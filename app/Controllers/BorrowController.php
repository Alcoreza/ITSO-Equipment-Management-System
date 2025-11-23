<?php

namespace App\Controllers;

use App\Models\Borrowed_model;
use CodeIgniter\Controller;

class BorrowController extends BaseController
{
    // Display the Borrow form
    public function index()
    {
        $data = [
            'title' => 'Borrow Equipment - ITSO EMS'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('borrow_view', $data)
            . view('include/foot_view');
    }

    // Handle form submission
    public function submit()
    {
        // Validate form inputs
        $validation = $this->validate([
            'borrower_id' => 'required|numeric',
            'email'       => 'required|valid_email',
            'equipment_id'=> 'required|numeric',
            'return_date' => 'permit_empty|valid_date'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Please check the form and try again.');
        }

        // Load model
        $borrowModel = new Borrowed_model();

        // Prepare data for DB
        $data = [
            'borrower_id' => $this->request->getPost('borrower_id'),
            'email'       => $this->request->getPost('email'),
            'equipment_id'=> $this->request->getPost('equipment_id'),
            'return_date' => $this->request->getPost('return_date')
        ];

        // Insert into database
        $borrowModel->insert($data);

        // Flash success message
        session()->setFlashdata('success', 'Equipment borrow recorded successfully!');

        return redirect()->to('/borrow');
    }
}
