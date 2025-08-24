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
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', [
                'validation' => $this->validator
            ]);
        }

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Exemple simple, tu peux remplacer par wp_users
        if($email === 'admin@rebencia.com' && $password === 'Rebencia1402!!') {
            $session->set([
                'email' => $email,
                'isLoggedIn' => true,
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
