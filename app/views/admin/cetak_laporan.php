<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan (<?= date('d-m-Y', strtotime($start_date)) ?> s/d <?= date('d-m-Y', strtotime($end_date)) ?>)</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            color: #2B1B12;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 13px;
            line-height: 1.5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #EFE6D8;
            padding-bottom: 15px;
        }
        .brand {
            font-size: 24px;
            font-weight: 700;
            color: #1E120C;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: 600;
            color: #BF7234;
            margin: 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-period {
            font-size: 12px;
            color: #8C7C72;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #8C7C72;
            margin-top: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #FAF8F5;
            border: 1.5px solid #EFE6D8;
            border-radius: 10px;
            padding: 15px;
        }
        .stat-label {
            font-size: 11px;
            color: #8C7C72;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: #1E120C;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1E120C;
            border-left: 3px solid #BF7234;
            padding-left: 8px;
            margin: 25px 0 12px 0;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table th {
            background-color: #FAF8F5;
            border-bottom: 1.5px solid #EFE6D8;
            color: #8C7C72;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px 10px;
            text-align: left;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #EFE6D8;
            font-size: 12px;
            color: #2B1B12;
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .table-total-row {
            background-color: #FAF8F5;
            font-weight: bold;
        }
        .table-total-row td {
            border-top: 1.5px solid #EFE6D8;
            border-bottom: 1.5px solid #EFE6D8;
        }

        .toolbar {
            margin-bottom: 20px;
            padding: 10px;
            background: #FAF8F5;
            border: 1px solid #EFE6D8;
            border-radius: 12px;
            display: flex;
            gap: 12px;
            justify-content: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
        }
        .btn {
            background: #BF7234;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #D08345;
        }
        .btn-secondary {
            background: #EFE6D8;
            color: #2B1B12;
        }
        .btn-secondary:hover {
            background: #e2d5c1;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .toolbar {
                display: none;
            }
            .container {
                max-width: 100%;
            }
            @page {
                size: auto;
                margin: 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar for screen controls -->
    <div class="toolbar">
        <button class="btn" onclick="window.print()">Cetak Laporan</button>
        <button class="btn btn-secondary" onclick="window.close()">Tutup Halaman</button>
    </div>

    <div class="container text-start">
        <!-- Header -->
        <div class="header">
            <div class="text-center">
                <div class="brand">☕ POS MANG KOJEK</div>
                <div class="report-title">Laporan Penjualan Periodik</div>
                <div class="report-period">
                    Periode: <?= date('d/m/Y', strtotime($start_date)) ?> s/d <?= date('d/m/Y', strtotime($end_date)) ?>
                </div>
            </div>
            <div class="meta-info">
                <span>Dicetak oleh: <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></span>
                <span>Waktu Cetak: <strong><?= date('d/m/Y H:i') ?></strong></span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value">Rp <?= number_format($total_income, 0, ',', '.') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Keuntungan Bersih</div>
                <div class="stat-value">Rp <?= number_format($total_profit, 0, ',', '.') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value"><?= number_format($total_transactions, 0, ',', '.') ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value">Rp <?= number_format($average_transaction, 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- Section 1: Product Sales Summary -->
        <div class="section-title">Ringkasan Penjualan Produk</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-center" style="width: 15%">Qty Terjual</th>
                    <th class="text-right" style="width: 25%">Total Omzet</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no_p = 1;
                $sum_qty = 0;
                if (empty($product_sales)): 
                ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 15px;">Tidak ada data produk terjual</td>
                    </tr>
                <?php 
                else: 
                    foreach ($product_sales as $p): 
                        $sum_qty += $p['total_qty'];
                ?>
                        <tr>
                            <td><?= $no_p++ ?></td>
                            <td class="bold"><?= htmlspecialchars($p['nama_produk'] ?: 'Produk Dihapus') ?></td>
                            <td><?= htmlspecialchars($p['kategori'] ?: '-') ?></td>
                            <td class="text-center"><?= number_format($p['total_qty'], 0, ',', '.') ?></td>
                            <td class="text-right bold">Rp <?= number_format($p['total_sales'], 0, ',', '.') ?></td>
                        </tr>
                <?php 
                    endforeach;
                ?>
                    <tr class="table-total-row">
                        <td colspan="3" class="text-right">TOTAL TERJUAL:</td>
                        <td class="text-center"><?= number_format($sum_qty, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($total_income, 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Section 2: Detailed Transaction History -->
        <div class="section-title">Riwayat Transaksi Detail</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Invoice</th>
                    <th>Tanggal & Waktu</th>
                    <th>Kasir</th>
                    <th class="text-right" style="width: 25%">Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no_t = 1;
                if (empty($transactions_list)): 
                ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 15px;">Tidak ada data transaksi</td>
                    </tr>
                <?php 
                else: 
                    foreach ($transactions_list as $t): 
                ?>
                        <tr>
                            <td><?= $no_t++ ?></td>
                            <td class="bold" style="color: #BF7234;"><?= htmlspecialchars($t['invoice']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($t['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($t['kasir_name'] ?: 'Kasir') ?></td>
                            <td class="text-right bold">Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                        </tr>
                <?php 
                    endforeach;
                ?>
                    <tr class="table-total-row">
                        <td colspan="4" class="text-right">TOTAL PENDAPATAN:</td>
                        <td class="text-right">Rp <?= number_format($total_income, 0, ',', '.') ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Trigger print dialog on page load
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
        });
    </script>
</body>
</html>
