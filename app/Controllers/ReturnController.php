<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ReturnController extends BaseController
{
    // Display the Return form
    public function index()
    {
        $data = [
            'title' => 'Return Equipment - ITSO EMS'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('return_view', $data)
            . view('include/foot_view');
    }

    // Handle form submission
    public function submit()
    {
        // Retrieve POST data
        $borrower_name = $this->request->getPost('borrower_name');
        $email = $this->request->getPost('email');
        $equipment = $this->request->getPost('equipment');
        $return_date = $this->request->getPost('return_date');

        // For now, just simulate saving data
        // In the future, save to DB and send email
        // Example: log the return (temporary)
        log_message('info', "Return submitted: $borrower_name, $email, $equipment, $return_date");

        // Redirect back with success message (Flashdata)
        session()->setFlashdata('success', 'Equipment return recorded successfully!');
        return redirect()->to('/return');
    }
}