<?php

namespace App\Controllers;

use App\Models\Equipment_model;
use App\Models\Borrowed_model;

class ReportsController extends BaseController
{
    public function index()
    {
        $equipmentModel = new Equipment_model();
        $borrowedModel = new Borrowed_model();

        // Get current page from query string, default to 1
        $currentPage = $this->request->getGet('page') ?? 1;
        $currentPage = (int)$currentPage;

        // Initialize data arrays
        $activeEquipment = [];
        $inactiveEquipment = [];
        $recentBorrows = [];

        // Page 1: Active Equipment (available items with active status)
        if ($currentPage === 1) {
            $activeEquipment = $equipmentModel
                ->select('equipment_name, equipment_type, COUNT(*) as total_qty, SUM(available) as available_qty')
                ->where('status', 'active')
                ->groupBy(['equipment_name', 'equipment_type'])
                ->having('available_qty >', 0)
                ->findAll();
        }
        // Page 2: Unusable Equipment (inactive status)
        elseif ($currentPage === 2) {
            $inactiveEquipment = $equipmentModel
                ->select('equipment_name, equipment_type, status, COUNT(*) as total_qty')
                ->where('status', 'inactive')
                ->groupBy(['equipment_name', 'equipment_type', 'status'])
                ->findAll();
        }
        // Page 3: Recent Borrowing History
        elseif ($currentPage === 3) {
            $recentBorrows = $borrowedModel
                ->select('borrowed_items.id,
                          borrowed_items.borrower_id, 
                          borrowed_items.equipment_id,
                          borrowed_items.return_date,
                          borrowed_items.status,
                          equipment.equipment_name, 
                          equipment.equipment_type,
                          users.first_name, 
                          users.last_name')
                ->join('equipment', 'borrowed_items.equipment_id = equipment.equipment_id', 'left')
                ->join('users', 'borrowed_items.borrower_id = users.id', 'left')
                ->orderBy('borrowed_items.id', 'DESC')
                ->limit(10)
                ->findAll();
        }

        $data = [
            'title' => 'Reports - ITSO EMS',
            'activeEquipment' => $activeEquipment,
            'inactiveEquipment' => $inactiveEquipment,
            'recentBorrows' => $recentBorrows,
            'currentPage' => $currentPage,
            'totalPages' => 3
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('reports_view', $data)
            . view('include/foot_view');
    }
}