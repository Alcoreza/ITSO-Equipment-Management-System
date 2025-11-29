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
        
        // Get the expected return date from borrowed_items
        $expected_return_date = isset($borrow['return_date']) ? $borrow['return_date'] : null;
        
        // Try to get actual borrow date from database if created_at exists
        // Since borrowed_items doesn't have created_at, we'll use NULL
        $borrow_date = null;

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

        // Send confirmation email
        $this->sendReturnEmail($borrowerName, $email, $equipment_name, $return_date, $equipment_id, $borrow_date, $expected_return_date);

        // Log the return
        log_message('info', "Return submitted: $borrowerName, $email, $equipment_name, $return_date");

        // Redirect back with success message (Flashdata)
        session()->setFlashdata('success', 'Equipment return recorded successfully! Confirmation email has been sent.');
        return redirect()->to('/return');
    }

    private function sendReturnEmail($borrowerName, $email, $equipment_name, $return_date, $equipment_id, $borrow_date = null, $expected_return_date = null)
    {
        // Format dates
        $formattedReturnDate = date('F d, Y', strtotime($return_date));
        $formattedExpectedReturnDate = $expected_return_date ? date('F d, Y', strtotime($expected_return_date)) : 'Not specified';

        // Calculate if return is late
        $isLate = false;
        $daysLate = 0;
        if ($expected_return_date && strtotime($return_date) > strtotime($expected_return_date)) {
            $isLate = true;
            $daysLate = floor((strtotime($return_date) - strtotime($expected_return_date)) / 86400);
        }

        // Prepare email message
        $message = "<h2>Hello, " . esc($borrowerName) . "!</h2><br>"
            . "<p>This email confirms that you have successfully returned equipment to ITSO EMS.</p>"
            . "<div style='background-color:#f8f9fa;padding:20px;border-radius:8px;margin:20px 0;'>"
            . "<h3 style='margin-top:0;color:#28a745;'>Return Details</h3>"
            . "<p><strong>Equipment:</strong> " . esc($equipment_name) . "</p>"
            . "<p><strong>Equipment ID:</strong> #" . esc($equipment_id) . "</p>"
            . "<p><strong>Returned By:</strong> " . esc($borrowerName) . "</p>"
            . "<p><strong>Email:</strong> " . esc($email) . "</p>"
            . "<p><strong>Expected Return Date:</strong> " . $formattedExpectedReturnDate . "</p>"
            . "<p><strong>Actual Return Date:</strong> " . $formattedReturnDate . "</p>";

        // Add late return warning if applicable
        if ($isLate) {
            $message .= "<p style='color:#dc3545;'><strong>⚠️ Return Status:</strong> Late by " . $daysLate . " day(s)</p>";
        } else {
            $message .= "<p style='color:#28a745;'><strong>✓ Return Status:</strong> On Time</p>";
        }

        $message .= "</div>";

        // Add late return notice if applicable
        if ($isLate) {
            $message .= "<div style='background-color:#f8d7da;padding:15px;border-left:4px solid #dc3545;margin:20px 0;'>"
                . "<p style='margin:0;color:#721c24;'><strong>⚠️ Late Return Notice:</strong></p>"
                . "<p style='margin-top:10px;color:#721c24;'>This equipment was returned " . $daysLate . " day(s) late. "
                . "Please ensure future equipment is returned on time to maintain your borrowing privileges.</p>"
                . "</div>";
        } else {
            $message .= "<div style='background-color:#d4edda;padding:15px;border-left:4px solid #28a745;margin:20px 0;'>"
                . "<p style='margin:0;color:#155724;'><strong>✓ Thank You!</strong></p>"
                . "<p style='margin-top:10px;color:#155724;'>The equipment was returned on time. "
                . "We appreciate your responsibility in using ITSO equipment.</p>"
                . "</div>";
        }

        $message .= "<p>Thank you for using ITSO EMS. The equipment has been checked in and is now available for other users.</p>"
            . "<p>If you have any questions or need to report any issues with the equipment, please contact the ITSO office.</p>"
            . "<br><p>Best regards,<br>ITSO EMS Team</p>";

        // Send email
        $emailService = service('email');
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($email);
        $emailService->setSubject('ITSO EMS - Equipment Return Confirmation');
        $emailService->setMessage($message);

        if (!$emailService->send()) {
            log_message('error', 'Return confirmation email failed to send to ' . $email);
        } else {
            log_message('info', 'Return confirmation email sent to ' . $email . ' for equipment: ' . $equipment_name . ' (Late: ' . ($isLate ? 'Yes' : 'No') . ')');
        }
    }
}