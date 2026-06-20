<?php
// Set Excel Download Headers
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan_" . $start_date . "_to_" . $end_date . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .title {
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        .subtitle {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
            color: #555;
        }
        .table {
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 11px;
            width: 100%;
        }
        .table th {
            background-color: #1E120C;
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #000000;
            padding: 6px;
        }
        .table td {
            border: 1px solid #000000;
            padding: 6px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .bg-light {
            background-color: #f9f9f9;
        }
        .header-row {
            background-color: #e6e6e6;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Report Title -->
    <table>
        <tr>
            <td colspan="5" class="title">LAPORAN PENJUALAN - POS MANG KOJEK</td>
        </tr>
        <tr>
            <td colspan="5" class="subtitle">Periode: <?= date('d/m/Y', strtotime($start_date)) ?> s/d <?= date('d/m/Y', strtotime($end_date)) ?></td>
        </tr>
        <tr>
            <td colspan="5" class="subtitle">Dicetak Oleh: <?= htmlspecialchars($_SESSION['nama']) ?> | Waktu: <?= date('d/m/Y H:i') ?></td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
    </table>

    <!-- Statistics Table -->
    <table class="table">
        <thead>
            <tr class="header-row">
                <th colspan="2">RINGKASAN STATISTIK UTAMA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Pendapatan</td>
                <td class="bold">Rp <?= number_format($total_income, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Keuntungan Bersih</td>
                <td class="bold">Rp <?= number_format($total_profit, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Total Transaksi</td>
                <td class="bold"><?= number_format($total_transactions, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Rata-rata Transaksi</td>
                <td class="bold">Rp <?= number_format($average_transaction, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr><td colspan="5"></td></tr>
    </table>

    <!-- Section 1: Product Sales Summary -->
    <table class="table">
        <thead>
            <tr style="background-color: #BF7234; color: #ffffff;">
                <th colspan="5" class="bold">1. RINGKASAN PENJUALAN PRODUK</th>
            </tr>
            <tr class="header-row">
                <th style="width: 50px;">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th style="width: 100px;">Qty Terjual</th>
                <th style="width: 150px;">Total Omzet</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_p = 1;
            $sum_qty = 0;
            if (empty($product_sales)): 
            ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data produk terjual</td>
                </tr>
            <?php 
            else: 
                foreach ($product_sales as $p): 
                    $sum_qty += $p['total_qty'];
            ?>
                    <tr>
                        <td class="text-center"><?= $no_p++ ?></td>
                        <td class="bold"><?= htmlspecialchars($p['nama_produk'] ?: 'Produk Dihapus') ?></td>
                        <td><?= htmlspecialchars($p['kategori'] ?: '-') ?></td>
                        <td class="text-center"><?= $p['total_qty'] ?></td>
                        <td class="text-right bold">Rp <?= number_format($p['total_sales'], 0, ',', '.') ?></td>
                    </tr>
            <?php 
                endforeach;
            ?>
                <tr class="header-row">
                    <td colspan="3" class="text-right">TOTAL TERJUAL:</td>
                    <td class="text-center"><?= $sum_qty ?></td>
                    <td class="text-right">Rp <?= number_format($total_income, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table>
        <tr><td colspan="5"></td></tr>
    </table>

    <!-- Section 2: Detailed Transaction History -->
    <table class="table">
        <thead>
            <tr style="background-color: #BF7234; color: #ffffff;">
                <th colspan="5" class="bold">2. RIWAYAT TRANSAKSI DETAIL</th>
            </tr>
            <tr class="header-row">
                <th style="width: 50px;">No</th>
                <th>Invoice</th>
                <th>Tanggal & Waktu</th>
                <th>Kasir</th>
                <th style="width: 150px;">Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_t = 1;
            if (empty($transactions_list)): 
            ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data transaksi</td>
                </tr>
            <?php 
            else: 
                foreach ($transactions_list as $t): 
            ?>
                    <tr>
                        <td class="text-center"><?= $no_t++ ?></td>
                        <td class="bold"><?= htmlspecialchars($t['invoice']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($t['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($t['kasir_name'] ?: 'Kasir') ?></td>
                        <td class="text-right bold">Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                    </tr>
            <?php 
                endforeach;
            ?>
                <tr class="header-row">
                    <td colspan="4" class="text-right">TOTAL PENDAPATAN:</td>
                    <td class="text-right">Rp <?= number_format($total_income, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
