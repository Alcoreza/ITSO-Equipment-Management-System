<?php

namespace App\Controllers;

class AdminController extends BaseController
{
   public function users()
{
    $usersModel = new \App\Models\Users_model();

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

//update user details
public function updateUser()
{
    $usersModel = new \App\Models\Users_model();
    // Get and validate id
    $id = $this->request->getPost('id');
    if (empty($id) || !is_numeric($id)) {
        return redirect()->back()->with('error', 'Invalid user id.');
    }

    // Collect updatable fields
    $data = [
        'first_name' => $this->request->getPost('first_name'),
        'last_name'  => $this->request->getPost('last_name'),
        'email'      => $this->request->getPost('email'),
    ];

    // Only include role when it's provided (prevents overwriting with blank)
    $role = $this->request->getPost('role');
    $role = is_string($role) ? trim($role) : '';
    if ($role !== '') {
        $data['role'] = $role;
    }

    // Password handling: only update when a non-empty value is provided
    $password = $this->request->getPost('password');
    $confirm  = $this->request->getPost('password_confirm');
    $password = is_string($password) ? trim($password) : '';

    if ($password !== '') {
        if ($password !== $confirm) {
            return redirect()->back()->with('error', 'Passwords do not match.')->withInput();
        }
        // Hash and set password
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    // Perform update and check result
    try {
        $updated = $usersModel->update((int)$id, $data);

        // Check for DB-level errors
        $dbError = [];
        if (isset($usersModel->db)) {
            $dbError = $usersModel->db->error();
        }

        if (!empty($dbError) && !empty($dbError['code'])) {
            // Log and return error message to help debug
            log_message('error', 'User update DB error: ' . $dbError['message'] . ' (code: ' . $dbError['code'] . ')');
            return redirect()->back()->with('error', 'Database error: ' . $dbError['message'])->withInput();
        }

        if ($updated === false) {
            // Model may return false on failure
            return redirect()->back()->with('error', 'Failed to update user.')->withInput();
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
    }

    return redirect()->to('/users')->with('success', 'User updated successfully.');
}


//view user details
public function getUser($id)
{
    $usersModel = new \App\Models\Users_model();
    $user = $usersModel->find($id);

    if (!$user) {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
    }

    // Convert status (1/0) to Active/Inactive
    $user['status_text'] = ($user['status'] == 1) ? 'Active' : 'Inactive';

    // Return user data as JSON
    return $this->response->setJSON($user);
}




 public function equipment()
    {
        $data = [
            'title' => 'Equipment Management - ITSO EMS',
            'bodyClass' => 'equipment-page',
            'active' => 'equipment'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view', $data)
            . view('equipment_view', $data)   // front-end only
            . view('include/foot_view', $data);
    }
}



/* public function users()
{
    if (session()->get('role') !== 'itso') {
        return redirect()->to('login')->with('error', 'Unauthorized access.');
    }

    return view('admin/users');
} */