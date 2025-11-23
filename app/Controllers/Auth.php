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
        session()->setFlashdata('info', 'Demo: logged out (no backend).');
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
        'role'       => $role
    ];

    // Insert into database
    if ($users->insert($data)) {
        session()->setFlashdata('success', 'Account created successfully!');

        // Redirect to index page
        return redirect()->to(base_url('/'));
    } else {
        session()->setFlashdata('error', 'Error saving data.');
        return redirect()->back()->withInput();
    }
}

}
