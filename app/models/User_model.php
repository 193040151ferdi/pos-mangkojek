<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getUserByUsername($username) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllUsers() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY role ASC, id DESC");
        return $this->db->resultSet();
    }

    public function checkUsernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $this->db->query("SELECT id FROM " . $this->table . " WHERE username = :username AND id != :id");
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->query("SELECT id FROM " . $this->table . " WHERE username = :username");
        }
        $this->db->bind(':username', $username);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function insertUser($data) {
        $query = "INSERT INTO " . $this->table . " (nama, username, password, role) VALUES (:nama, :username, :password, :role)";
        $this->db->query($query);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);
        return $this->db->execute();
    }

    public function updateUser($data) {
        if (!empty($data['password'])) {
            $query = "UPDATE " . $this->table . " SET nama = :nama, username = :username, password = :password, role = :role WHERE id = :id";
            $this->db->query($query);
            $this->db->bind(':password', $data['password']);
        } else {
            $query = "UPDATE " . $this->table . " SET nama = :nama, username = :username, role = :role WHERE id = :id";
            $this->db->query($query);
        }
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':role', $data['role']);
        return $this->db->execute();
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
