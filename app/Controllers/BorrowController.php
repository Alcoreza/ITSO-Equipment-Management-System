<?php

namespace App\Controllers;

class BorrowController extends BaseController
{
    // Display the Borrow form
    public function index()
    {
        $data = [
            'title' => 'Borrow Equipment'
        ];

        return view('borrow_view', $data);
    }

    // Handle form submission
    public function submit()
    {
        // Retrieve POST data
        $borrower_name = $this->request->getPost('borrower_name');
        $email = $this->request->getPost('email');
        $equipment = $this->request->getPost('equipment_id');
        $return_date = $this->request->getPost('return_date');

        // For now, just log the submission
        log_message('info', "Borrow submitted: $borrower_name, $email, $equipment, $return_date");

        // Set flashdata message for success
        session()->setFlashdata('success', 'Equipment borrow recorded successfully!');

        // Redirect back to the borrow page
        return redirect()->to('/borrow');
    }
}
