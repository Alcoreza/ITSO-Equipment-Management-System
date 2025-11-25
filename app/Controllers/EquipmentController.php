<?php

namespace App\Controllers;
use App\Models\Equipment_model;

class Equipment extends BaseController
{
    public function index()
    {
        $equipmentModel = new Equipment_model();

        // Get all equipment records
        $data['equipment'] = $equipmentModel
            ->select('equipment_id, equipment_name, equipment_type, available, status, image, description')
            ->findAll();

        return view('equipment_view', $data);
    }

    // Optional: fetch a single equipment item by ID for AJAX (View/Edit)
    public function get($id)
    {
        $equipmentModel = new Equipment_model();
        $equipment = $equipmentModel->find($id);

        if ($equipment) {
            return $this->response->setJSON($equipment);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Equipment not found']);
        }
    }

    // Optional: Save changes from Edit Modal
    public function update($id)
    {
        $equipmentModel = new Equipment_model();
        $data = $this->request->getPost(); // item_name, equipment_type, status, available, description

        if ($equipmentModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }
}