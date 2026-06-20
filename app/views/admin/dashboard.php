<div class="mb-4">
    <h2 class="fw-bold mb-1" style="color: var(--espresso-dark);">Selamat datang, <?= htmlspecialchars($_SESSION['nama']); ?>!</h2>
</div>

<!-- Statistics Grid -->
<div class="row g-4 mb-4">
    <!-- Pendapatan Hari Ini -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-info">
                <span class="stat-card-value">Rp <?= number_format($todayIncome); ?></span>
                <span class="stat-card-label">Pendapatan Hari Ini</span>
            </div>
            <div class="stat-card-icon revenue">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </div>
    
    <!-- Transaksi Hari Ini -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-info">
                <span class="stat-card-value"><?= number_format($todayTrans); ?></span>
                <span class="stat-card-label">Transaksi Hari Ini</span>
            </div>
            <div class="stat-card-icon transactions">
                <i class="bi bi-cart"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Produk -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-info">
                <span class="stat-card-value"><?= number_format($totalProducts); ?></span>
                <span class="stat-card-label">Total Produk</span>
            </div>
            <div class="stat-card-icon products">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
    </div>
    
    <!-- Stok Rendah -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-info">
                <span class="stat-card-value"><?= number_format($lowStock); ?></span>
                <span class="stat-card-label">Stok Rendah</span>
            </div>
            <div class="stat-card-icon low-stock">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Sales Chart Card -->
<div class="card shadow mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4" style="color: var(--espresso-dark);">Penjualan 7 Hari Terakhir</h4>
        <div style="height: 300px; position: relative;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    const chartLabels = <?= json_encode($chartLabels); ?>;
    const chartData = <?= json_encode($chartValues); ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: chartData,
                borderColor: '#BF7234',
                backgroundColor: 'rgba(191, 114, 52, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#BF7234',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
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
                            return ' ' + context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
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
});
</script>
