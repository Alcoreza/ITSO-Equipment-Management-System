<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'About - ITSO EMS',
        );

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('about_view', $data)
            . view('include/foot_view');
    }
}
