<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $activityLogModel;
    protected $template;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->template = new \App\Libraries\Template();
    }

    public function index()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        $userId = user_id();
        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User not found.');
        }
        // Get recent activity for the user
        $recentActivity = $this->activityLogModel->getUserActivity($userId, 10);
        // Get user stats (projects, tasks, etc.)
        $userStats = $this->userModel->getUserStats($userId);
        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'recentActivity' => $recentActivity,
            'userStats' => $userStats,
            'breadcrumbs' => [
                ['title' => 'My Profile']
            ]
        ];
        return $this->template->member('profile/index', $data);
    }

    public function edit()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        $userId = user_id();
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit Profile',
            'user' => $user,
            'breadcrumbs' => [
                ['title' => 'Profile', 'url' => base_url('profile')],
                ['title' => 'Edit Profile']
            ]
        ];

        return $this->template->member('profile/edit', $data);
    }

    public function update()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        $userId = user_id();
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User not found.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email|max_length[128]',
            'full_name' => 'permit_empty|max_length[128]',
            'phone' => 'permit_empty|max_length[32]'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }
            
            return $this->template->member('profile/edit', [
                'title' => 'Edit Profile',
                'user' => $user,
                'validation' => $validation,
                'breadcrumbs' => [
                    ['title' => 'Profile', 'url' => base_url('profile')],
                    ['title' => 'Edit Profile']
                ]
            ]);
        }

        try {
            // Update users table (email only)
            $userUpdateData = [
                'email' => $this->request->getPost('email'),
                'date_modified' => date('Y-m-d H:i:s')
            ];

            // Update users table
            $this->userModel->updateUser($userId, $userUpdateData);

            // Update user_profile table
            $profileUpdateData = [
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone')
            ];

            $this->userModel->updateUserProfile($userId, $profileUpdateData);

            // Log the activity
            $this->activityLogModel->logActivity([
                'user_id' => $userId,
                'project_id' => null,
                'action' => 'profile_updated',
                'description' => 'Profile information updated'
            ]);

            // Update session data
            session()->set([
                'email' => $userUpdateData['email'],
                'full_name' => $profileUpdateData['full_name']
            ]);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Profile updated successfully!'
                ]);
            }

            return redirect()->to(base_url('profile'))->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Profile update failed: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update profile. Please try again.'
                ]);
            }
            
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function changePassword()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        $userId = user_id();
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Change Password',
            'user' => $user,
            'breadcrumbs' => [
                ['title' => 'Profile', 'url' => base_url('profile')],
                ['title' => 'Change Password']
            ]
        ];

        return $this->template->member('profile/change_password', $data);
    }

    public function updatePassword()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        $userId = user_id();
        $user = $this->userModel->getUserWithPasswordById($userId);

        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User not found.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }
            
            return $this->template->member('profile/change_password', [
                'title' => 'Change Password',
                'user' => $user,
                'validation' => $validation,
                'breadcrumbs' => [
                    ['title' => 'Profile', 'url' => base_url('profile')],
                    ['title' => 'Change Password']
                ]
            ]);
        }

        // Verify current password
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Current password is incorrect.'
                ]);
            }
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        try {
            $this->userModel->updateUser($userId, [
                'password' => $this->request->getPost('new_password'),
                'date_modified' => date('Y-m-d H:i:s')
            ]);

            // Log the activity
            $this->activityLogModel->logActivity([
                'user_id' => $userId,
                'project_id' => null,
                'action' => 'password_changed',
                'description' => 'Password changed successfully'
            ]);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password changed successfully!'
                ]);
            }

            return redirect()->to(base_url('profile'))->with('success', 'Password changed successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Password change failed: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to change password. Please try again.'
                ]);
            }
            
            return redirect()->back()->with('error', 'Failed to change password. Please try again.');
        }
    }
}
