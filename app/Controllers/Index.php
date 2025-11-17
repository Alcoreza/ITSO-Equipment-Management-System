<?php
namespace App\Controllers;

class Index extends BaseController
{
    public function index()
    {
        $data = array(
            'title' => 'ITSO Equipment Management System',
            'total_equipment' => '--', // placeholder
            'active_users' => '--'     // placeholder
        );

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('main_view', $data)
            . view('include/foot_view');
    }
}
?>