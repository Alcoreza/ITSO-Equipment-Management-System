<?php

namespace App\Controllers;

use App\Models\Borrowed_model;
use App\Models\Equipment_model;
use App\Models\Users_model;

class BorrowController extends BaseController
{
    public function index()
    {
        $equipmentModel = new Equipment_model();

        // Get distinct equipment names where available
        $builder = $equipmentModel->builder();
        $builder->select('equipment_name');
        $builder->where('available', 1);
        $builder->groupBy('equipment_name');
        $distinctEquipment = $builder->get()->getResultArray();

        $data = [
            'title' => 'Borrow Equipment - ITSO EMS',
            'equipment_list' => $distinctEquipment
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('borrow_view', $data)
            . view('include/foot_view');
    }

    public function submit()
    {
        $validation = $this->validate([
            'borrower_name'   => 'required|string',
            'email'           => 'required|valid_email',
            'equipment_name'  => 'required|string',
            'return_date'     => 'permit_empty|valid_date'
        ]);

        if (!$validation) {
            session()->setFlashdata('error', 'Please check the form and try again.');
            return redirect()->back()->withInput();
        }

        $borrowerName    = $this->request->getPost('borrower_name');
        $email           = $this->request->getPost('email');
        $equipment_name  = $this->request->getPost('equipment_name');
        $return_date     = $this->request->getPost('return_date');

        // Lookup user by email
        $usersModel = new Users_model();
        $user = $usersModel->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Borrower not found.');
            return redirect()->back()->withInput();
        }

        // Prevent inactive users from borrowing
        if (isset($user['status']) && $user['status'] != 1) {
            session()->setFlashdata('error', 'This account is inactive and cannot borrow equipment.');
            return redirect()->back()->withInput();
        }

        $borrower_id = $user['id'];

        // Find first available equipment with that name
        $equipmentModel = new Equipment_model();
        $equipment = $equipmentModel
            ->where('equipment_name', $equipment_name)
            ->where('available', 1)
            ->orderBy('equipment_id', 'ASC')
            ->first();

        if (!$equipment) {
            session()->setFlashdata('error', 'Selected equipment is currently unavailable.');
            return redirect()->back()->withInput();
        }

        $equipment_id = $equipment['equipment_id'];

        // Insert borrow record
        $borrowModel = new Borrowed_model();
        $borrowModel->insert([
            'borrower_name'   => $borrowerName,
            'borrower_id'     => $borrower_id,
            'email'           => $email,
            'equipment_id'    => $equipment_id,
            'return_date'     => $return_date,
            'status'          => 'borrowed'
        ]);

        // Mark equipment as unavailable
        $equipmentModel->update($equipment_id, ['available' => 0]);

        session()->setFlashdata('success', 'Equipment borrow recorded successfully!');
        return redirect()->to('/borrow');
    }
}
