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

        // Validate that reservation is at least one day in advance
        $reserveDateTime = strtotime($reserve_date);
        $tomorrowDateTime = strtotime('+1 day', strtotime(date('Y-m-d')));
        
        if ($reserveDateTime < $tomorrowDateTime) {
            session()->setFlashdata('error', 'Reservations must be made at least one day in advance.');
            return redirect()->back()->withInput();
        }

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
        $this->sendReservationEmail($associateName, $email, $equipment_name, $reserve_date, $notes, $equipment_id, $reservationId);

        session()->setFlashdata('success', 'Equipment reserved successfully! Confirmation email has been sent.');
        return redirect()->to('/reservation');
    }

    public function myReservations()
    {
        $email = $this->request->getGet('email');
        
        if (empty($email)) {
            session()->setFlashdata('error', 'Please provide your email address.');
            return redirect()->to('/reservation');
        }

        $reservationModel = new Reservations_model();
        $equipmentModel = new Equipment_model();
        
        // Get all reservations for this email
        $reservations = $reservationModel
            ->where('email', $email)
            ->where('status', 'reserved')
            ->orderBy('reserve_date', 'DESC')
            ->findAll();

        // Attach equipment details to each reservation
        foreach ($reservations as &$reservation) {
            $equipment = $equipmentModel->find($reservation['equipment_id']);
            $reservation['equipment_name'] = $equipment ? $equipment['equipment_name'] : 'Unknown';
        }

        $data = [
            'title' => 'My Reservations - ITSO EMS',
            'reservations' => $reservations,
            'email' => $email
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('my_reservations_view', $data)
            . view('include/foot_view');
    }

    public function cancelReservation($id)
    {
        $reservationModel = new Reservations_model();
        $equipmentModel = new Equipment_model();
        
        $reservation = $reservationModel->find($id);
        
        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found.');
            return redirect()->back();
        }

        if ($reservation['status'] != 'reserved') {
            session()->setFlashdata('error', 'This reservation cannot be cancelled.');
            return redirect()->back();
        }

        // Update reservation status to cancelled
        $reservationModel->update($id, ['status' => 'cancelled']);

        // Make equipment available again
        $equipmentModel->update($reservation['equipment_id'], ['available' => 1]);

        // Send cancellation email
        $this->sendCancellationEmail($reservation);

        session()->setFlashdata('success', 'Reservation cancelled successfully.');
        return redirect()->back();
    }

    public function rescheduleReservation($id)
    {
        $reservationModel = new Reservations_model();
        $equipmentModel = new Equipment_model();
        
        $reservation = $reservationModel->find($id);
        
        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found.');
            return redirect()->back();
        }

        if ($reservation['status'] != 'reserved') {
            session()->setFlashdata('error', 'This reservation cannot be rescheduled.');
            return redirect()->back();
        }

        $newDate = $this->request->getPost('new_date');
        
        if (empty($newDate)) {
            session()->setFlashdata('error', 'Please provide a new date.');
            return redirect()->back();
        }

        // Validate that new reservation is at least one day in advance
        $newDateTime = strtotime($newDate);
        $tomorrowDateTime = strtotime('+1 day', strtotime(date('Y-m-d')));
        
        if ($newDateTime < $tomorrowDateTime) {
            session()->setFlashdata('error', 'Reservations must be made at least one day in advance.');
            return redirect()->back();
        }

        // Check if equipment is available on new date
        $existing = $reservationModel
            ->where('equipment_id', $reservation['equipment_id'])
            ->where('reserve_date', $newDate)
            ->where('status', 'reserved')
            ->where('id !=', $id)
            ->first();

        if ($existing) {
            session()->setFlashdata('error', 'This equipment is already reserved for the selected date.');
            return redirect()->back();
        }

        // Update reservation date
        $reservationModel->update($id, ['reserve_date' => $newDate]);

        // Send reschedule email
        $equipment = $equipmentModel->find($reservation['equipment_id']);
        $this->sendRescheduleEmail($reservation, $newDate, $equipment['equipment_name']);

        session()->setFlashdata('success', 'Reservation rescheduled successfully.');
        return redirect()->back();
    }

    private function sendReservationEmail($associateName, $email, $equipment_name, $reserve_date, $notes, $equipment_id, $reservationId)
    {
        // Format the reservation date
        $formattedDate = date('F d, Y', strtotime($reserve_date));

        // Prepare email message
        $message = "<h2>Hello, " . esc($associateName) . "!</h2><br>"
            . "<p>Your equipment reservation has been confirmed.</p>"
            . "<div style='background-color:#f8f9fa;padding:20px;border-radius:8px;margin:20px 0;'>"
            . "<h3 style='margin-top:0;color:#007bff;'>Reservation Details</h3>"
            . "<p><strong>Reservation ID:</strong> #" . esc($reservationId) . "</p>"
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
            . "<p>If you need to cancel or modify your reservation, please visit the My Reservations page or contact the ITSO office.</p>"
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

    private function sendCancellationEmail($reservation)
    {
        $equipmentModel = new Equipment_model();
        $equipment = $equipmentModel->find($reservation['equipment_id']);
        $formattedDate = date('F d, Y', strtotime($reservation['reserve_date']));

        $message = "<h2>Reservation Cancelled</h2><br>"
            . "<p>Your equipment reservation has been cancelled.</p>"
            . "<div style='background-color:#f8f9fa;padding:20px;border-radius:8px;margin:20px 0;'>"
            . "<h3 style='margin-top:0;color:#dc3545;'>Cancellation Details</h3>"
            . "<p><strong>Reservation ID:</strong> #" . esc($reservation['id']) . "</p>"
            . "<p><strong>Equipment:</strong> " . esc($equipment['equipment_name']) . "</p>"
            . "<p><strong>Original Date:</strong> " . $formattedDate . "</p>"
            . "</div>"
            . "<p>If this was a mistake, you may create a new reservation.</p>"
            . "<br><p>Best regards,<br>ITSO EMS Team</p>";

        $emailService = service('email');
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($reservation['email']);
        $emailService->setSubject('ITSO EMS - Reservation Cancelled');
        $emailService->setMessage($message);
        $emailService->send();
    }

    private function sendRescheduleEmail($reservation, $newDate, $equipmentName)
    {
        $oldFormattedDate = date('F d, Y', strtotime($reservation['reserve_date']));
        $newFormattedDate = date('F d, Y', strtotime($newDate));

        $message = "<h2>Reservation Rescheduled</h2><br>"
            . "<p>Your equipment reservation has been rescheduled.</p>"
            . "<div style='background-color:#f8f9fa;padding:20px;border-radius:8px;margin:20px 0;'>"
            . "<h3 style='margin-top:0;color:#28a745;'>Updated Reservation Details</h3>"
            . "<p><strong>Reservation ID:</strong> #" . esc($reservation['id']) . "</p>"
            . "<p><strong>Equipment:</strong> " . esc($equipmentName) . "</p>"
            . "<p><strong>Previous Date:</strong> " . $oldFormattedDate . "</p>"
            . "<p><strong>New Date:</strong> " . $newFormattedDate . "</p>"
            . "</div>"
            . "<p>Please make sure to pick up the equipment on the new reserved date.</p>"
            . "<br><p>Best regards,<br>ITSO EMS Team</p>";

        $emailService = service('email');
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($reservation['email']);
        $emailService->setSubject('ITSO EMS - Reservation Rescheduled');
        $emailService->setMessage($message);
        $emailService->send();
    }
}