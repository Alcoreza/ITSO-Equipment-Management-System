<?php

namespace App\Controllers;
use App\Models\Equipment_model;

class Equipment extends BaseController
{
    protected $equipmentModel;

    public function __construct()
    {
        $this->equipmentModel = new Equipment_model();
    }

    public function index()
    {
        // Get all equipment records
        $data['equipment'] = $this->equipmentModel
            ->select('equipment_id, equipment_name, equipment_type, available, status, image, description')
            ->orderBy('equipment_name', 'ASC')
            ->findAll();

        return view('equipment_view', $data);
    }

    // Fetch a single equipment item by ID for AJAX (View/Edit)
    public function get($id)
    {
        $equipment = $this->equipmentModel->find($id);

        if ($equipment) {
            return $this->response->setJSON($equipment);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Equipment not found']);
        }
    }

    // Save changes from Edit Modal (AJAX)
    public function update($id)
    {
        // Accept JSON or form post
        $input = $this->request->getJSON(true);
        if (empty($input)) {
            $input = $this->request->getPost();
        }

        if (!$id) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Missing ID']);
        }

        // Map incoming fields to DB columns
        $data = [];
        if (isset($input['equipment_name'])) $data['equipment_name'] = $input['equipment_name'];
        if (isset($input['equipment_type'])) $data['equipment_type'] = $input['equipment_type'];
        if (isset($input['status'])) $data['status'] = $input['status'];
        if (isset($input['available'])) $data['available'] = (int)$input['available'];
        if (isset($input['description'])) $data['description'] = $input['description'];
        // Image handling is not implemented here; file upload would require special handling.

        if (empty($data)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'No fields to update']);
        }

        $updated = $this->equipmentModel->update($id, $data);

        if ($updated) {
            $equipment = $this->equipmentModel->find($id);
            return $this->response->setJSON(['success' => true, 'equipment' => $equipment]);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Update failed']);
        }
    }

    // Toggle active/inactive status (AJAX)
    public function toggle($id)
    {
        $equipment = $this->equipmentModel->find($id);

        if (!$equipment) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'error' => 'Equipment not found']);
        }

        $newStatus = ($equipment['status'] === 'active') ? 'inactive' : 'active';

        $updated = $this->equipmentModel->update($id, ['status' => $newStatus]);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'status' => $newStatus]);
        }

        return $this->response->setStatusCode(500)->setJSON(['success' => false, 'error' => 'Toggle failed']);
    }
}