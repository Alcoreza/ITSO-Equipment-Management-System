<?php

namespace App\Controllers;

use App\Models\Borrowed_model;
use App\Models\Equipment_model;
use App\Models\Users_model;
use CodeIgniter\Controller;

class BorrowController extends BaseController
{
    // Show the Borrow Equipment Form
    public function index()
    {
        $equipmentModel = new Equipment_model();

        // Get the distinct equipment names that are available
        $builder = $equipmentModel->builder();
        $builder->select('equipment_name');
        $builder->where('available', 1); // Only show available equipment
        $builder->groupBy('equipment_name');
        $distinctEquipment = $builder->get()->getResultArray();

        // Pass the equipment data to the view
        $data = [
            'title' => 'Borrow Equipment - ITSO EMS',
            'equipment_list' => $distinctEquipment
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('borrow_view', $data)
            . view('include/foot_view');
    }

    // Handle form submission (Insert Borrow data into the database)
    public function submit()
    {
        // Validate the form data
        $validation = $this->validate([
            'borrower_name'  => 'required|string',
            'email'          => 'required|valid_email',
            'equipment_name' => 'required|string',
            'return_date'    => 'permit_empty|valid_date'
        ]);

        if (!$validation) {
            log_message('error', 'Validation failed: ' . print_r($this->validator->getErrors(), true));
            return redirect()->back()->withInput()->with('error', 'Please check the form and try again.');
        }

        $borrowerName = $this->request->getPost('borrower_name');
        $email = $this->request->getPost('email');
        $equipment_name = $this->request->getPost('equipment_name');
        $return_date = $this->request->getPost('return_date');

        // Lookup user by first name
        $usersModel = new Users_model();
        $user = $usersModel->where('first_name', $borrowerName)->first();

        if (!$user) {
            log_message('error', "User not found: " . $borrowerName);
            return redirect()->back()->withInput()->with('error', 'Borrower not found.');
        }

        $borrower_id = $user['id'];
        log_message('info', 'Borrower found: ' . print_r($user, true));

        // Find the first available equipment with that name
        $equipmentModel = new Equipment_model();
        $equipment = $equipmentModel
            ->where('equipment_name', $equipment_name)
            ->where('available', 1)
            ->orderBy('equipment_id', 'ASC')
            ->first();

        if (!$equipment) {
            log_message('error', "Equipment not available: " . $equipment_name);
            return redirect()->back()->withInput()->with('error', 'Selected equipment is currently unavailable.');
        }

        $equipment_id = $equipment['equipment_id'];
        log_message('info', 'Equipment found: ' . print_r($equipment, true));

        // Insert the borrow record into the database
        $borrowModel = new Borrowed_model();
        $borrowData = [
            'borrower_id' => $borrower_id,
            'email' => $email,
            'equipment_id' => $equipment_id,
            'return_date' => $return_date,
            'status' => 'borrowed' // default status is 'borrowed'
        ];

        log_message('info', 'Inserting Borrow Data: ' . print_r($borrowData, true));

        $inserted = $borrowModel->insert($borrowData);
        if ($inserted === false) {
            log_message('error', 'Failed to insert borrow data: ' . print_r($borrowModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to record borrow data.');
        }

        log_message('info', 'Borrow data inserted successfully!');

        // Mark the equipment as unavailable (update the equipment status)
        $updated = $equipmentModel->update($equipment_id, ['available' => 0]);
        if (!$updated) {
            log_message('error', 'Failed to update equipment availability: ' . print_r($equipmentModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to update equipment status.');
        }

        log_message('info', 'Equipment availability updated successfully.');

        // Set flashdata message for success
        session()->setFlashdata('success', 'Equipment borrow recorded successfully!');
        return redirect()->to('/borrow');
    }
}
