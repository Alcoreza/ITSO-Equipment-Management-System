<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function users()
    {
        $data = [
            'title' => 'User Management - ITSO EMS',
            'bodyClass' => 'users-page',
        ];

        return view('include/head_view', $data)
            . view('include/nav_view', $data)      // render sidebar here
            . view('users_view', $data)
            . view('include/foot_view', $data);
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


