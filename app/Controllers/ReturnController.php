<?php

namespace App\Controllers;

use App\Models\Returned_model;
use App\Models\Borrowed_model;
use App\Models\Equipment_model;
use App\Models\Users_model;

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
            session()->setFlashdata('error', 'Please check the form and try again.');
            return redirect()->back()->withInput();
        }

        $borrowerName   = $this->request->getPost('borrower_name');
        $email          = $this->request->getPost('email');
        $equipment_name = $this->request->getPost('equipment_name');
        $return_date    = $this->request->getPost('return_date');

        // Lookup borrower by email (use email to identify user)
        $usersModel = new Users_model();
        $user = $usersModel->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Borrower not found.');
            return redirect()->back()->withInput();
        }

        // Prevent inactive users from returning
        if (isset($user['status']) && $user['status'] != 1) {
            session()->setFlashdata('error', 'This account is inactive and cannot return equipment.');
            return redirect()->back()->withInput();
        }

        $borrower_id = $user['id'];

        // Find the first active borrow for this borrower and equipment
        $borrowModel = new Borrowed_model();
        $borrowQuery = $borrowModel
            ->select('borrowed_items.*, equipment.equipment_name')
            ->join('equipment', 'borrowed_items.equipment_id = equipment.equipment_id')
            ->where('equipment.equipment_name', $equipment_name)
            // Accept both explicit 'borrowed' status and legacy NULL status
            ->where("(borrowed_items.status = 'borrowed' OR borrowed_items.status IS NULL)")
            ->orderBy('borrowed_items.id', 'ASC');

        // Primary lookup by borrower_id (preferred)
        $borrow = (clone $borrowQuery)->where('borrowed_items.borrower_id', $borrower_id)->first();

        // Fallback: try matching by email (in case borrower_id wasn't set correctly)
        if (!$borrow) {
            log_message('info', "Return lookup: no borrow found by borrower_id={$borrower_id} for equipment={$equipment_name}. Trying by email={$email}.");
            $borrow = (clone $borrowQuery)->where('borrowed_items.email', $email)->first();
        }

        // Fallback #2: try matching by borrower_name (in case older records used a name column)
        if (!$borrow) {
            log_message('info', "Return lookup: no borrow found by email={$email} for equipment={$equipment_name}. Trying by borrower_name={$borrowerName}.");
            $borrow = (clone $borrowQuery)->where('borrowed_items.borrower_name', $borrowerName)->first();
        }

        if (!$borrow) {
            log_message('warning', "Return failed: no active borrowed record. borrower_id={$borrower_id}, email={$email}, borrower_name={$borrowerName}, equipment_name={$equipment_name}");
            session()->setFlashdata('error', 'No active borrowed record for this equipment. Please check the borrower and equipment details.');
            return redirect()->back()->withInput();
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
