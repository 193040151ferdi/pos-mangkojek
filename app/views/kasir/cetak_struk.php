<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - <?= htmlspecialchars($trans['invoice']); ?></title>
    <!-- Google Font for fallback aesthetics on screen -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            width: 280px;
            margin-left: auto;
            margin-right: auto;
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
        .brand {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }
        .address {
            font-size: 10px;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .info {
            font-size: 11px;
            margin-bottom: 8px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .separator {
            border-bottom: 1px dashed #000;
            margin: 6px 0;
        }
        .double-separator {
            border-bottom: 3px double #000;
            margin: 8px 0;
        }
        .item-row {
            margin-bottom: 6px;
        }
        .item-name {
            word-break: break-word;
        }
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding-left: 8px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .grand-total {
            font-size: 12px;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 15px;
            font-size: 10px;
            line-height: 1.3;
        }
        
        /* Navigation/Control Bar visible only on screen */
        .no-print-bar {
            margin-bottom: 15px;
            padding: 8px;
            background: #FDFBF7;
            border: 1px solid #EFE6D8;
            border-radius: 8px;
            display: flex;
            gap: 10px;
            justify-content: center;
            width: 280px;
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
        }
        .btn-action {
            background: #BF7234;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-action:hover {
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
                width: 100%;
                padding: 0;
                margin: 0;
            }
            .no-print-bar {
                display: none;
            }
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Print / Close Controls for Screen View -->
    <div class="no-print-bar">
        <button class="btn-action" onclick="window.print()">Cetak</button>
        <button class="btn-action btn-secondary" onclick="window.close()">Tutup</button>
    </div>

    <!-- Receipt Contents -->
    <div class="text-center">
        <div class="brand">☕ POS Mang Kojek</div>
        <div class="address">
            Jl. Espresso Raya No. 45, Bandung<br>
            Telp: 0812-3456-7890
        </div>
    </div>

    <div class="separator"></div>

    <div class="info">
        <div class="info-row">
            <span>Invoice:</span>
            <span class="bold"><?= htmlspecialchars($trans['invoice']); ?></span>
        </div>
        <div class="info-row">
            <span>Tanggal:</span>
            <span><?= date('d/m/Y H:i', strtotime($trans['tanggal'])); ?></span>
        </div>
        <div class="info-row">
            <span>Kasir:</span>
            <span><?= htmlspecialchars($trans['kasir_name'] ?: 'Kasir'); ?></span>
        </div>
    </div>

    <div class="separator"></div>

    <!-- Purchased items -->
    <div class="items">
        <?php foreach ($details as $item): ?>
            <div class="item-row">
                <div class="item-name"><?= htmlspecialchars($item['nama_produk'] ?: 'Produk Dihapus'); ?></div>
                <div class="item-details">
                    <span><?= $item['qty']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?></span>
                    <span>Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="separator"></div>

    <!-- Totals -->
    <div class="totals">
        <div class="total-row grand-total">
            <span>TOTAL:</span>
            <span>Rp <?= number_format($trans['total'], 0, ',', '.'); ?></span>
        </div>
        <div class="total-row">
            <span>Bayar:</span>
            <span>Rp <?= number_format($trans['bayar'], 0, ',', '.'); ?></span>
        </div>
        <div class="total-row">
            <span>Kembalian:</span>
            <span>Rp <?= number_format($trans['kembalian'], 0, ',', '.'); ?></span>
        </div>
    </div>

    <div class="double-separator"></div>

    <div class="text-center footer-note">
        Terima kasih atas kunjungan Anda!<br>
        Selamat menikmati kopi dan makanan kami.<br>
        Powered by POS Mang Kojek
    </div>

    <script>
        // Trigger print dialog on page load
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
            
            setTimeout(() => {
                if (window.opener) {
                    // Close popup window optionally
                }
            }, 1000);
        });
    </script>
</body>
</html>
