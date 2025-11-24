<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ReservationController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Reservation - ITSO EMS'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('reservation_view', $data)
            . view('include/foot_view');
    }

    public function submitReservation()
    {
        $name       = $this->request->getPost('associate_name');
        $email      = $this->request->getPost('email');
        $equipment  = $this->request->getPost('equipment');
        $date       = $this->request->getPost('reservation_date');
        $notes      = $this->request->getPost('notes');

        // In real use: Insert into database here.
        // Example response only:

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "Reservation submitted successfully!"
        ]);
    }
}
