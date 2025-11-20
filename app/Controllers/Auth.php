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
        // FRONTEND ONLY placeholder - backend team will implement authentication logic.
        session()->setFlashdata('error', 'Demo mode: authentication not implemented.');
        return redirect()->to(base_url('login'));
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
        // front-end only demo
        session()->setFlashdata('success', 'Demo: Registration submitted. (Backend not implemented)');
        return redirect()->to(base_url('register'));
    }
}
