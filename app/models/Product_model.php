<?php

class Product_model {
    private $table = 'products';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProducts($search = '') {
        if ($search !== '') {
            $this->db->query("SELECT * FROM " . $this->table . " WHERE nama_produk LIKE :search OR kategori LIKE :search ORDER BY id DESC");
            $this->db->bind(':search', '%' . $search . '%');
        } else {
            $this->db->query("SELECT * FROM " . $this->table . " ORDER BY id DESC");
        }
        return $this->db->resultSet();
    }

    public function getProductsOrderedByName() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY nama_produk ASC");
        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function insertProduct($data) {
        $query = "INSERT INTO " . $this->table . " (nama_produk, harga, modal, stok, kategori, gambar) VALUES (:nama_produk, :harga, :modal, :stok, :kategori, :gambar)";
        $this->db->query($query);
        $this->db->bind(':nama_produk', $data['nama_produk']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':modal', $data['modal']);
        $this->db->bind(':stok', $data['stok']);
        $this->db->bind(':kategori', $data['kategori']);
        $this->db->bind(':gambar', $data['gambar']);
        return $this->db->execute();
    }

    public function updateProduct($data) {
        $query = "UPDATE " . $this->table . " SET nama_produk = :nama_produk, harga = :harga, modal = :modal, stok = :stok, kategori = :kategori, gambar = :gambar WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nama_produk', $data['nama_produk']);
        $this->db->bind(':harga', $data['harga']);
        $this->db->bind(':modal', $data['modal']);
        $this->db->bind(':stok', $data['stok']);
        $this->db->bind(':kategori', $data['kategori']);
        $this->db->bind(':gambar', $data['gambar']);
        return $this->db->execute();
    }

    public function deleteProduct($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
