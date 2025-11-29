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
        $borrowId = $borrowModel->insert([
            'borrower_name'   => $borrowerName,
            'borrower_id'     => $borrower_id,
            'email'           => $email,
            'equipment_id'    => $equipment_id,
            'return_date'     => $return_date,
            'status'          => 'borrowed'
        ]);

        // Mark equipment as unavailable
        $equipmentModel->update($equipment_id, ['available' => 0]);

        // Send confirmation email
        $this->sendBorrowEmail($borrowerName, $email, $equipment_name, $return_date, $equipment_id);

        session()->setFlashdata('success', 'Equipment borrow recorded successfully! Confirmation email has been sent.');
        return redirect()->to('/borrow');
    }

    private function sendBorrowEmail($borrowerName, $email, $equipment_name, $return_date, $equipment_id)
    {
        // Format the return date
        $formattedReturnDate = !empty($return_date) ? date('F d, Y', strtotime($return_date)) : 'Not specified';
        $borrowDate = date('F d, Y');

        // Prepare email message
        $message = "<h2>Hello, " . esc($borrowerName) . "!</h2><br>"
            . "<p>This email confirms that you have borrowed equipment from ITSO EMS.</p>"
            . "<div style='background-color:#f8f9fa;padding:20px;border-radius:8px;margin:20px 0;'>"
            . "<h3 style='margin-top:0;color:#007bff;'>Borrow Details</h3>"
            . "<p><strong>Equipment:</strong> " . esc($equipment_name) . "</p>"
            . "<p><strong>Equipment ID:</strong> #" . esc($equipment_id) . "</p>"
            . "<p><strong>Borrowed By:</strong> " . esc($borrowerName) . "</p>"
            . "<p><strong>Email:</strong> " . esc($email) . "</p>"
            . "<p><strong>Borrow Date:</strong> " . $borrowDate . "</p>"
            . "<p><strong>Expected Return Date:</strong> " . $formattedReturnDate . "</p>"
            . "</div>"
            . "<div style='background-color:#fff3cd;padding:15px;border-left:4px solid #ffc107;margin:20px 0;'>"
            . "<p style='margin:0;'><strong>⚠️ Important Reminders:</strong></p>"
            . "<ul style='margin-top:10px;'>"
            . "<li>Please take good care of the equipment</li>"
            . "<li>Return the equipment on or before the expected return date</li>"
            . "<li>Report any damage or issues immediately to the ITSO office</li>"
            . "<li>Late returns may affect future borrowing privileges</li>"
            . "</ul>"
            . "</div>"
            . "<p>If you have any questions or concerns, please contact the ITSO office.</p>"
            . "<br><p>Best regards,<br>ITSO EMS Team</p>";

        // Send email
        $emailService = service('email');
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($email);
        $emailService->setSubject('ITSO EMS - Equipment Borrow Confirmation');
        $emailService->setMessage($message);

        if (!$emailService->send()) {
            log_message('error', 'Borrow confirmation email failed to send to ' . $email);
        } else {
            log_message('info', 'Borrow confirmation email sent to ' . $email . ' for equipment: ' . $equipment_name);
        }
    }
}