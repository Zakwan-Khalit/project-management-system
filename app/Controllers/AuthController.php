<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\UserRoleModel;
use App\Models\RoleModel;
use App\Models\ActivityLogModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $userProfileModel;
    protected $userRoleModel;
    protected $roleModel;
    protected $activityLog;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userProfileModel = new UserProfileModel();
        $this->userRoleModel = new UserRoleModel();
        $this->roleModel = new RoleModel();
        $this->activityLog = new ActivityLogModel();
    }
    
    public function login()
    {
        // Handle POST request (AJAX login)
        if ($this->request->getMethod() === 'POST') {
            log_message('info', 'Login POST request received');
            
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            log_message('info', 'Attempting login for email: ' . $email);
            
            try {
                $user = $this->userModel->getUserByEmail($email);
                
                if ($user && password_verify($password, $user['password'])) {
                    if ($user['is_active']) {
                        log_message('info', 'Login successful for user ID: ' . $user['id']);
                        
                        // Update last login
                        $this->userModel->updateLastLogin($user['id']);
                        
                        // Set session data
                        $sessionData = [
                            'id' => $user['id'],
                            'email' => $user['email'],
                            'first_name' => $user['first_name'] ?? '',
                            'last_name' => $user['last_name'] ?? '',
                            'role_id' => $user['role_id'] ?? null,
                            'role_name' => $user['role_name'] ?? '',
                            'avatar' => $user['avatar'] ?? null,
                            'is_logged_in' => true
                        ];
                        
                        session()->set('userdata', $sessionData);
                        session()->set('is_logged_in', true);
                        
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Login successful',
                            'redirect' => base_url('dashboard')
                        ]);
                    } else {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Account is deactivated'
                        ]);
                    }
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Invalid email or password'
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Login error: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unable to connect to server. Please check your connection and try again.'
                ]);
            }
        }
        
        // Handle GET request (show login form)
        $data = [];
        $this->template->auth("auth/login", $data);
    }
    
    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'role' => 'developer', // Default user role
                'is_active' => 1
            ];
            
            if ($this->userModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Registration successful. Please login.',
                    'redirect' => base_url('login')
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Registration failed',
                    'errors' => $this->userModel->errors()
                ]);
            }
        }
        
        // Use auth template for register page
        return $this->template
            ->set('title', 'Register')
            ->auth('auth/register');
    }
    
    public function logout()
    {
        // Simply destroy session without logging activity
        // This prevents database errors during logout
        session()->destroy();
        
        // Clear any remaining session data
        session()->remove(['userdata', 'is_logged_in', 'login_time']);
        
        // Redirect to login page
        return redirect()->to(base_url('login'));
    }
    
    public function profile()
    {
        $userData = session('userdata');
        $userId = $userData['id'] ?? null;
        
        if (!$userId) {
            return redirect()->to(base_url('login'));
        }
        
        $user = $this->userModel->find($userId);
        
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'phone' => $this->request->getPost('phone')
            ];
            
            // Handle avatar upload
            $avatar = $this->request->getFile('avatar');
            if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
                $newName = $avatar->getRandomName();
                $avatar->move(WRITEPATH . 'uploads/avatars', $newName);
                $data['avatar'] = $newName;
            }
            
            if ($this->userModel->update($userId, $data)) {
                // Update session userdata
                $updatedUserData = array_merge($userData, [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name']
                ]);
                
                if (isset($data['avatar'])) {
                    $updatedUserData['avatar'] = $data['avatar'];
                }
                
                session()->set('userdata', $updatedUserData);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Profile updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update profile',
                    'errors' => $this->userModel->errors()
                ]);
            }
        }
        
        return $this->template->member('auth/profile', ['user' => $user]);
    }
    
    public function changePassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $userData = session('userdata');
            $userId = $userData['id'] ?? null;
            
            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session expired. Please login again.'
                ]);
            }
            
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_password');
            
            if ($newPassword !== $confirmPassword) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'New passwords do not match'
                ]);
            }
            
            $user = $this->userModel->find($userId);
            
            if (!password_verify($currentPassword, $user['password'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ]);
            }
            
            if ($this->userModel->update($userId, ['password' => $newPassword])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password changed successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to change password'
                ]);
            }
        }
    }
    
    public function checkLogin()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        log_message('info', 'Login check POST request received');
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        log_message('info', 'Attempting login for email: ' . $email);
        
        $user = $this->userModel->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_active']) {
                log_message('info', 'Login successful for user ID: ' . $user['id']);
                
                // Update last login
                $this->userModel->updateLastLogin($user['id']);
                
                // Set session with userdata structure
                $userData = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'role' => $user['role'],
                    'avatar' => $user['avatar'],
                    'is_active' => $user['is_active'],
                    'created_at' => $user['created_at'],
                    'last_login' => $user['last_login']
                ];
                
                session()->set([
                    'userdata' => $userData,
                    'is_logged_in' => true,
                    'login_time' => time()
                ]);
                
                log_message('info', 'Session set, returning success response');
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => base_url('dashboard')
                ]);
            } else {
                log_message('warning', 'Login failed - account deactivated for email: ' . $email);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Account is deactivated'
                ]);
            }
        } else {
            log_message('warning', 'Login failed - invalid credentials for email: ' . $email);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
        }
    }
    
    // Placeholder for settings page
    public function settings()
    {
        // You can implement real settings logic here later
        return $this->template->member('auth/settings', ['title' => 'Settings']);
    }
}
