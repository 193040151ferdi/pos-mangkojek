<!-- Date Input Styling & Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--espresso-dark);">Laporan</h2>
        <p class="text-muted mb-0">Analisis penjualan dan performa</p>
    </div>
    
    <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Period Filter Form -->
        <form method="GET" action="<?= BASEURL; ?>/admin/laporan" class="d-flex align-items-center gap-2 bg-white p-2 border shadow-sm" style="border-radius: 12px; border-color: var(--border-color) !important; margin-bottom: 0;">
            <div class="d-flex align-items-center">
                <select name="periode" id="periodeSelect" class="form-select border-0 py-1" style="font-size: 0.9rem; font-family: var(--font-outfit); width: 130px; background: transparent; cursor: pointer; border-right: 1px solid var(--border-color) !important; border-radius: 0;" aria-label="Pilih Periode">
                    <option value="hari" <?= $periode == 'hari' ? 'selected' : '' ?>>Per Hari</option>
                    <option value="minggu" <?= $periode == 'minggu' ? 'selected' : '' ?>>Per Minggu</option>
                    <option value="bulan" <?= $periode == 'bulan' ? 'selected' : '' ?>>Per Bulan</option>
                </select>
            </div>
            
            <div id="inputHari" class="d-none align-items-center">
                <input type="date" name="tanggal" class="form-control border-0 py-1" value="<?= htmlspecialchars($tanggal) ?>" style="font-size: 0.9rem; font-family: var(--font-outfit); width: 150px; background: transparent; cursor: pointer;" aria-label="Pilih Hari">
            </div>
            
            <div id="inputMinggu" class="d-none align-items-center">
                <input type="week" name="minggu" class="form-control border-0 py-1" value="<?= htmlspecialchars($minggu) ?>" style="font-size: 0.9rem; font-family: var(--font-outfit); width: 175px; background: transparent; cursor: pointer;" aria-label="Pilih Minggu">
            </div>
            
            <div id="inputBulan" class="d-none align-items-center">
                <input type="month" name="bulan" class="form-control border-0 py-1" value="<?= htmlspecialchars($bulan) ?>" style="font-size: 0.9rem; font-family: var(--font-outfit); width: 155px; background: transparent; cursor: pointer;" aria-label="Pilih Bulan">
            </div>
            
            <button type="submit" class="btn btn-primary btn-sm px-3 py-2" style="border-radius: 8px;">
                <i class="bi bi-filter"></i>
            </button>
        </form>

        <!-- Export Buttons -->
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm px-3 py-2 d-flex align-items-center gap-1" onclick="exportReport('print')" style="border-radius: 8px; background-color: var(--coffee-caramel); border-color: var(--coffee-caramel);">
                <i class="bi bi-printer-fill"></i> Cetak / PDF
            </button>
            <button type="button" class="btn btn-success btn-sm px-3 py-2 d-flex align-items-center gap-1" onclick="exportReport('excel')" style="border-radius: 8px; background-color: #2e7d32; border-color: #2e7d32;">
                <i class="bi bi-file-earmark-excel-fill"></i> Ekspor Excel
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards Grid -->
<div class="row g-4 mb-4">
    <!-- Total Pendapatan -->
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-card-info">
                <span class="stat-card-label">Total Pendapatan</span>
                <span class="stat-card-value" style="font-size: 1.5rem;">Rp <?= number_format($total_income, 0, ',', '.') ?></span>
            </div>
            <div class="stat-card-icon revenue">
                <span class="fw-bold" style="font-size: 0.85rem; font-family: var(--font-outfit);">IDR</span>
            </div>
        </div>
    </div>

    <!-- Keuntungan Bersih -->
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-card-info">
                <span class="stat-card-label">Keuntungan Bersih</span>
                <span class="stat-card-value" style="font-size: 1.5rem;">Rp <?= number_format($total_profit, 0, ',', '.') ?></span>
            </div>
            <div class="stat-card-icon" style="background-color: #EBF7EE; color: #2E7D32; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">
                <span class="fw-bold" style="font-family: var(--font-outfit);">IDR</span>
            </div>
        </div>
    </div>
    
    <!-- Total Transaksi -->
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-card-info">
                <span class="stat-card-label">Total Transaksi</span>
                <span class="stat-card-value" style="font-size: 1.5rem;"><?= number_format($total_transactions, 0, ',', '.') ?></span>
            </div>
            <div class="stat-card-icon transactions" style="background-color: #FAF0E6; color: var(--coffee-caramel);">
                <i class="bi bi-cart"></i>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Pills -->
<div class="toggle-pills">
    <button type="button" class="toggle-pill-btn active" id="btnGrafik" onclick="switchView('grafik')">Grafik</button>
    <button type="button" class="toggle-pill-btn" id="btnTabel" onclick="switchView('tabel')">Tabel</button>
</div>

<!-- Graphic View Container -->
<div id="viewGrafik" class="card shadow mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4" style="color: var(--espresso-dark); font-size: 1.25rem;"><?= $chart_title ?></h4>
        <div style="height: 350px; position: relative;">
            <canvas id="dailyRevenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Table View Container -->
<div id="viewTabel" class="d-none">
    <div class="card shadow p-4 border-0">
        <h4 class="fw-bold mb-4" style="color: var(--espresso-dark); font-size: 1.25rem;">Riwayat Transaksi</h4>
        
        <?php if (empty($transactions_list)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox-fill display-4 mb-3" style="color: var(--border-color);"></i>
                <p class="mb-0">Tidak ada transaksi ditemukan pada rentang tanggal ini.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tanggal & Waktu</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Bayar</th>
                            <th>Kembalian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($transactions_list as $row): 
                            $items_json = json_encode($items_by_transaction[$row['id']] ?? []);
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-semibold" style="color: var(--coffee-caramel);"><?= htmlspecialchars($row['invoice']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($row['kasir_name'] ?: 'Tidak Diketahui') ?></td>
                                <td class="fw-bold text-dark">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($row['bayar'], 0, ',', '.') ?></td>
                                <td class="text-muted">Rp <?= number_format($row['kembalian'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-sm px-3 detail-trans-btn" 
                                             data-id="<?= $row['id'] ?>"
                                             data-invoice="<?= htmlspecialchars($row['invoice']) ?>"
                                             data-tanggal="<?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?>"
                                             data-kasir="<?= htmlspecialchars($row['kasir_name'] ?: 'Tidak Diketahui') ?>"
                                             data-total="Rp <?= number_format($row['total'], 0, ',', '.') ?>"
                                             data-bayar="Rp <?= number_format($row['bayar'], 0, ',', '.') ?>"
                                             data-kembalian="Rp <?= number_format($row['kembalian'], 0, ',', '.') ?>"
                                             data-items='<?= htmlspecialchars($items_json, ENT_QUOTES, 'UTF-8') ?>'
                                             style="border-radius: 8px;">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div class="modal fade" id="detailTransaksiModal" tabindex="-1" aria-labelledby="detailTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="detailTransaksiModalLabel" style="color: var(--espresso-dark);">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-start">
                <div class="row g-2 mb-3 pb-3 border-bottom" style="font-size: 0.85rem;">
                    <div class="col-6">
                        <span class="text-muted d-block">Invoice</span>
                        <strong class="text-dark fs-6" id="modal_invoice">-</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Tanggal / Waktu</span>
                        <strong class="text-dark" id="modal_tanggal">-</strong>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="text-muted me-2">Kasir:</span>
                        <strong class="text-dark" id="modal_kasir">-</strong>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-2" style="font-size: 0.9rem; color: var(--espresso-dark);">Produk Dibeli</h6>
                <div class="table-responsive mb-3" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-sm align-middle" style="font-size: 0.85rem;">
                        <thead>
                            <tr class="text-muted">
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modal_items_table_body">
                            <!-- Populated Dynamically -->
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-light p-3 rounded-3" style="font-size: 0.9rem;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total Belanja:</span>
                        <strong class="text-dark fs-6" id="modal_total">-</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Bayar:</span>
                        <span class="text-dark" id="modal_bayar">-</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                        <span class="fw-semibold text-muted">Kembalian:</span>
                        <strong class="text-success" id="modal_kembalian">-</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 d-flex gap-2">
                <button type="button" id="btnModalPrint" class="btn btn-success flex-grow-1" style="border-radius: 10px; padding: 10px; background-color: var(--coffee-caramel); border-color: var(--coffee-caramel);">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Struk
                </button>
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 10px; padding: 10px; background-color: #EFE6D8; border-color: #EFE6D8; color: var(--text-primary);">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function switchView(view) {
    const btnGrafik = document.getElementById('btnGrafik');
    const btnTabel = document.getElementById('btnTabel');
    const viewGrafik = document.getElementById('viewGrafik');
    const viewTabel = document.getElementById('viewTabel');

    if (view === 'grafik') {
        btnGrafik.classList.add('active');
        btnTabel.classList.remove('active');
        viewGrafik.classList.remove('d-none');
        viewTabel.classList.add('d-none');
    } else {
        btnTabel.classList.add('active');
        btnGrafik.classList.remove('active');
        viewTabel.classList.remove('d-none');
        viewGrafik.classList.add('d-none');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const periodeSelect = document.getElementById('periodeSelect');
    const containers = {
        hari: document.getElementById('inputHari'),
        minggu: document.getElementById('inputMinggu'),
        bulan: document.getElementById('inputBulan')
    };

    function updatePeriodInputs() {
        const selected = periodeSelect.value;
        Object.keys(containers).forEach(key => {
            const container = containers[key];
            if (key === selected) {
                container.classList.remove('d-none');
                container.classList.add('d-flex');
                container.querySelectorAll('input').forEach(input => input.disabled = false);
            } else {
                container.classList.add('d-none');
                container.classList.remove('d-flex');
                container.querySelectorAll('input').forEach(input => input.disabled = true);
            }
        });
    }

    if (periodeSelect) {
        periodeSelect.addEventListener('change', updatePeriodInputs);
        updatePeriodInputs();
    }

    // Chart
    const ctx = document.getElementById('dailyRevenueChart').getContext('2d');
    const chartLabels = <?= json_encode($labels); ?>;
    const chartData = <?= json_encode($chart_data); ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartData,
                borderColor: '#BF7234',
                backgroundColor: 'rgba(191, 114, 52, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#BF7234',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' Pendapatan: Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: {
                        borderDash: [5, 5],
                        color: '#EFE6D8'
                    },
                    ticks: {
                        color: '#8C7C72',
                        font: {
                            family: 'Outfit'
                        },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return (value / 1000) + 'k';
                            }
                            return value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#8C7C72',
                        font: {
                            family: 'Outfit'
                        }
                    }
                }
            }
        }
    });

    // Details Modal
    const detailButtons = document.querySelectorAll(".detail-trans-btn");
    const detailModal = new bootstrap.Modal(document.getElementById('detailTransaksiModal'));
    
    detailButtons.forEach(button => {
        button.addEventListener("click", function() {
            const transId = this.getAttribute("data-id");
            const invoice = this.getAttribute("data-invoice");
            const tanggal = this.getAttribute("data-tanggal");
            const kasir = this.getAttribute("data-kasir");
            const total = this.getAttribute("data-total");
            const bayar = this.getAttribute("data-bayar");
            const kembalian = this.getAttribute("data-kembalian");
            const items = JSON.parse(this.getAttribute("data-items"));
            
            document.getElementById("btnModalPrint").onclick = function() {
                const width = 450;
                const height = 650;
                const left = (screen.width - width) / 2;
                const top = (screen.height - height) / 2;
                window.open('<?= BASEURL; ?>/kasir/cetakStruk/' + transId, 'Cetak Struk', `width=${width},height=${height},top=${top},left=${left},menubar=no,toolbar=no,location=no,status=no`);
            };
            
            document.getElementById("modal_invoice").innerText = invoice;
            document.getElementById("modal_tanggal").innerText = tanggal;
            document.getElementById("modal_kasir").innerText = kasir;
            document.getElementById("modal_total").innerText = total;
            document.getElementById("modal_bayar").innerText = bayar;
            document.getElementById("modal_kembalian").innerText = kembalian;
            
            const tbody = document.getElementById("modal_items_table_body");
            tbody.innerHTML = "";
            
            if(items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">Tidak ada data produk</td></tr>`;
            } else {
                items.forEach(item => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${escapeHtml(item.nama_produk)}</td>
                        <td class="text-center">${item.qty}</td>
                        <td class="text-end">Rp ${item.harga.toLocaleString('id-ID')}</td>
                        <td class="text-end fw-semibold">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
            
            detailModal.show();
        });
    });
    
    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});

function exportReport(format) {
    const start = '<?= $start_date ?>';
    const end = '<?= $end_date ?>';
    if (format === 'print') {
        window.open('<?= BASEURL; ?>/admin/cetakLaporan?start_date=' + start + '&end_date=' + end, '_blank');
    } else if (format === 'excel') {
        window.location.href = '<?= BASEURL; ?>/admin/exportExcel?start_date=' + start + '&end_date=' + end;
    }
}
</script>
