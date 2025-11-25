<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Equipment_model;

class AdminController extends BaseController
{
    // ===============================
    // USERS MANAGEMENT
    // ===============================
    public function users()
    {
        $usersModel = new Users_model();

        // Fetch all users from DB
        $allUsers = $usersModel->findAll();

        $data = [
            'title' => 'User Management - ITSO EMS',
            'bodyClass' => 'users-page',
            'users' => $allUsers
        ];

        return view('include/head_view', $data)
            . view('include/nav_view', $data)
            . view('users_view', $data)
            . view('include/foot_view', $data);
    }

    // Update user details
    public function updateUser()
    {
        $usersModel = new Users_model();

        $id = $this->request->getPost('id');

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'role'       => $this->request->getPost('role'),
        ];

        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');

        // Only update password if user typed one
        if (!empty($password)) {
            if ($password !== $confirm) {
                return redirect()->back()->with('error', 'Passwords do not match.');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $usersModel->update($id, $data);

        return redirect()->to('/users')->with('success', 'User updated successfully.');
    }

    // View user details
    public function getUser($id)
    {
        $usersModel = new Users_model();
        $user = $usersModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        // Convert status 1/0 to Active/Inactive
        $user['status_text'] = isset($user['status']) && $user['status'] == 1 ? 'Active' : 'Inactive';

        return $this->response->setJSON($user);
    }

    // ===============================
    // EQUIPMENT MANAGEMENT
    // ===============================
    public function equipment()
    {
        $equipmentModel = new Equipment_model();

        // Fetch all equipment items from DB
        $equipment = $equipmentModel->findAll();

        $data = [
            'title' => 'Equipment Management - ITSO EMS',
            'bodyClass' => 'equipment-page',
            'active' => 'equipment',
            'equipment' => $equipment, // PASS data to view
        ];

        return view('include/head_view', $data)
            . view('include/nav_view', $data)
            . view('equipment_view', $data)
            . view('include/foot_view', $data);
    }
}
