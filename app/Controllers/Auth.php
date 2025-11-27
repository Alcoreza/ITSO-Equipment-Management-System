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

        $usersModel = new \App\Models\Users_model();

        // check email exists
        $user = $usersModel->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Email not found.');
            return redirect()->back()->withInput();
        }

        // validate password
        if (!password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // Check if email is verified
        if ($user['is_verified'] != 1) {
            session()->setFlashdata('error', 'Please verify your email before logging in. Check your inbox.');
            return redirect()->back()->withInput();
        }

        // Check role (only ITSO can access admin panel)
        if ($user['role'] !== 'itso') {
            session()->setFlashdata('error', 'Access denied. Only ITSO users can log in to admin panel.');
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
            'bodyClass' => 'auth-page'
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

        // Prepare insert data - MATCH YOUR DATABASE FIELDS
        $data_insert = [
            'username'    => $email,
            'password'    => password_hash($password, PASSWORD_DEFAULT),
            'first_name'  => $first_name,
            'last_name'   => $last_name,
            'email'       => $email,
            'role'        => $role,
            'token'       => $token,
            'is_verified' => 0,
            'status'      => 1  // Active by default
        ];

        // Prepare verification email
        $verificationLink = base_url('auth/verify/' . $token);
        $message = "<h2>Hello, " . esc($fullname) . "!</h2><br>"
            . "<p>Thank you for registering with ITSO EMS.</p>"
            . "<p>Please verify your email by clicking the link below:</p>"
            . "<p><a href='" . $verificationLink . "' style='display:inline-block;padding:10px 20px;background-color:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>Verify Email</a></p>"
            . "<p>Or copy this link: " . $verificationLink . "</p>"
            . "<p>If you did not register, please ignore this message.</p>"
            . "<br><p>Best regards,<br>ITSO EMS Team</p>";

        // Send email BEFORE inserting to database
        $emailService = service('email');

        // Set from address if configured
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        $emailService->setFrom($fromEmail, $fromName);

        $emailService->setTo($email);
        $emailService->setSubject('ITSO EMS - Verify Your Email Address');
        $emailService->setMessage($message);

        // Try to send email
        if (!$emailService->send()) {
            log_message('error', 'Verification email failed to send to ' . $email);
            log_message('debug', $emailService->printDebugger(['headers']));
            
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

            // Log successful insertion
            $userId = is_numeric($inserted) ? $inserted : $users->getInsertID();
            log_message('info', 'User registered successfully: ID=' . $userId . ', Email=' . $email);

            $session->setFlashdata('success', 'Account created successfully! Please check your email to verify your account.');
            return redirect()->to(base_url('login'));

        } catch (\Exception $e) {
            log_message('error', 'Register exception: ' . $e->getMessage());
            $session->setFlashdata('error', 'An error occurred while creating your account. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    public function verify($token)
    {
        $usermodel = model('Users_model');
        
        // Find user by token
        $user = $usermodel->where('token', $token)->first();

        if ($user) {
            // Check if already verified
            if ($user['is_verified'] == 1) {
                session()->setFlashdata('info', 'Your account is already verified. You can log in now.');
                return redirect()->to(base_url('login'));
            }
            
            // Mark as verified and clear token
            $updated = $usermodel->update($user['id'], [
                'is_verified' => 1
            ]);

            if ($updated) {
                log_message('info', 'User verified successfully: ID=' . $user['id'] . ', Email=' . $user['email']);
                session()->setFlashdata('success', 'Your account has been successfully verified! You can now log in.');
            } else {
                log_message('error', 'Failed to update verification status for user ID=' . $user['id']);
                session()->setFlashdata('error', 'Verification failed. Please try again or contact support.');
            }

            return redirect()->to(base_url('login'));
            
        } else {
            // Invalid or expired token
            log_message('warning', 'Invalid verification token attempted: ' . $token);
            session()->setFlashdata('error', 'Invalid or expired verification token. Please register again.');
            return redirect()->to(base_url('register'));
        }
    }
}