<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        $userId = user_id();
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User not found.');
        }

        // Get recent activity for the user
        $recentActivity = $this->activityLogModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'recentActivity' => $recentActivity,
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
        $user = $this->userModel->find($userId);

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
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User not found.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email|max_length[100]',
            'first_name' => 'permit_empty|max_length[50]',
            'last_name' => 'permit_empty|max_length[50]',
            'phone' => 'permit_empty|max_length[20]',
            'bio' => 'permit_empty|max_length[500]'
        ];

        // Add password validation only if password is provided
        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = 'min_length[6]|max_length[255]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
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

        $updateData = [
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'bio' => $this->request->getPost('bio'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update password if provided
        if (!empty($this->request->getPost('password'))) {
            $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->update($userId, $updateData);

            // Log the activity
            $this->activityLogModel->insert([
                'user_id' => $userId,
                'action' => 'profile_updated',
                'description' => 'Profile information updated',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Update session data
            session()->set([
                'email' => $updateData['email'],
                'first_name' => $updateData['first_name'],
                'last_name' => $updateData['last_name']
            ]);

            return redirect()->to(base_url('profile'))->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    public function changePassword()
    {
        if (!is_logged_in()) {
            return redirect()->to(base_url('login'));
        }

        $userId = user_id();
        $user = $this->userModel->find($userId);

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
        $user = $this->userModel->find($userId);

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
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        try {
            $this->userModel->update($userId, [
                'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Log the activity
            $this->activityLogModel->insert([
                'user_id' => $userId,
                'action' => 'password_changed',
                'description' => 'Password changed successfully',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->to(base_url('profile'))->with('success', 'Password changed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to change password. Please try again.');
        }
    }
}
