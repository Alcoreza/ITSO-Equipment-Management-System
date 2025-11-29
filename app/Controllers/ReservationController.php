<?php

namespace App\Controllers;

use App\Models\Reservations_model;
use App\Models\Equipment_model;
use App\Models\Users_model;
use CodeIgniter\Controller;

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
            session()->setFlashdata('error', 'Please check the form and try again.');
            return redirect()->back()->withInput();
        }

        $associateName = $this->request->getPost('associate_name');
        $email = $this->request->getPost('email');
        $equipment_name = $this->request->getPost('equipment_name');
        $reserve_date = $this->request->getPost('reserve_date');
        $notes = $this->request->getPost('notes');

        // Lookup user by email (more reliable) and ensure active status
        $usersModel = new Users_model();
        $user = $usersModel->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Associate not found.');
            return redirect()->back()->withInput();
        }

        if (isset($user['status']) && $user['status'] != 1) {
            session()->setFlashdata('error', 'This account is inactive and cannot make reservations.');
            return redirect()->back()->withInput();
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
            session()->setFlashdata('error', 'No available equipment of this type.');
            return redirect()->back()->withInput();
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
            session()->setFlashdata('error', 'This equipment is already reserved for the selected date.');
            return redirect()->back()->withInput();
        }

        // Insert reservation
        $reservationId = $reservationModel->insert([
            'user_id' => $user_id,
            'email' => $email,
            'equipment_id' => $equipment_id,
            'reserve_date' => $reserve_date,
            'notes' => $notes,
            'status' => 'reserved'
        ]);

        // Mark equipment as unavailable
        $equipmentModel->update($equipment_id, ['available' => 0]);

        // Send confirmation email
        $this->sendReservationEmail($associateName, $email, $equipment_name, $reserve_date, $notes, $equipment_id);

        session()->setFlashdata('success', 'Equipment reserved successfully! Confirmation email has been sent.');
        return redirect()->to('/reservation');
    }

    private function sendReservationEmail($associateName, $email, $equipment_name, $reserve_date, $notes, $equipment_id)
    {
        // Format the reservation date
        $formattedDate = date('F d, Y', strtotime($reserve_date));

        // Prepare email message
        $message = "<h2>Hello, " . esc($associateName) . "!</h2><br>"
            . "<p>Your equipment reservation has been confirmed.</p>"
            . "<div style='background-color:#f8f9fa;padding:20px;border-radius:8px;margin:20px 0;'>"
            . "<h3 style='margin-top:0;color:#007bff;'>Reservation Details</h3>"
            . "<p><strong>Equipment:</strong> " . esc($equipment_name) . "</p>"
            . "<p><strong>Equipment ID:</strong> #" . esc($equipment_id) . "</p>"
            . "<p><strong>Reserved Date:</strong> " . $formattedDate . "</p>"
            . "<p><strong>Reserved By:</strong> " . esc($associateName) . "</p>"
            . "<p><strong>Email:</strong> " . esc($email) . "</p>";
        
        if (!empty($notes)) {
            $message .= "<p><strong>Notes:</strong> " . esc($notes) . "</p>";
        }
        
        $message .= "</div>"
            . "<p>Please make sure to pick up the equipment on the reserved date.</p>"
            . "<p>If you need to cancel or modify your reservation, please contact the ITSO office immediately.</p>"
            . "<br><p>Best regards,<br>ITSO EMS Team</p>";

        // Send email
        $emailService = service('email');
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($email);
        $emailService->setSubject('ITSO EMS - Equipment Reservation Confirmation');
        $emailService->setMessage($message);

        if (!$emailService->send()) {
            log_message('error', 'Reservation confirmation email failed to send to ' . $email);
        } else {
            log_message('info', 'Reservation confirmation email sent to ' . $email . ' for equipment: ' . $equipment_name);
        }
    }
}