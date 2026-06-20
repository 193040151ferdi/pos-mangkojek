<?php

class Transaction_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Save transaction with database transaction (Atomic)
    public function saveTransaction($data) {
        try {
            $this->db->beginTransaction();

            // 1. Insert into transactions table
            $queryTrans = "INSERT INTO transactions (invoice, user_id, total, bayar, kembalian) VALUES (:invoice, :user_id, :total, :bayar, :kembalian)";
            $this->db->query($queryTrans);
            $this->db->bind(':invoice', $data['invoice']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':total', $data['total']);
            $this->db->bind(':bayar', $data['bayar']);
            $this->db->bind(':kembalian', $data['kembalian']);
            $this->db->execute();

            $transaction_id = $this->db->lastInsertId();

            // 2. Loop detail products and insert, and update stock
            foreach ($data['products'] as $item) {
                // Fetch current cost/modal price of product
                $this->db->query("SELECT modal FROM products WHERE id = :id");
                $this->db->bind(':id', $item['id']);
                $product = $this->db->single();
                $modal = (float)($product['modal'] ?? 0);

                // Insert into details
                $queryDetail = "INSERT INTO transaction_details (transaction_id, product_id, qty, harga, modal, subtotal) VALUES (:transaction_id, :product_id, :qty, :harga, :modal, :subtotal)";
                $this->db->query($queryDetail);
                $this->db->bind(':transaction_id', $transaction_id);
                $this->db->bind(':product_id', $item['id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':harga', $item['harga']);
                $this->db->bind(':modal', $modal);
                $this->db->bind(':subtotal', $item['qty'] * $item['harga']);
                $this->db->execute();

                // Update stock
                $queryStock = "UPDATE products SET stok = stok - :qty WHERE id = :id";
                $this->db->query($queryStock);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':id', $item['id']);
                $this->db->execute();
            }

            $this->db->commit();
            return $transaction_id;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getTransactionById($id) {
        $this->db->query("
            SELECT t.*, u.nama AS kasir_name 
            FROM transactions t
            LEFT JOIN users u ON t.user_id = u.id
            WHERE t.id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getTransactionDetails($id) {
        $this->db->query("
            SELECT td.*, p.nama_produk 
            FROM transaction_details td
            LEFT JOIN products p ON td.product_id = p.id
            WHERE td.transaction_id = :id
        ");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    // Dashboard statistics methods
    public function getTodayIncome() {
        $this->db->query("SELECT SUM(total) AS total FROM transactions WHERE DATE(tanggal) = CURDATE()");
        $row = $this->db->single();
        return $row['total'] ?? 0;
    }

    public function getTodayTransactionsCount() {
        $this->db->query("SELECT COUNT(*) AS total FROM transactions WHERE DATE(tanggal) = CURDATE()");
        $row = $this->db->single();
        return $row['total'] ?? 0;
    }

    public function getTotalProductsCount() {
        $this->db->query("SELECT COUNT(*) AS total FROM products");
        $row = $this->db->single();
        return $row['total'] ?? 0;
    }

    public function getLowStockCount() {
        $this->db->query("SELECT COUNT(*) AS total FROM products WHERE stok <= 10");
        $row = $this->db->single();
        return $row['total'] ?? 0;
    }

    public function getSalesLast7Days() {
        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dayName = date('D', strtotime("-$i days"));
            $indDays = ['Mon'=>'Sen', 'Tue'=>'Sel', 'Wed'=>'Rab', 'Thu'=>'Kam', 'Fri'=>'Jum', 'Sat'=>'Sab', 'Sun'=>'Min'];
            $displayDay = $indDays[$dayName] ?? $dayName;
            
            $this->db->query("SELECT SUM(total) AS total FROM transactions WHERE DATE(tanggal) = :date");
            $this->db->bind(':date', $date);
            $r = $this->db->single();
            $total = $r['total'] ?? 0;
            
            $chartLabels[] = $displayDay . " (" . date('d/m', strtotime($date)) . ")";
            $chartValues[] = (float)$total;
        }
        return [
            'labels' => $chartLabels,
            'values' => $chartValues
        ];
    }

    // Report statistics methods
    public function getIncomeInRange($start, $end) {
        $this->db->query("SELECT SUM(total) AS total FROM transactions WHERE DATE(tanggal) BETWEEN :start AND :end");
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        $row = $this->db->single();
        return (float)($row['total'] ?? 0);
    }

    public function getProfitInRange($start, $end) {
        $this->db->query("
            SELECT SUM(td.subtotal - (td.qty * td.modal)) AS profit 
            FROM transaction_details td 
            LEFT JOIN transactions t ON td.transaction_id = t.id 
            WHERE DATE(t.tanggal) BETWEEN :start AND :end
        ");
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        $row = $this->db->single();
        return (float)($row['profit'] ?? 0);
    }

    public function getTransactionsCountInRange($start, $end) {
        $this->db->query("SELECT COUNT(*) AS total FROM transactions WHERE DATE(tanggal) BETWEEN :start AND :end");
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        $row = $this->db->single();
        return (int)($row['total'] ?? 0);
    }

    public function getProductSalesInRange($start, $end) {
        $this->db->query("
            SELECT p.nama_produk, p.kategori, SUM(td.qty) AS total_qty, SUM(td.subtotal) AS total_sales
            FROM transaction_details td
            LEFT JOIN products p ON td.product_id = p.id
            LEFT JOIN transactions t ON td.transaction_id = t.id
            WHERE DATE(t.tanggal) BETWEEN :start AND :end
            GROUP BY td.product_id
            ORDER BY total_sales DESC
        ");
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        return $this->db->resultSet();
    }

    public function getTransactionsListInRange($start, $end) {
        $this->db->query("
            SELECT t.*, u.nama AS kasir_name 
            FROM transactions t
            LEFT JOIN users u ON t.user_id = u.id
            WHERE DATE(t.tanggal) BETWEEN :start AND :end
            ORDER BY t.tanggal DESC
        ");
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        return $this->db->resultSet();
    }

    // Pre-fetch items details for all transactions in range to display in the modal
    public function getDetailsByTransactionIds($start, $end) {
        $this->db->query("
            SELECT td.*, p.nama_produk 
            FROM transaction_details td
            LEFT JOIN products p ON td.product_id = p.id
            WHERE td.transaction_id IN (
                SELECT id FROM transactions WHERE DATE(tanggal) BETWEEN :start AND :end
            )
        ");
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        $details = $this->db->resultSet();

        $items_by_transaction = [];
        foreach ($details as $d) {
            $items_by_transaction[$d['transaction_id']][] = [
                'nama_produk' => $d['nama_produk'] ?: 'Produk Dihapus',
                'qty' => (int)$d['qty'],
                'harga' => (float)$d['harga'],
                'subtotal' => (float)$d['subtotal']
            ];
        }
        return $items_by_transaction;
    }
}
