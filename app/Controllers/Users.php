<?php
namespace App\Controllers;

class Users extends BaseController
{
    public function index()
    {
        $usermodel = model('Users_model');

        $data = [
            'title' => 'Aling Basyang\'s Sisigan - User Directory',
            'users' => $usermodel->findAll()
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('userslist_view', $data)
            . view('include/foot_view');
    }

    public function add()
    {
        $data = [
            'title' => 'Aling Basyang\'s Sisigan - Add New User',
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('adduser_view')
            . view('include/foot_view');
    }

    public function insert()
    {
        $usermodel = model('Users_model');

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'suffix' => $this->request->getPost('suffix'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role') ?? 'staff',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $usermodel->insert($data);
        return redirect()->to('users');
    }

    public function view($id)
    {
        $usermodel = model('Users_model');

        $data = [
            'title' => 'Aling Basyang\'s Sisigan - View User Record',
            'user' => $usermodel->find($id)
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('viewuser_view', $data)
            . view('include/foot_view');
    }

    public function edit($id)
    {
        $usermodel = model('Users_model');

        $data = [
            'title' => 'Aling Basyang\'s Sisigan - Edit User Record',
            'user' => $usermodel->find($id)
        ];

        return view('include/head_view', $data)
            . view('include/nav_view')
            . view('updateuser_view', $data)
            . view('include/foot_view');
    }

    public function update($id)
    {
        $usermodel = model('Users_model');

        $data = [
            'username' => $this->request->getPost('username'),
            // Only update password if user entered one
            'password' => $this->request->getPost('password')
                ? password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
                : $usermodel->find($id)['password'],
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'suffix' => $this->request->getPost('suffix'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role') ?? 'staff',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $usermodel->update($id, $data);
        return redirect()->to('users');
    }

    public function delete($id)
    {
        $usermodel = model('Users_model');
        $usermodel->delete($id);
        return redirect()->to('users');
    }
}
?>