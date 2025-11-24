<?php

namespace App\Controllers;

use App\Models\Reservations_model;
use App\Models\Equipment_model;
use App\Models\Users_model;

class ReservationController extends BaseController
{
    public function index()
    {
        $equipmentModel = new Equipment_model();

        // Get distinct equipment names that are currently available
        $builder = $equipmentModel->builder();
        $builder->select('equipment_name');
        $builder->where('available', 1);
        $builder->groupBy('equipment_name');
        $availableEquipment = $builder->get()->getResultArray();

        $data = [
            'title' => 'Reservation - ITSO EMS',
            'equipment_list' => $availableEquipment
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('reservation_view', $data)
            . view('include/foot_view');
    }

    public function submitReservation()
    {
        $validation = $this->validate([
            'associate_name' => 'required|string',
            'email' => 'required|valid_email',
            'equipment_name' => 'required|string',
            'reserve_date' => 'required|valid_date',
            'notes' => 'permit_empty|string'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Please check the form and try again.');
        }

        $associateName = $this->request->getPost('associate_name');
        $email = $this->request->getPost('email');
        $equipment_name = $this->request->getPost('equipment_name');
        $reserve_date = $this->request->getPost('reserve_date');
        $notes = $this->request->getPost('notes');

        // Lookup user by first name
        $usersModel = new Users_model();
        $user = $usersModel->where('first_name', $associateName)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Associate not found.');
        }

        $user_id = $user['id'];

        // Find the first available equipment of this type
        $equipmentModel = new Equipment_model();
        $equipment = $equipmentModel
            ->where('equipment_name', $equipment_name)
            ->where('available', 1)
            ->orderBy('equipment_id', 'ASC')
            ->first();

        if (!$equipment) {
            return redirect()->back()->withInput()->with('error', 'No available equipment of this type.');
        }

        $equipment_id = $equipment['equipment_id'];

        // Prevent duplicate reservation for the same equipment on the same date
        $reservationModel = new Reservations_model();
        $existing = $reservationModel
            ->where('equipment_id', $equipment_id)
            ->where('reserve_date', $reserve_date)
            ->where('status', 'reserved')
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'This equipment is already reserved for the selected date.');
        }

        // Insert reservation
        $reservationModel->insert([
            'user_id' => $user_id,
            'email' => $email,
            'equipment_id' => $equipment_id,
            'reserve_date' => $reserve_date,
            'notes' => $notes,
            'status' => 'reserved'
        ]);

        // Mark equipment as unavailable
        $equipmentModel->update($equipment_id, ['available' => 0]);

        session()->setFlashdata('success', 'Equipment reserved successfully!');
        return redirect()->to('/reservation');
    }
}
