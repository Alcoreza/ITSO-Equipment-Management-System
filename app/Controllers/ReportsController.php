<?php

namespace App\Controllers;

class ReportsController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Reports - ITSO EMS'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('reports_view', $data)
            . view('include/foot_view');
    }
}