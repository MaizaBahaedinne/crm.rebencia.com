<?php namespace App\Controllers;

use App\Models\WordPressUserModel;

class AuthController extends BaseController
{
    public function login()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $model = new WordPressUserModel();
        $user = $model->verifyUser($username, $password);

        if ($user) {
            $session->set([
                'user_id' => $user['ID'],
                'username' => $user['user_login'],
                'isLoggedIn' => true
            ]);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
