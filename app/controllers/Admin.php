<?php

class Admin extends Controller {
    private $userModel;
    private $productModel;
    private $transactionModel;

    public function __construct() {
        $this->checkAuth(['admin']);
        $this->userModel = $this->model('User_model');
        $this->productModel = $this->model('Product_model');
        $this->transactionModel = $this->model('Transaction_model');
    }

    public function dashboard() {
        $data['todayIncome'] = $this->transactionModel->getTodayIncome();
        $data['todayTrans'] = $this->transactionModel->getTodayTransactionsCount();
        $data['totalProducts'] = $this->transactionModel->getTotalProductsCount();
        $data['lowStock'] = $this->transactionModel->getLowStockCount();

        $chartData = $this->transactionModel->getSalesLast7Days();
        $data['chartLabels'] = $chartData['labels'];
        $data['chartValues'] = $chartData['values'];

        $this->view('templates/header');
        $this->view('templates/sidebar');
        $this->view('admin/dashboard', $data);
        $this->view('templates/footer');
    }

    // --- PRODUCT MANAGEMENT ---
    public function produk() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data['search'] = $search;
        $data['products'] = $this->productModel->getAllProducts($search);

        $this->view('templates/header');
        $this->view('templates/sidebar');
        $this->view('admin/produk', $data);
        $this->view('templates/footer');
    }

    public function produkTambah() {
        if (isset($_POST['simpan'])) {
            $nama = trim($_POST['nama_produk']);
            $kategori = trim($_POST['kategori']);
            
            $dataInsert = [
                'nama_produk' => $nama,
                'harga' => floatval($_POST['harga']),
                'modal' => floatval($_POST['modal']),
                'stok' => intval($_POST['stok']),
                'kategori' => $kategori,
                'gambar' => get_product_image($nama, $kategori)
            ];

            $this->productModel->insertProduct($dataInsert);
            $this->redirect('admin/produk');
        }

        $this->view('templates/header');
        $this->view('templates/sidebar');
        $this->view('admin/produk_tambah');
        $this->view('templates/footer');
    }

    public function produkEdit($id) {
        $produk = $this->productModel->getProductById($id);
        if (!$produk) {
            $this->redirect('admin/produk');
        }

        if (isset($_POST['update'])) {
            $nama = trim($_POST['nama_produk']);
            $kategori = trim($_POST['kategori']);

            $dataUpdate = [
                'id' => $id,
                'nama_produk' => $nama,
                'harga' => floatval($_POST['harga']),
                'modal' => floatval($_POST['modal']),
                'stok' => intval($_POST['stok']),
                'kategori' => $kategori,
                'gambar' => get_product_image($nama, $kategori)
            ];

            $this->productModel->updateProduct($dataUpdate);
            $this->redirect('admin/produk');
        }

        $data['produk'] = $produk;
        $this->view('templates/header');
        $this->view('templates/sidebar');
        $this->view('admin/produk_edit', $data);
        $this->view('templates/footer');
    }

    public function produkHapus($id) {
        $this->productModel->deleteProduct($id);
        $this->redirect('admin/produk');
    }

    // --- USER MANAGEMENT ---
    public function pengguna() {
        $error_msg = "";
        $success_msg = "";

        // Handle CRUD Actions via POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
            $action = $_GET['action'];

            if ($action == 'tambah') {
                $nama = trim($_POST['nama']);
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                $role = trim($_POST['role']);

                if (empty($nama) || empty($username) || empty($password) || empty($role)) {
                    $error_msg = "Semua field harus diisi.";
                } else {
                    if ($this->userModel->checkUsernameExists($username)) {
                        $error_msg = "Username sudah digunakan oleh akun lain.";
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $dataInsert = [
                            'nama' => $nama,
                            'username' => $username,
                            'password' => $hashed_password,
                            'role' => $role
                        ];
                        if ($this->userModel->insertUser($dataInsert)) {
                            $success_msg = "Akun pengguna berhasil ditambahkan.";
                        } else {
                            $error_msg = "Gagal menambahkan akun.";
                        }
                    }
                }
            } elseif ($action == 'edit') {
                $id = intval($_POST['id']);
                $nama = trim($_POST['nama']);
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                $role = trim($_POST['role']);

                if (empty($nama) || empty($username) || empty($role)) {
                    $error_msg = "Nama, Username, dan Role harus diisi.";
                } else {
                    if ($this->userModel->checkUsernameExists($username, $id)) {
                        $error_msg = "Username sudah digunakan oleh akun lain.";
                    } else {
                        $dataUpdate = [
                            'id' => $id,
                            'nama' => $nama,
                            'username' => $username,
                            'role' => $role,
                            'password' => !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : ''
                        ];

                        if ($this->userModel->updateUser($dataUpdate)) {
                            if ($id == $_SESSION['id']) {
                                $_SESSION['nama'] = $nama;
                                $_SESSION['role'] = $role;
                            }
                            $success_msg = "Akun pengguna berhasil diubah.";
                        } else {
                            $error_msg = "Gagal mengubah akun.";
                        }
                    }
                }
            }
        }

        // Handle Delete via GET action
        if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($id == $_SESSION['id']) {
                $error_msg = "Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.";
            } else {
                if ($this->userModel->deleteUser($id)) {
                    $success_msg = "Akun pengguna berhasil dihapus.";
                } else {
                    $error_msg = "Gagal menghapus akun.";
                }
            }
        }

        $data['users'] = $this->userModel->getAllUsers();
        $data['error_msg'] = $error_msg;
        $data['success_msg'] = $success_msg;

        $this->view('templates/header');
        $this->view('templates/sidebar');
        $this->view('admin/pengguna', $data);
        $this->view('templates/footer');
    }

    // --- SALES REPORTS ---
    public function laporan() {
        $periode = isset($_GET['periode']) ? $_GET['periode'] : 'bulan';
        $tanggal = date('Y-m-d');
        $minggu = date('o-\WW');
        $bulan = date('Y-m');

        if ($periode == 'hari') {
            $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
            $start_date = $tanggal;
            $end_date = $tanggal;
        } elseif ($periode == 'minggu') {
            $minggu = isset($_GET['minggu']) ? $_GET['minggu'] : date('o-\WW');
            if (preg_match('/^(\d{4})-W(\d{2})$/', $minggu, $matches)) {
                $year = (int)$matches[1];
                $week = (int)$matches[2];
                $dto = new DateTime();
                $dto->setISODate($year, $week);
                $start_date = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $end_date = $dto->format('Y-m-d');
            } else {
                $dto = new DateTime();
                $dto->setISODate((int)$dto->format('o'), (int)$dto->format('W'));
                $start_date = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $end_date = $dto->format('Y-m-d');
                $minggu = date('o-\WW');
            }
        } else {
            $periode = 'bulan';
            $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
            if (preg_match('/^(\d{4})-(\d{2})$/', $bulan, $matches)) {
                $year = $matches[1];
                $month = $matches[2];
                $start_date = "$year-$month-01";
                $end_date = date('Y-m-t', strtotime($start_date));
            } else {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-d');
                $bulan = date('Y-m');
            }
        }

        // Stats
        $data['total_income'] = $this->transactionModel->getIncomeInRange($start_date, $end_date);
        $data['total_profit'] = $this->transactionModel->getProfitInRange($start_date, $end_date);
        $data['total_transactions'] = $this->transactionModel->getTransactionsCountInRange($start_date, $end_date);
        $data['average_transaction'] = $data['total_transactions'] > 0 ? ($data['total_income'] / $data['total_transactions']) : 0;

        // Chart configuration
        $labels = [];
        $chart_data = [];
        $chart_title = "Pendapatan Harian";

        if ($periode == 'hari') {
            $chart_title = "Pendapatan Per Jam";
            $hourly_sales = array_fill(0, 24, 0);

            // Fetch list in range
            $list = $this->transactionModel->getTransactionsListInRange($start_date, $end_date);
            foreach ($list as $row) {
                $hour = (int)date('H', strtotime($row['tanggal']));
                $hourly_sales[$hour] += (float)$row['total'];
            }

            foreach ($hourly_sales as $hour => $sales_val) {
                $labels[] = sprintf('%02d:00', $hour);
                $chart_data[] = $sales_val;
            }
        } else {
            if ($periode == 'minggu') {
                $chart_title = "Pendapatan Harian (Mingguan)";
            } else {
                $chart_title = "Pendapatan Harian (Bulanan)";
            }

            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $end = $end->modify('+1 day'); // inclusive

            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);

            $daily_sales = [];
            foreach ($daterange as $date) {
                $daily_sales[$date->format('Y-m-d')] = 0;
            }

            $list = $this->transactionModel->getTransactionsListInRange($start_date, $end_date);
            foreach ($list as $row) {
                $tgl = date('Y-m-d', strtotime($row['tanggal']));
                if (isset($daily_sales[$tgl])) {
                    $daily_sales[$tgl] += (float)$row['total'];
                }
            }

            $indDays = ['Mon'=>'Sen', 'Tue'=>'Sel', 'Wed'=>'Rab', 'Thu'=>'Kam', 'Fri'=>'Jum', 'Sat'=>'Sab', 'Sun'=>'Min'];
            foreach ($daily_sales as $date_str => $sales_val) {
                $date_obj = new DateTime($date_str);
                $dayName = $date_obj->format('D');
                $displayDay = $indDays[$dayName] ?? $dayName;
                
                $labels[] = $displayDay . " (" . $date_obj->format('d/m') . ")";
                $chart_data[] = $sales_val;
            }
        }

        $data['periode'] = $periode;
        $data['tanggal'] = $tanggal;
        $data['minggu'] = $minggu;
        $data['bulan'] = $bulan;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['chart_title'] = $chart_title;
        $data['labels'] = $labels;
        $data['chart_data'] = $chart_data;

        // Transaction tables
        $data['transactions_list'] = $this->transactionModel->getTransactionsListInRange($start_date, $end_date);
        $data['items_by_transaction'] = $this->transactionModel->getDetailsByTransactionIds($start_date, $end_date);

        $this->view('templates/header');
        $this->view('templates/sidebar');
        $this->view('admin/laporan', $data);
        $this->view('templates/footer');
    }

    public function cetakLaporan() {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['total_income'] = $this->transactionModel->getIncomeInRange($start_date, $end_date);
        $data['total_profit'] = $this->transactionModel->getProfitInRange($start_date, $end_date);
        $data['total_transactions'] = $this->transactionModel->getTransactionsCountInRange($start_date, $end_date);
        $data['average_transaction'] = $data['total_transactions'] > 0 ? ($data['total_income'] / $data['total_transactions']) : 0;
        
        $data['product_sales'] = $this->transactionModel->getProductSalesInRange($start_date, $end_date);
        $data['transactions_list'] = $this->transactionModel->getTransactionsListInRange($start_date, $end_date);

        $this->view('admin/cetak_laporan', $data);
    }

    public function exportExcel() {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['total_income'] = $this->transactionModel->getIncomeInRange($start_date, $end_date);
        $data['total_profit'] = $this->transactionModel->getProfitInRange($start_date, $end_date);
        $data['total_transactions'] = $this->transactionModel->getTransactionsCountInRange($start_date, $end_date);
        $data['average_transaction'] = $data['total_transactions'] > 0 ? ($data['total_income'] / $data['total_transactions']) : 0;
        
        $data['product_sales'] = $this->transactionModel->getProductSalesInRange($start_date, $end_date);
        $data['transactions_list'] = $this->transactionModel->getTransactionsListInRange($start_date, $end_date);

        $this->view('admin/export_excel', $data);
    }
}
