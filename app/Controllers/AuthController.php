<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        helper(['form']);
        echo view('auth/login');
    }

    public function loginPost()
    {
        helper(['form']);
        $session = session();

        $rules = [
            'password' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', [
                'validation' => $this->validator
            ]);
        }

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Utiliser WordPressUserModel pour vérifier l'utilisateur
        $userModel = new \App\Models\WordPressUserModel();
        $user = $userModel->verifyUser($email, $password);

        if ($user) {
            $session->set([
            'email' => $user['user_email'],
            'isLoggedIn' => true,
            'user_id' => $user['ID'],
            ]);
            return redirect()->to('/dashboard');
        } else {
            $session->setFlashdata('error', 'Email ou mot de passe incorrect');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
