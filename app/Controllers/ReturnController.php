<?php

namespace App\Controllers;

use App\Models\Returned_model;
use App\Models\Borrowed_model;
use App\Models\Equipment_model;
use App\Models\Users_model;
use CodeIgniter\Controller;

class ReturnController extends BaseController
{
    // Display the Return form
    public function index()
    {
        $equipmentModel = new Equipment_model();

        // Get distinct equipment names that are currently borrowed (status = 'borrowed')
        $borrowedModel = new Borrowed_model();
        $builder = $borrowedModel->builder();
        $builder->select('equipment.equipment_name')
                ->join('equipment', 'borrowed_items.equipment_id = equipment.equipment_id')
                ->where('borrowed_items.status', 'borrowed')
                ->groupBy('equipment.equipment_name');
        $borrowedEquipment = $builder->get()->getResultArray();

        $data = [
            'title' => 'Return Equipment - ITSO EMS',
            'equipment_list' => $borrowedEquipment
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('return_view', $data)
            . view('include/foot_view');
    }

    // Handle form submission
    public function submit()
    {
        $validation = $this->validate([
            'borrower_name'   => 'required|string',
            'email'           => 'required|valid_email',
            'equipment_name'  => 'required|string',
            'return_date'     => 'required|valid_date'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Please check the form and try again.');
        }

        $borrowerName   = $this->request->getPost('borrower_name');
        $email          = $this->request->getPost('email');
        $equipment_name = $this->request->getPost('equipment_name');
        $return_date    = $this->request->getPost('return_date');

        // Lookup borrower_id
        $usersModel = new Users_model();
        $user = $usersModel->where('first_name', $borrowerName)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Borrower not found.');
        }

        $borrower_id = $user['id'];

        // Find the first active borrow for this borrower and equipment
        $borrowModel = new Borrowed_model();
        $borrow = $borrowModel
            ->select('borrowed_items.*, equipment.equipment_name')
            ->join('equipment', 'borrowed_items.equipment_id = equipment.equipment_id')
            ->where('borrowed_items.borrower_id', $borrower_id)
            ->where('equipment.equipment_name', $equipment_name)
            ->where('borrowed_items.status', 'borrowed')
            ->orderBy('borrowed_items.id', 'ASC')
            ->first();

        if (!$borrow) {
            return redirect()->back()->withInput()->with('error', 'No active borrowed record for this equipment.');
        }

        $borrow_id    = $borrow['id'];
        $equipment_id = $borrow['equipment_id'];

        // Insert return record
        $returnedModel = new Returned_model();
        $returnedModel->insert([
            'borrow_id'    => $borrow_id,
            'borrower_id'  => $borrower_id,
            'email'        => $email,
            'equipment_id' => $equipment_id,
            'return_date'  => $return_date
        ]);

        // Update borrowed_items status to 'returned'
        $borrowModel->update($borrow_id, ['status' => 'returned']);

        // Mark equipment as available
        $equipmentModel = new Equipment_model();
        $equipmentModel->update($equipment_id, ['available' => 1]);

        // Temporary log (to be replaced with actual DB or email sending)
        log_message('info', "Return submitted: $borrowerName, $email, $equipment_name, $return_date");

        // Redirect back with success message (Flashdata)
        session()->setFlashdata('success', 'Equipment return recorded successfully!');
        return redirect()->to('/return');
    }
}
