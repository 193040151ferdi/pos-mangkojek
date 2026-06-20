<?php

class Auth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User_model');
    }

    public function login() {
        // If already logged in, redirect based on role
        if (isset($_SESSION['id'])) {
            if ($_SESSION['role'] === 'admin') {
                $this->redirect('admin/dashboard');
            } else {
                $this->redirect('kasir/transaksi');
            }
        }

        $this->view('auth/login');
    }

    public function loginProcess() {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username dan password harus diisi.";
            $this->redirect('auth/login');
        }

        $user = $this->userModel->getUserByUsername($username);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    $this->redirect('admin/dashboard');
                } else {
                    $this->redirect('kasir/transaksi');
                }
            } else {
                $_SESSION['error'] = "Password yang Anda masukkan salah.";
                $this->redirect('auth/login');
            }
        } else {
            $_SESSION['error'] = "Username tidak ditemukan.";
            $this->redirect('auth/login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}
