<?php
namespace App\Controllers;

class Auth extends BaseController
{
    // ===============================
    // LOGIN PAGE
    // ===============================
    /**
     * Display the login form
     */
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

    // ===============================
    // LOGIN ATTEMPT (PROCESS LOGIN)
    // ===============================
    /**
     * Process login form submission
     * Validates email, password, email verification, and role
     */
    public function attempt()
    {
        $request = service('request');
        $email = trim($request->getPost('email'));
        $password = $request->getPost('password');

        $usersModel = new \App\Models\Users_model();

        // Check if email exists in database
        $user = $usersModel->where('email', $email)->first();

        if (!$user) {
            session()->setFlashdata('error', 'Email not found.');
            return redirect()->back()->withInput();
        }

        // Validate password against hashed password in database
        if (!password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // Check if user has verified their email
        if ($user['is_verified'] != 1) {
            session()->setFlashdata('error', 'Please verify your email before logging in. Check your inbox.');
            return redirect()->back()->withInput();
        }

        // Check if user role is ITSO (only ITSO can access admin panel)
        if ($user['role'] !== 'itso') {
            session()->setFlashdata('error', 'Access denied. Only ITSO users can log in to admin panel.');
            return redirect()->back()->withInput();
        }

        // Login successful - store user data in session
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'role'       => $user['role']
        ]);

        session()->setFlashdata('success', 'Welcome back!');

        // Redirect to users management page
        return redirect()->to(base_url('users'));
    }

    // ===============================
    // LOGOUT
    // ===============================
    /**
     * Destroy session and logout user
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }

    // ===============================
    // FORGOT PASSWORD PAGE
    // ===============================
    /**
     * Display forgot password form
     */
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

    // ===============================
    // SEND PASSWORD RESET EMAIL
    // ===============================
    /**
     * Process forgot password form
     * Generates reset token, sends email with reset link
     */
    public function sendReset()
    {
        $request = service('request');
        $email = trim($request->getPost('email'));
        $usersModel = new \App\Models\Users_model();
        $session = session();

        // Validate email input
        if (empty($email)) {
            $session->setFlashdata('error', 'Please enter your email address.');
            return redirect()->back()->withInput();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $session->setFlashdata('error', 'Please enter a valid email address.');
            return redirect()->back()->withInput();
        }

        // Check if user exists
        $user = $usersModel->where('email', $email)->first();

        // For security: always show success message even if email doesn't exist
        // This prevents email enumeration attacks
        if (!$user) {
            log_message('warning', 'Password reset requested for non-existent email: ' . $email);
            $session->setFlashdata('success', 'If that email exists in our system, we sent password reset instructions.');
            return redirect()->to(base_url('password/forgot'));
        }

        // Generate secure random token (64 characters)
        try {
            $token = bin2hex(random_bytes(32));
        } catch (\Exception $e) {
            $token = bin2hex(openssl_random_pseudo_bytes(32));
        }

        // Set token expiry to 1 hour from now
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save reset token and expiry to database
        $updated = $usersModel->update($user['id'], [
            'reset_token' => $token,
            'reset_token_expiry' => $expiry
        ]);

        if (!$updated) {
            log_message('error', 'Failed to save reset token for user: ' . $email);
            $session->setFlashdata('error', 'An error occurred. Please try again.');
            return redirect()->back()->withInput();
        }

        // Prepare password reset email
        $resetLink = base_url('password/reset/' . $token);
        $userName = $user['first_name'] . ' ' . $user['last_name'];
        
        $message = "<h2>Hello, " . esc($userName) . "!</h2><br>"
            . "<p>We received a request to reset your password for your ITSO EMS account.</p>"
            . "<p>Click the button below to reset your password:</p>"
            . "<p><a href='" . $resetLink . "' style='display:inline-block;padding:12px 24px;background-color:#007bff;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold;'>Reset Password</a></p>"
            . "<p>Or copy this link: " . $resetLink . "</p>"
            . "<p><strong>This link will expire in 1 hour.</strong></p>"
            . "<p>If you did not request a password reset, please ignore this email and your password will remain unchanged.</p>"
            . "<br><p>Best regards,<br>ITSO EMS Team</p>";

        // Configure and send email
        $emailService = service('email');
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        
        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($email);
        $emailService->setSubject('ITSO EMS - Password Reset Request');
        $emailService->setMessage($message);

        // Attempt to send email
        if (!$emailService->send()) {
            log_message('error', 'Password reset email failed to send to ' . $email);
            log_message('debug', $emailService->printDebugger(['headers']));
            
            // Still show success message for security
            $session->setFlashdata('success', 'If that email exists in our system, we sent password reset instructions.');
            return redirect()->to(base_url('password/forgot'));
        }

        // Log successful email send
        log_message('info', 'Password reset email sent to: ' . $email);
        $session->setFlashdata('success', 'If that email exists in our system, we sent password reset instructions. Please check your inbox.');
        
        return redirect()->to(base_url('password/forgot'));
    }

    // ===============================
    // RESET PASSWORD PAGE
    // ===============================
    /**
     * Display password reset form
     * Validates reset token before showing form
     */
    public function reset($token = null)
    {
        // Check if token is provided in URL
        if (!$token) {
            session()->setFlashdata('error', 'Invalid reset link.');
            return redirect()->to(base_url('password/forgot'));
        }

        $usersModel = new \App\Models\Users_model();
        
        // Find user by reset token
        $user = $usersModel->where('reset_token', $token)->first();

        // Check if token exists in database
        if (!$user) {
            session()->setFlashdata('error', 'Invalid or expired reset link. Please request a new one.');
            return redirect()->to(base_url('password/forgot'));
        }

        // Check if token has expired (compare with current time)
        $expiry = strtotime($user['reset_token_expiry']);
        $now = time();

        if ($now > $expiry) {
            session()->setFlashdata('error', 'This reset link has expired. Please request a new one.');
            return redirect()->to(base_url('password/forgot'));
        }

        // Token is valid - show reset password form
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

    // ===============================
    // UPDATE PASSWORD (PROCESS RESET)
    // ===============================
    /**
     * Process password reset form
     * Updates password and clears reset token
     */
    public function updatePassword()
    {
        $request = service('request');
        $usersModel = new \App\Models\Users_model();
        $session = session();

        // Get form data
        $token = $request->getPost('token');
        $newPassword = $request->getPost('new_password');
        $confirmPassword = $request->getPost('confirm_password');

        // Validate all fields are filled
        if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
            $session->setFlashdata('error', 'All fields are required.');
            return redirect()->back()->withInput();
        }

        // Check password length (minimum 8 characters)
        if (strlen($newPassword) < 8) {
            $session->setFlashdata('error', 'Password must be at least 8 characters.');
            return redirect()->back()->withInput();
        }

        // Check if passwords match
        if ($newPassword !== $confirmPassword) {
            $session->setFlashdata('error', 'Passwords do not match.');
            return redirect()->back()->withInput();
        }

        // Find user by reset token
        $user = $usersModel->where('reset_token', $token)->first();

        if (!$user) {
            $session->setFlashdata('error', 'Invalid or expired reset link.');
            return redirect()->to(base_url('password/forgot'));
        }

        // Check if token has expired
        $expiry = strtotime($user['reset_token_expiry']);
        if (time() > $expiry) {
            $session->setFlashdata('error', 'This reset link has expired. Please request a new one.');
            return redirect()->to(base_url('password/forgot'));
        }

        // Update password and clear reset token from database
        $updated = $usersModel->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expiry' => null
        ]);

        if (!$updated) {
            log_message('error', 'Failed to update password for user ID: ' . $user['id']);
            $session->setFlashdata('error', 'An error occurred while updating your password. Please try again.');
            return redirect()->back();
        }

        // Log successful password reset
        log_message('info', 'Password successfully reset for user: ' . $user['email']);
        $session->setFlashdata('success', 'Your password has been successfully reset! You can now log in with your new password.');
        
        return redirect()->to(base_url('login'));
    }

    // ===============================
    // REGISTER PAGE
    // ===============================
    /**
     * Display registration form
     */
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

    // ===============================
    // SUBMIT REGISTRATION
    // ===============================
    /**
     * Process registration form
     * Creates user account and sends verification email
     */
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

        // Validate all required fields are filled
        if (empty($fullname) || empty($email) || empty($role) || empty($password) || empty($confirm)) {
            $session->setFlashdata('error', 'All fields are required.');
            return redirect()->back()->withInput();
        }

        // Check if passwords match
        if ($password !== $confirm) {
            $session->setFlashdata('error', 'Passwords do not match.');
            return redirect()->back()->withInput();
        }

        // Check minimum password length
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

        // Generate verification token (32 bytes = 64 hex characters)
        try {
            $token = bin2hex(random_bytes(16));
        } catch (\Exception $e) {
            $token = bin2hex(openssl_random_pseudo_bytes(16));
        }

        // Prepare data for database insertion
        $data_insert = [
            'username'    => $email,
            'password'    => password_hash($password, PASSWORD_DEFAULT),
            'first_name'  => $first_name,
            'last_name'   => $last_name,
            'email'       => $email,
            'role'        => $role,
            'token'       => $token,
            'is_verified' => 0,  // Email not verified yet
            'status'      => 1,  // Active by default
            'is_deactivated' => 0
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

        // Configure email service
        $emailService = service('email');
        $fromEmail = env('SITE_EMAIL', 'noreply@itsoems.com');
        $fromName = env('SITE_NAME', 'ITSO EMS');
        $emailService->setFrom($fromEmail, $fromName);
        $emailService->setTo($email);
        $emailService->setSubject('ITSO EMS - Verify Your Email Address');
        $emailService->setMessage($message);

        // Try to send verification email BEFORE creating account
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

            // Log successful registration
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

    // ===============================
    // EMAIL VERIFICATION
    // ===============================
    /**
     * Verify user email using token from email link
     */
    public function verify($token)
    {
        $usermodel = model('Users_model');
        
        // Find user by verification token
        $user = $usermodel->where('token', $token)->first();

        if ($user) {
            // Check if already verified
            if ($user['is_verified'] == 1) {
                session()->setFlashdata('info', 'Your account is already verified. You can log in now.');
                return redirect()->to(base_url('login'));
            }
            
            // Mark user as verified
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