<?php

namespace App\Controllers;
use App\Models\Equipment_model;

class EquipmentController extends BaseController
{
    public function index()
    {
        $equipmentModel = new Equipment_model();

        // Group equipment by name, type & status - NOW INCLUDES IMAGE
        $equipment = $equipmentModel
            ->select('equipment_name, equipment_type, status, 
                      COUNT(*) as total_qty, 
                      SUM(available) as available_qty,
                      MAX(image) as image')
            ->groupBy(['equipment_name', 'equipment_type', 'status'])
            ->findAll();

        $data = [
            'title' => 'Equipment Management - ITSO EMS',
            'bodyClass' => 'equipment-page',
            'active' => 'equipment',
            'equipment' => $equipment,
        ];

        return view('include/head_view', $data)
            . view('include/nav_view', $data)
            . view('equipment_view', $data)
            . view('include/foot_view', $data);
    }

    // Fetch a single equipment item by ID
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

    // Get all equipment IDs for a specific group (name + type + status)
    public function getGroupItems()
    {
        $equipmentModel = new Equipment_model();
        
        $name = $this->request->getGet('name');
        $type = $this->request->getGet('type');
        $status = $this->request->getGet('status');

        if (empty($name) || empty($type) || empty($status)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing parameters']);
        }

        $items = $equipmentModel
            ->where('equipment_name', $name)
            ->where('equipment_type', $type)
            ->where('status', $status)
            ->findAll();

        return $this->response->setJSON(['items' => $items]);
    }

    // Add new equipment (batch creation)
    public function add()
    {
        $equipmentModel = new Equipment_model();
        $session = session();

        // Get form data
        $name = $this->request->getPost('equipment_name');
        $type = $this->request->getPost('equipment_type');
        $quantity = (int)$this->request->getPost('quantity');
        $description = $this->request->getPost('description');

        // Validation
        if (empty($name) || empty($type) || $quantity < 1) {
            $session->setFlashdata('error', 'Please fill all required fields with valid data.');
            return redirect()->back()->withInput();
        }

        // Handle image upload
        $imageName = null;
        $imageFile = $this->request->getFile('equipment_image');
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Get random name and move to public/img directory
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/img', $imageName);
            // Store ONLY the filename in database, not the path
        }

        // Insert multiple records based on quantity
        $insertData = [];
        for ($i = 0; $i < $quantity; $i++) {
            $insertData[] = [
                'equipment_name' => $name,
                'equipment_type' => $type,
                'available' => 1,
                'status' => 'active',
                'image' => $imageName,
                'description' => $description
            ];
        }

        try {
            $equipmentModel->insertBatch($insertData);
            $session->setFlashdata('success', "$quantity equipment item(s) added successfully.");
        } catch (\Exception $e) {
            log_message('error', 'Equipment add error: ' . $e->getMessage());
            $session->setFlashdata('error', 'Failed to add equipment. Please try again.');
        }

        return redirect()->to('/equipment');
    }

    // Update a single equipment item
    public function update($id)
    {
        $equipmentModel = new Equipment_model();
        $session = session();

        if (empty($id) || !is_numeric($id)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid equipment ID']);
        }

        // Check if equipment exists
        $existing = $equipmentModel->find($id);
        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Equipment not found']);
        }

        // Get update data
        $data = [
            'equipment_name' => $this->request->getPost('equipment_name'),
            'equipment_type' => $this->request->getPost('equipment_type'),
            'available' => (int)$this->request->getPost('available'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description')
        ];

        // Handle image upload if provided
        $imageFile = $this->request->getFile('equipment_image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Delete old image if exists
            if (!empty($existing['image'])) {
                $oldImagePath = ROOTPATH . 'public/img/' . $existing['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload new image - store ONLY the filename in database, not the path
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/img', $imageName);
            $data['image'] = $imageName;
        }

        try {
            $updated = $equipmentModel->update($id, $data);

            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'Equipment updated successfully']);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update equipment']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Equipment update error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'An error occurred while updating']);
        }
    }

    // Toggle equipment status (activate/deactivate)
    public function toggleStatus($id)
    {
        $equipmentModel = new Equipment_model();
        $session = session();

        if (empty($id) || !is_numeric($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid equipment ID']);
            }
            $session->setFlashdata('error', 'Invalid equipment ID.');
            return redirect()->back();
        }

        $equipment = $equipmentModel->find($id);
        if (!$equipment) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Equipment not found']);
            }
            $session->setFlashdata('error', 'Equipment not found.');
            return redirect()->back();
        }

        // Toggle status
        $newStatus = ($equipment['status'] === 'active') ? 'inactive' : 'active';

        try {
            $updated = $equipmentModel->update($id, ['status' => $newStatus]);

            if ($updated) {
                $message = "Equipment " . ($newStatus === 'active' ? 'activated' : 'deactivated') . " successfully.";
                
                // Always return JSON for AJAX requests
                return $this->response->setJSON(['success' => true, 'status' => $newStatus, 'message' => $message]);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update status']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Equipment toggle error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'An error occurred']);
        }
    }

    // Get aggregate view for a group
    public function getGroupView()
    {
        $equipmentModel = new Equipment_model();
        
        $name = $this->request->getGet('name');
        $type = $this->request->getGet('type');
        $status = $this->request->getGet('status');

        if (empty($name) || empty($type) || empty($status)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing parameters']);
        }

        $items = $equipmentModel
            ->where('equipment_name', $name)
            ->where('equipment_type', $type)
            ->where('status', $status)
            ->findAll();

        if (empty($items)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'No equipment found']);
        }

        // Calculate aggregate data
        $totalQty = count($items);
        $availableQty = 0;
        foreach ($items as $item) {
            if ($item['available'] == 1) {
                $availableQty++;
            }
        }

        // Get first item for image and description
        $firstItem = $items[0];

        $aggregate = [
            'equipment_name' => $name,
            'equipment_type' => $type,
            'status' => $status,
            'total_qty' => $totalQty,
            'available_qty' => $availableQty,
            'image' => $firstItem['image'] ?? null,
            'description' => $firstItem['description'] ?? 'No description available.'
        ];

        return $this->response->setJSON($aggregate);
    }
}