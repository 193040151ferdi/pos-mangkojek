<?php

class Controller {
    public function view($view, $data = []) {
        extract($data);
        require_once 'app/views/' . $view . '.php';
    }

    public function model($model) {
        require_once 'app/models/' . $model . '.php';
        return new $model;
    }

    public function redirect($url) {
        header('Location: ' . BASEURL . '/' . $url);
        exit;
    }

    // Helper to check user auth role
    public function checkAuth($rolesAllowed = []) {
        if (!isset($_SESSION['id'])) {
            $this->redirect('auth/login');
        }
        if (!empty($rolesAllowed) && !in_array($_SESSION['role'], $rolesAllowed)) {
            if ($_SESSION['role'] === 'admin') {
                $this->redirect('admin/dashboard');
            } else {
                $this->redirect('kasir/transaksi');
            }
        }
    }
}
