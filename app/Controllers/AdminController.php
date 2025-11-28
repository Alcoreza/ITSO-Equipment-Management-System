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
    $request = service('request');

    // Get filter parameters from URL/query string
    $filterRole = $request->getGet('role');
    $filterStatus = $request->getGet('status');

    // Pagination settings
    $perPage = 6;

    // Start building query
    $builder = $usersModel->orderBy('id', 'ASC');

    // Apply role filter if provided
    if ($filterRole && in_array($filterRole, ['itso', 'associate', 'student'])) {
        $builder->where('role', $filterRole);
    }

    // Apply status filter if provided
    if ($filterStatus !== null && $filterStatus !== '') {
        $statusValue = ($filterStatus === 'active') ? 1 : 0;
        $builder->where('status', $statusValue);
    }

    // Paginate filtered results
    $users = $builder->paginate($perPage);
    $pager = $usersModel->pager;

    $data = [
        'title' => 'User Management - ITSO EMS',
        'bodyClass' => 'users-page',
        'users' => $users,
        'pager' => $pager,
        'perPage' => $perPage,
        'filterRole' => $filterRole ?? '',
        'filterStatus' => $filterStatus ?? ''
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

    // ===============================
// Add New User (Admin creates account)
// ===============================
public function addUser()
{
    $request = service('request');
    $users = new Users_model();
    $session = session();

    // Get form inputs
    $fullname = trim($request->getPost('fullname'));
    $email = trim($request->getPost('email'));
    $role = $request->getPost('role');
    $password = $request->getPost('password');
    $confirm = $request->getPost('confirm_password');

    // Basic validation
    if (empty($fullname) || empty($email) || empty($role) || empty($password)) {
        $session->setFlashdata('error', 'All fields are required.');
        return redirect()->back()->withInput();
    }

    if ($password !== $confirm) {
        $session->setFlashdata('error', 'Passwords do not match.');
        return redirect()->back()->withInput();
    }

    if (strlen($password) < 8) {
        $session->setFlashdata('error', 'Password must be at least 8 characters.');
        return redirect()->back()->withInput();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $session->setFlashdata('error', 'Invalid email format.');
        return redirect()->back()->withInput();
    }

    // Split full name into first and last name
    $nameParts = explode(" ", $fullname, 2);
    $first_name = $nameParts[0] ?? '';
    $last_name = $nameParts[1] ?? '';

    // Check for duplicate email
    $existing = $users->where('email', $email)->first();
    if ($existing) {
        $session->setFlashdata('error', 'That email is already in use.');
        return redirect()->back()->withInput();
    }

    // Generate verification token
    try {
        $token = bin2hex(random_bytes(16));
    } catch (\Exception $e) {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
    }

    // Prepare insert data
    $data_insert = [
        'username'    => $email,
        'password'    => password_hash($password, PASSWORD_DEFAULT),
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'email'       => $email,
        'role'        => $role,
        'token'       => $token,
        'is_verified' => 0,
        'status'      => 1
    ];

    // Prepare verification email
    $verificationLink = base_url('auth/verify/' . $token);
    $message = "<h2>Hello, " . esc($fullname) . "!</h2><br>"
        . "<p>An account has been created for you in ITSO EMS.</p>"
        . "<p>Please verify your email by clicking the link below:</p>"
        . "<p><a href='" . $verificationLink . "' style='display:inline-block;padding:10px 20px;background-color:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>Verify Email</a></p>"
        . "<p>Or copy this link: " . $verificationLink . "</p>"
        . "<p>If you did not request this account, please ignore this message.</p>"
        . "<br><p>Best regards,<br>ITSO EMS Team</p>";

    // Send email
    $emailService = service('email');
    $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
    $fromName = env('SITE_NAME', 'ITSO EMS');
    $emailService->setFrom($fromEmail, $fromName);
    $emailService->setTo($email);
    $emailService->setSubject('ITSO EMS - Verify Your Email Address');
    $emailService->setMessage($message);

    if (!$emailService->send()) {
        log_message('error', 'Verification email failed to send to ' . $email);
        $session->setFlashdata('error', 'Failed to send verification email. Please contact support.');
        return redirect()->back()->withInput();
    }

    // Insert user into database
    try {
        $inserted = $users->insert($data_insert);

        if ($inserted === false) {
            $dbError = $users->errors() ?? [];
            log_message('error', 'User insert failed: ' . print_r($dbError, true));
            
            $session->setFlashdata('error', 'Failed to create account. Please try again.');
            return redirect()->back()->withInput();
        }

        $userId = is_numeric($inserted) ? $inserted : $users->getInsertID();
        log_message('info', 'User created by admin: ID=' . $userId . ', Email=' . $email);

        $session->setFlashdata('success', 'User created successfully! Verification email has been sent.');
        return redirect()->to(base_url('users'));

    } catch (\Exception $e) {
        log_message('error', 'Add user exception: ' . $e->getMessage());
        $session->setFlashdata('error', 'An error occurred while creating the account. Please try again.');
        return redirect()->back()->withInput();
    }
}
}
