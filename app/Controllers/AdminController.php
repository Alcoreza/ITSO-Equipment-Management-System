<?php

namespace App\Controllers;

use App\Models\Users_model;
use App\Models\Equipment_model;

class AdminController extends BaseController
{
    // ===============================
    // USERS MANAGEMENT (Merged & Updated)
    // ===============================
    public function users()
    {
        $usersModel = new Users_model();

        // Pagination settings
        $perPage = 6; // users per page

        // Fetch paginated users (oldest first so new accounts appear at the back)
        $users = $usersModel->orderBy('id', 'ASC')->paginate($perPage);
        $pager = $usersModel->pager;

        $data = [
            'title' => 'User Management - ITSO EMS',
            'bodyClass' => 'users-page',
            'users' => $users,
            'pager' => $pager,
            'perPage' => $perPage
        ];

        return view('include/head_view', $data)
            . view('include/nav_view', $data)
            . view('users_view', $data)
            . view('include/foot_view', $data);
    }

    // ===============================
    // Update User
    // ===============================
    public function updateUser()
    {
        $usersModel = new Users_model();

        // Get and validate id
        $id = $this->request->getPost('id');
        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'Invalid user id.');
        }

        // Collect updatable fields
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
        ];

        // Only include role when it exists
        $role = $this->request->getPost('role');
        $role = is_string($role) ? trim($role) : '';
        if ($role !== '') {
            $data['role'] = $role;
        }

        // Password handling
        $password = $this->request->getPost('password');
        $confirm  = $this->request->getPost('password_confirm');
        $password = is_string($password) ? trim($password) : '';

        if ($password !== '') {
            if ($password !== $confirm) {
                return redirect()->back()->with('error', 'Passwords do not match.')->withInput();
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Perform update
        try {
            $updated = $usersModel->update((int)$id, $data);

            $dbError = [];
            if (isset($usersModel->db)) {
                $dbError = $usersModel->db->error();
            }

            if (!empty($dbError) && !empty($dbError['code'])) {
                log_message('error', 'User update DB error: ' . $dbError['message'] . ' (code: ' . $dbError['code'] . ')');
                return redirect()->back()->with('error', 'Database error: ' . $dbError['message'])->withInput();
            }

            if ($updated === false) {
                return redirect()->back()->with('error', 'Failed to update user.')->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }

        return redirect()->to('/users')->with('success', 'User updated successfully.');
    }

    // ===============================
    // View User Details
    // ===============================
    public function getUser($id)
    {
        $usersModel = new Users_model();
        $user = $usersModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        // Convert status (1/0)
        $user['status_text'] = ($user['status'] == 1) ? 'Active' : 'Inactive';

        return $this->response->setJSON($user);
    }

    // ===============================
    // Toggle User Status
    // ===============================
    public function toggleUser()
    {
        $usersModel = new Users_model();

        $id = $this->request->getPost('id');
        $action = $this->request->getPost('action');

        if (empty($id) || !is_numeric($id)) {
            return redirect()->back()->with('error', 'Invalid user id.');
        }

        $user = $usersModel->find((int)$id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Determine status
        $newStatus = null;
        if ($action === 'deactivate') $newStatus = 0;
        elseif ($action === 'activate') $newStatus = 1;
        else $newStatus = ($user['status'] == 1) ? 0 : 1;

        try {
            $updated = $usersModel->update((int)$id, ['status' => $newStatus]);

            $dbError = [];
            if (isset($usersModel->db)) {
                $dbError = $usersModel->db->error();
            }

            if (!empty($dbError) && !empty($dbError['code'])) {
                log_message('error', 'Toggle user DB error: ' . $dbError['message']);
                if ($this->request->isAJAX()) {
                    return $this->response->setStatusCode(500)->setJSON(['error' => $dbError['message']]);
                }
                return redirect()->back()->with('error', 'Database error: ' . $dbError['message']);
            }

            if ($updated === false) {
                if ($this->request->isAJAX()) {
                    return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update user status.']);
                }
                return redirect()->back()->with('error', 'Failed to update user status.');
            }
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Error updating status: ' . $e->getMessage());
        }

        $msg = $newStatus == 1 ? 'User activated.' : 'User deactivated.';

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'status' => $newStatus, 'message' => $msg]);
        }

        return redirect()->to('/users')->with('success', $msg);
    }

    // ===============================
    // EQUIPMENT MANAGEMENT (Merged)
    // ===============================
    public function equipment()
    {
        $equipmentModel = new Equipment_model();

        // Group equipment by name, type & status
        $equipment = $equipmentModel
            ->select('equipment_name, equipment_type, status, COUNT(*) as total_qty, SUM(available) as available_qty')
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
}
