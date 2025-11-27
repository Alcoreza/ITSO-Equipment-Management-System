<?php
namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        $data = [
            'title' => 'Login - ITSO EMS',
            'bodyClass' => 'auth-page'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('login_view', $data)
            . view('include/foot_view');
    }

    public function attempt()
    {
        $request = service('request');
        $email = trim($request->getPost('email'));
        $password = $request->getPost('password');

        $itsoModel = new \App\Models\Users_model();

        // check email exists
        $user = $itsoModel->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Email not found.');
            return redirect()->back()->withInput();
        }

        // validate password
        if (!password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // 🔥 CHECK ROLE HERE
        if ($user['role'] !== 'itso') {
            session()->setFlashdata('error', 'Access denied. Only ITSO users can log in.');
            return redirect()->back()->withInput();
        }

        // success → store session
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'role'       => $user['role']
        ]);

        session()->setFlashdata('success', 'Welcome back!');

        return redirect()->to(base_url('users'));
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }

    public function forgot()
    {
        $data = [
            'title' => 'Forgot Password - ITSO EMS',
            'bodyClass' => 'auth-page'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('forgot_view', $data)
            . view('include/foot_view');
    }

    public function sendReset()
    {
        session()->setFlashdata('success', 'If that email exists, we sent password reset instructions. (Demo)');
        return redirect()->to(base_url('password/forgot'));
    }

    public function reset($token = null)
    {
        $data = [
            'title' => 'Reset Password - ITSO EMS',
            'token' => $token,
            'bodyClass' => 'auth-page'
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('reset_view', $data)
            . view('include/foot_view');
    }

    public function updatePassword()
    {
        session()->setFlashdata('success', 'Password updated (demo). Please login.');
        return redirect()->to(base_url('login'));
    }

    public function register()
    {
        $data = [
            'title' => 'Register - ITSO EMS',
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('register_view', $data)
            . view('include/foot_view');
    }

    public function submitRegister()
{
    $request = service('request');
    $users = new \App\Models\Users_model();

    // Get form inputs
    $fullname = trim($request->getPost('fullname'));
    $email = trim($request->getPost('email'));
    $role = $request->getPost('role');
    $password = $request->getPost('password');
    $confirm = $request->getPost('confirm_password');

    // Basic validation
    if ($password !== $confirm) {
        session()->setFlashdata('error', 'Passwords do not match.');
        return redirect()->back()->withInput();
    }

    if (strlen($password) < 8) {
        session()->setFlashdata('error', 'Password must be at least 8 characters.');
        return redirect()->back()->withInput();
    }

    // Split full name into parts
    $parts = explode(" ", $fullname);
    $first_name = $parts[0] ?? '';
    $last_name = $parts[count($parts) - 1] ?? '';

    // Prepare data to match your DB columns
    $data = [
        'username'   => $email, 
        'password'   => password_hash($password, PASSWORD_DEFAULT),
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $email,
        'role'       => $role,
        'token'      => bin2hex(random_bytes(16)),  // Generate verification token
        'is_verified' => 0  // New user is not verified by default
    ];

    // Check for duplicate email
    $existing = $users->where('email', $email)->first();
    if ($existing) {
        session()->setFlashdata('error', 'That email is already in use.');
        return redirect()->back()->withInput();
    }

    // Insert into database
    try {
        $inserted = $users->insert($data);
        if ($inserted === false) {
            // Check DB error
            $dbError = [];
            if (isset($users->db)) {
                $dbError = $users->db->error();
            }
            $msg = 'Error saving data.';
            if (!empty($dbError) && !empty($dbError['message'])) {
                log_message('error', 'Register DB error: ' . $dbError['message']);
                $msg = 'Database error: ' . $dbError['message'];
            }
            session()->setFlashdata('error', $msg);
            return redirect()->back()->withInput();
        }

        // Send email with verification link
        $verificationLink = base_url('auth/verify/' . $data['token']);
        $message = "<h2>Welcome to ITSO EMS!</h2><br>"
            . "<p>Please verify your email by clicking the link below:</p>"
            . "<p><a href='" . $verificationLink . "'>Verify your email</a></p>";

        // Email setup
        $emailService = service('email');
        $emailService->setTo($email);
        $emailService->setSubject('User Account Verification');
        $emailService->setMessage($message);

        // Send the email
        if (!$emailService->send()) {
            session()->setFlashdata('error', 'There was an issue sending the verification email.');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Account created successfully! Please check your email to verify your account.');
        return redirect()->to(base_url('/'));
    } catch (\Exception $e) {
        log_message('error', 'Register exception: ' . $e->getMessage());
        session()->setFlashdata('error', 'An error occurred while creating account.');
        return redirect()->back()->withInput();
    }
}

public function verify($token)
{
    $usersModel = new \App\Models\Users_model();

    // Find user by token
    $user = $usersModel->where('token', $token)->first();

    // If user is found and token is valid
    if ($user) {
        // Mark the user as verified and clear the token
        $usersModel->update($user['id'], ['is_verified' => 1, 'token' => null]);

        // Set flashdata to notify the user
        session()->setFlashdata('success', 'Your account has been successfully verified!');

        // No redirection to login, just load the verification page
        return view('verify_view'); // Load the verification view
    } else { 
        // If the token is invalid or expired
        session()->setFlashdata('error', 'Invalid or expired token.');

        // No redirection to login, just load the verification page with error
        return view('verify_view'); // Load the verification view
    }
}


}