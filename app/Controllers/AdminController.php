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

<<<<<<< HEAD
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
=======
//update user details
public function updateUser()
{
    $usersModel = new \App\Models\Users_model();

    $id = $this->request->getPost('id');

    $data = [
        'first_name' => $this->request->getPost('first_name'),
        'last_name'  => $this->request->getPost('last_name'),
        'email'      => $this->request->getPost('email'),
        'role'       => $this->request->getPost('role'), // ✅ added role
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
>>>>>>> e7ce3e73e1eed9ef6c274731dbb92a46497e7c66
}


//view user details
public function getUser($id)
{
    $usersModel = new \App\Models\Users_model();
    $user = $usersModel->find($id);

    if (!$user) {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
    }

    // Convert status 1/0 to Active/Inactive
    $user['status_text'] = isset($user['status']) && $user['status'] == 1 ? 'Active' : 'Inactive';

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