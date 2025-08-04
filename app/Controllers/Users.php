<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        try {
            $data = [
                'title' => 'User Management',
                'users' => $this->userModel->getAllUsersWithDetails(),
                'departments' => $this->userModel->getDepartments(),
                'positions' => $this->userModel->getPositions()
            ];

            return $this->template->member('users/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Users index error: ' . $e->getMessage());
            $data = [
                'title' => 'User Management',
                'error_message' => 'Unable to load users data. Please try again later.',
                'users' => [],
                'departments' => [],
                'positions' => []
            ];
            return $this->template->member('users/index', $data);
        }
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->store();
        }

        try {
            $data = [
                'title' => 'Add User',
                'departments' => $this->userModel->getDepartments(),
                'positions' => $this->userModel->getPositions()
            ];

            return $this->template->member('users/create', $data);
        } catch (\Exception $e) {
            log_message('error', 'Users create error: ' . $e->getMessage());
            return redirect()->to('/users')->with('error', 'Unable to load user creation form.');
        }
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'full_name' => 'required|min_length[3]|max_length[128]',
            'phone' => 'permit_empty|max_length[32]',
            'department_id' => 'required|is_natural',
            'position_id' => 'required|is_natural',
            'is_active' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $userData = [
                'email' => $this->request->getPost('email'),
                'password' => password_hash('123qwe', PASSWORD_DEFAULT), // Default password
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                'email_verified_at' => date('Y-m-d H:i:s')
            ];

            $userId = $this->userModel->createUser($userData);

            if ($userId) {
                $profileData = [
                    'user_id' => $userId,
                    'full_name' => $this->request->getPost('full_name'),
                    'phone' => $this->request->getPost('phone')
                ];

                $this->userModel->createUserProfile($profileData);

                $relData = [
                    'user_id' => $userId,
                    'department_id' => $this->request->getPost('department_id'),
                    'position_id' => $this->request->getPost('position_id'),
                    'is_active' => 1
                ];

                $this->userModel->createUserRel($relData);

                return redirect()->to('/users')->with('success', 'User created successfully! Default password: 123qwe');
            }

            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        } catch (\Exception $e) {
            log_message('error', 'User creation error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit($id)
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->update($id);
        }

        try {
            $user = $this->userModel->getUserWithDetails($id);
            if (!$user) {
                return redirect()->to('/users')->with('error', 'User not found.');
            }

            $data = [
                'title' => 'Edit User',
                'user' => $user,
                'departments' => $this->userModel->getDepartments(),
                'positions' => $this->userModel->getPositions()
            ];

            return $this->template->member('users/edit', $data);
        } catch (\Exception $e) {
            log_message('error', 'Users edit error: ' . $e->getMessage());
            return redirect()->to('/users')->with('error', 'Unable to load user for editing.');
        }
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'full_name' => 'required|min_length[3]|max_length[128]',
            'phone' => 'permit_empty|max_length[32]',
            'department_id' => 'required|is_natural',
            'position_id' => 'required|is_natural',
            'is_active' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }
            
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $userData = [
                'email' => $this->request->getPost('email'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];

            $this->userModel->updateUser($id, $userData);

            $profileData = [
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone')
            ];

            $this->userModel->updateUserProfile($id, $profileData);

            $relData = [
                'department_id' => $this->request->getPost('department_id'),
                'position_id' => $this->request->getPost('position_id')
            ];

            $this->userModel->updateUserRel($id, $relData);

            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User updated successfully!'
                ]);
            }

            return redirect()->to('/users')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            log_message('error', 'User update error: ' . $e->getMessage());
            
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user. Please try again.'
                ]);
            }
            
            return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function delete($id)
    {
        try {
            $this->userModel->deleteUser($id);
            return redirect()->to('/users')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            log_message('error', 'User deletion error: ' . $e->getMessage());
            return redirect()->to('/users')->with('error', 'Failed to delete user.');
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = $this->userModel->getUserById($id);
            if (!$user) {
                return $this->response->setJSON(['error' => 'User not found']);
            }

            $newStatus = $user['is_active'] ? 0 : 1;
            $this->userModel->updateUser($id, ['is_active' => $newStatus]);

            return $this->response->setJSON([
                'success' => true,
                'new_status' => $newStatus,
                'message' => 'User status updated successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'User toggle status error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to update user status']);
        }
    }

    public function getPositionsByDepartment()
    {
        try {
            $departmentId = $this->request->getPost('department_id');
            
            if (!$departmentId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Department ID is required'
                ]);
            }
            
            $positions = $this->userModel->getPositionsByDepartment($departmentId);
            
            return $this->response->setJSON([
                'success' => true,
                'positions' => $positions
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Get positions by department error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load positions'
            ]);
        }
    }
}
