<?php

class Kasir extends Controller {
    private $productModel;
    private $transactionModel;

    public function __construct() {
        $this->checkAuth();
        $this->productModel = $this->model('Product_model');
        $this->transactionModel = $this->model('Transaction_model');
    }

    public function transaksi() {
        $successTrans = null;
        if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $successTrans = $this->transactionModel->getTransactionById($id);
        }

        $data['successTrans'] = $successTrans;
        $data['products'] = $this->productModel->getProductsOrderedByName();

        $this->view('templates/header');
        $this->view('templates/sidebar');
        $this->view('kasir/transaksi', $data);
        $this->view('templates/footer');
    }

    public function simpanTransaksi() {
        if (empty($_POST['produk_id'])) {
            echo "
            <script>
            alert('Keranjang masih kosong');
            window.location='" . BASEURL . "/kasir/transaksi';
            </script>
            ";
            exit;
        }

        $invoice = "INV-" . date('YmdHis');
        $user_id = intval($_SESSION['id']);
        $total = floatval($_POST['total']);
        $bayar = floatval($_POST['bayar']);
        $kembalian = $bayar - $total;

        // Construct products data array for model to insert using PDO transactions
        $productsData = [];
        foreach ($_POST['produk_id'] as $key => $prod_id) {
            $productsData[] = [
                'id' => intval($prod_id),
                'qty' => intval($_POST['qty'][$key]),
                'harga' => floatval($_POST['harga'][$key])
            ];
        }

        $transData = [
            'invoice' => $invoice,
            'user_id' => $user_id,
            'total' => $total,
            'bayar' => $bayar,
            'kembalian' => $kembalian,
            'products' => $productsData
        ];

        $transaction_id = $this->transactionModel->saveTransaction($transData);

        if ($transaction_id) {
            $this->redirect('kasir/transaksi?success=1&id=' . $transaction_id);
        } else {
            echo "
            <script>
            alert('Gagal memproses transaksi.');
            window.location='" . BASEURL . "/kasir/transaksi';
            </script>
            ";
            exit;
        }
    }

    public function cetakStruk($id) {
        $id_clean = intval($id);
        $trans = $this->transactionModel->getTransactionById($id_clean);

        if (!$trans) {
            die("Transaksi tidak ditemukan.");
        }

        $data['trans'] = $trans;
        $data['details'] = $this->transactionModel->getTransactionDetails($id_clean);

        $this->view('kasir/cetak_struk', $data);
    }
}
