<div class="row g-3 pos-wrapper" style="margin-top: -1rem;">
    <!-- Panel Kiri: Katalog Produk -->
    <div class="col-lg-8 d-flex flex-column" style="min-height: calc(100vh - 6rem);">
        <!-- Bagian Judul -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-muted mb-1" style="font-size: 0.95rem;">Selamat Datang, <strong style="color: var(--coffee-caramel);"><?= htmlspecialchars($_SESSION['nama']); ?></strong></p>
                <h2 class="fw-bold mb-1" style="font-size: 1.8rem; color: var(--espresso-dark);">Kasir POS</h2>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Buat transaksi penjualan baru</p>
            </div>
        </div>

        <!-- Kolom Pencarian -->
        <div class="search-wrapper mb-3">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari produk..." onkeyup="filterProducts()">
        </div>

        <!-- Tombol Kategori -->
        <div class="mb-4 d-flex gap-2 overflow-x-auto pb-2" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
            <button type="button" class="filter-pill active" onclick="setCategory('all', this)">Semua</button>
            <button type="button" class="filter-pill" onclick="setCategory('kopi-panas', this)">Kopi Panas</button>
            <button type="button" class="filter-pill" onclick="setCategory('kopi-dingin', this)">Kopi Dingin</button>
            <button type="button" class="filter-pill" onclick="setCategory('non-kopi', this)">Non Kopi</button>
            <button type="button" class="filter-pill" onclick="setCategory('makanan', this)">Makanan</button>
            <button type="button" class="filter-pill" onclick="setCategory('snack', this)">Snack</button>
        </div>

        <!-- Grid Produk yang Bisa Discroll -->
        <div class="product-grid-container flex-grow-1">
            <div class="row g-2" id="productGrid">
                <?php foreach ($products as $produk): ?>
                    <div class="col-6 col-sm-4 col-md-4 product-card-col" 
                         data-id="<?= $produk['id']; ?>"
                         data-name="<?= htmlspecialchars(strtolower($produk['nama_produk'])); ?>"
                         data-category="<?= htmlspecialchars($produk['kategori']); ?>"
                         onclick="addToCart(<?= $produk['id']; ?>, '<?= htmlspecialchars(addslashes($produk['nama_produk'])); ?>', <?= $produk['harga']; ?>, <?= $produk['stok']; ?>, this)"
                    >
                        <div class="pos-product-card shadow-sm <?= ($produk['stok'] <= 0) ? 'out-of-stock' : ''; ?>">
                             <div class="pos-product-img-wrapper">
                                <?php if ($produk['stok'] <= 0): ?>
                                    <img class="out-of-stock-stamp" src="<?= BASEURL; ?>/assets/img/stok_habis.png" alt="STOK HABIS">
                                <?php endif; ?>
                                <?php if (!empty($produk['gambar']) && file_exists("assets/img/products/" . $produk['gambar'])): ?>
                                    <img src="<?= BASEURL; ?>/assets/img/products/<?= $produk['gambar']; ?>" alt="<?= htmlspecialchars($produk['nama_produk']); ?>">
                                <?php else: ?>
                                    <div class="pos-product-img-placeholder">
                                        <i class="bi bi-cup-hot"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="pos-product-info">
                                <div class="pos-product-name" title="<?= htmlspecialchars($produk['nama_produk']); ?>"><?= htmlspecialchars($produk['nama_produk']); ?></div>
                                <p class="pos-product-price">Rp <?= number_format($produk['harga']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Panel Kanan: Bagian Keranjang -->
    <div class="col-lg-4">
        <div class="cart-panel">
            <!-- Header Keranjang -->
            <div class="cart-header">
                <h3 class="cart-title">Keranjang</h3>
                <button type="button" class="cart-clear-btn" onclick="clearCart()">Hapus Semua</button>
            </div>

            <!-- Daftar Item Keranjang yang Bisa Discroll -->
            <div class="cart-items-list" id="cartItemsList">
                <div class="cart-empty-state">
                    <i class="bi bi-cart3"></i>
                    <p class="mb-0">Keranjang masih kosong.<br>Klik produk di sebelah kiri untuk menambahkan.</p>
                </div>
            </div>

            <!-- Footer Keranjang Melayang -->
            <div class="cart-footer">
                <div class="cart-total-row">
                    <span class="cart-total-label">Total</span>
                    <span class="cart-total-val" id="cartTotalDisplay">Rp 0</span>
                </div>
                <button type="button" id="btnCheckout" class="btn-checkout" disabled onclick="openCheckoutModal()">
                    Bayar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header text-white border-0 py-3" style="background-color: var(--espresso-dark);">
                <h5 class="modal-title fw-bold" id="checkoutModalLabel">☕ Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="<?= BASEURL; ?>/kasir/simpanTransaksi" method="POST" id="checkoutForm">
                <!-- Data Keranjang Dinamis untuk Pengiriman Form -->
                <div id="hiddenCartInputs"></div>
                <input type="hidden" name="total" id="inputTotal">

                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                           <p class="text-muted mb-1 fs-6">Total Tagihan</p>
                           <h2 class="fw-bold" style="color: var(--coffee-caramel); font-size: 2.2rem;" id="modalTotalDisplay">Rp 0</h2>
                    </div>

                    <div class="mb-3">
                           <label class="form-label fw-semibold">Uang Dibayar (Tunai)</label>
                           <input type="number" name="bayar" id="bayar" class="form-control form-control-lg text-center fw-bold fs-4" placeholder="0" required oninput="calculateChange()">
                    </div>

                    <!-- Pilihan Cepat Uang Tunai -->
                    <div class="quick-cash-grid">
                           <button type="button" class="btn-quick-cash" onclick="setCashAmount('pas')">Uang Pas</button>
                           <button type="button" class="btn-quick-cash" onclick="setCashAmount(10000)">10.000</button>
                           <button type="button" class="btn-quick-cash" onclick="setCashAmount(20000)">20.000</button>
                           <button type="button" class="btn-quick-cash" onclick="setCashAmount(50000)">50.000</button>
                           <button type="button" class="btn-quick-cash" onclick="setCashAmount(100000)">100.000</button>
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center mb-2">
                           <span class="fw-semibold text-muted">Kembalian</span>
                           <h4 class="fw-bold mb-0 text-success" id="modalChangeDisplay">Rp 0</h4>
                    </div>
                </div>

                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                    <button type="submit" class="btn btn-success px-4 py-2" style="border-radius: 10px; background-color: var(--coffee-caramel); border-color: var(--coffee-caramel);">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($successTrans): ?>
<!-- Modal Transaksi Berhasil -->
<div class="modal fade" id="successModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="success-icon-wrapper" style="width: 72px; height: 72px; background-color: #E8F5E9; color: #2E7D32; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2.2rem; margin-top: 0.5rem; margin-bottom: 0.5rem;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3 class="fw-bold mt-2" style="color: var(--espresso-dark);">Transaksi Berhasil!</h3>
                    <p class="text-muted" style="font-size: 0.95rem;">Pembayaran telah diterima dan dicatat.</p>
                </div>

                <div class="bg-light p-3 rounded-3 mb-4">
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;">
                        <span class="text-muted">No. Invoice</span>
                        <span class="fw-bold" style="color: var(--coffee-caramel);"><?= htmlspecialchars($successTrans['invoice']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;">
                        <span class="text-muted">Total Belanja</span>
                        <span class="fw-bold text-dark">Rp <?= number_format($successTrans['total'], 0, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.9rem;">
                        <span class="text-muted">Uang Dibayar</span>
                        <span class="text-dark">Rp <?= number_format($successTrans['bayar'], 0, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                        <span class="fw-semibold text-muted">Kembalian</span>
                        <h4 class="fw-bold mb-0 text-success">Rp <?= number_format($successTrans['kembalian'], 0, ',', '.'); ?></h4>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-12">
                        <button type="button" class="btn btn-success w-100 py-2.5 fw-bold text-white" onclick="printReceipt(<?= $successTrans['id']; ?>)" style="border-radius: 12px; background-color: var(--coffee-caramel); border-color: var(--coffee-caramel);">
                            <i class="bi bi-printer-fill me-2"></i> Cetak Struk
                        </button>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-secondary w-100 py-2 fw-semibold" onclick="resetToNewTransaction()" style="border-radius: 12px; background-color: #EFE6D8; border-color: #EFE6D8; color: var(--espresso-dark);">
                            Transaksi Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Script Kerja Keranjang Dinamis -->
<script>
let cart = [];
let currentCategory = 'all';
let searchQuery = '';
let lastModifiedId = null;

const categoryFilters = {
    'all': (cat, name) => true,
    'kopi-panas': (cat, name) => cat === 'Kopi' && (name.includes('hangat') || name.includes('tubruk') || name === 'americano'),
    'kopi-dingin': (cat, name) => cat === 'Kopi' && (name.includes('gula aren') || name.includes('mang kojek') || name.includes('leci') || name.includes('lemon') || name.includes('mangga') || name.includes('es')),
    'non-kopi': (cat, name) => cat === 'Susu' || cat === 'Minuman Lain',
    'makanan': (cat, name) => cat === 'Nasi' || cat === 'Mie',
    'snack': (cat, name) => cat === 'Cemilan' || cat === 'Tambahan'
};

function setCategory(category, element) {
    document.querySelectorAll('.filter-pill').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    currentCategory = category;
    applyFilters();
}

function filterProducts() {
    searchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
    applyFilters();
}

function applyFilters() {
    const filterFn = categoryFilters[currentCategory];
    document.querySelectorAll('.product-card-col').forEach(card => {
        const name = card.dataset.name;
        const cat = card.dataset.category;
        
        const matchesCategory = filterFn(cat, name);
        const matchesSearch = searchQuery === '' || name.includes(searchQuery);
        
        if (matchesCategory && matchesSearch) {
            card.style.setProperty('display', 'block', 'important');
        } else {
            card.style.setProperty('display', 'none', 'important');
        }
    });
}

function addToCart(id, name, price, maxStock, element) {
    if (maxStock <= 0) {
        alert('Stok produk ini sedang habis!');
        return;
    }

    if (element) {
        const card = element.querySelector('.pos-product-card');
        if (card) {
            card.classList.remove('clicked-flash');
            void card.offsetWidth;
            card.classList.add('clicked-flash');
        }
    }

    lastModifiedId = id;
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        if (existingItem.qty >= maxStock) {
            alert('Stok produk tidak mencukupi!');
            return;
        }
        existingItem.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, maxStock });
    }
    renderCart();
}

function updateQty(id, delta) {
    const item = cart.find(item => item.id === id);
    if (!item) return;

    const newQty = item.qty + delta;
    if (newQty <= 0) {
        removeFromCart(id);
    } else if (newQty > item.maxStock) {
        alert('Stok produk tidak mencukupi!');
    } else {
        lastModifiedId = id;
        item.qty = newQty;
        renderCart();
    }
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

function getCartTotal() {
    return cart.reduce((total, item) => total + (item.price * item.qty), 0);
}

function renderCart() {
    const cartList = document.getElementById('cartItemsList');
    const btnCheckout = document.getElementById('btnCheckout');
    const cartTotalDisplay = document.getElementById('cartTotalDisplay');
    
    if (cart.length === 0) {
        cartList.innerHTML = `
            <div class="cart-empty-state">
                <i class="bi bi-cart3"></i>
                <p class="mb-0">Keranjang masih kosong.<br>Klik produk di sebelah kiri untuk menambahkan.</p>
            </div>
        `;
        btnCheckout.disabled = true;
        cartTotalDisplay.innerText = 'Rp 0';
        return;
    }

    btnCheckout.disabled = false;
    let html = '';
    cart.forEach(item => {
        const subtotal = item.price * item.qty;
        html += `
            <div class="cart-item-card" id="cart-item-${item.id}">
                <button type="button" class="cart-item-delete" onclick="removeFromCart(${item.id})" title="Hapus">
                    <i class="bi bi-trash-fill"></i>
                </button>
                <div class="cart-item-title">${item.name}</div>
                <div class="cart-item-price">Rp ${item.price.toLocaleString('id-ID')}</div>
                
                <div class="cart-item-footer">
                    <div class="cart-item-qty-control">
                        <button type="button" class="cart-qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="cart-qty-num">${item.qty}</span>
                        <button type="button" class="cart-qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                    <h5 class="cart-item-subtotal">Rp ${subtotal.toLocaleString('id-ID')}</h5>
                </div>
            </div>
        `;
    });
    cartList.innerHTML = html;
    cartTotalDisplay.innerText = 'Rp ' + getCartTotal().toLocaleString('id-ID');

    if (lastModifiedId) {
        const cartItemEl = document.getElementById(`cart-item-${lastModifiedId}`);
        if (cartItemEl) {
            cartItemEl.classList.add('highlight');
            setTimeout(() => {
                cartItemEl.classList.remove('highlight');
            }, 600);
        }
        lastModifiedId = null;
    }
}

let checkoutModalObj = null;
function openCheckoutModal() {
    const total = getCartTotal();
    document.getElementById('modalTotalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('inputTotal').value = total;
    
    const container = document.getElementById('hiddenCartInputs');
    container.innerHTML = '';
    cart.forEach(item => {
        container.innerHTML += `
            <input type="hidden" name="produk_id[]" value="${item.id}">
            <input type="hidden" name="qty[]" value="${item.qty}">
            <input type="hidden" name="harga[]" value="${item.price}">
        `;
    });
    
    document.getElementById('bayar').value = '';
    document.getElementById('modalChangeDisplay').innerText = 'Rp 0';
    document.getElementById('modalChangeDisplay').className = 'fw-bold mb-0 text-success';
    
    if (!checkoutModalObj) {
        checkoutModalObj = new bootstrap.Modal(document.getElementById('checkoutModal'));
    }
    checkoutModalObj.show();
}

function setCashAmount(amount) {
    const total = getCartTotal();
    const bayarInput = document.getElementById('bayar');
    if (amount === 'pas') {
        bayarInput.value = total;
    } else {
        bayarInput.value = amount;
    }
    calculateChange();
}

function calculateChange() {
    const total = getCartTotal();
    const bayar = parseInt(document.getElementById('bayar').value) || 0;
    const kembalian = bayar - total;
    
    const display = document.getElementById('modalChangeDisplay');
    if (kembalian < 0) {
        display.innerText = 'Kurang Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
        display.className = 'fw-bold mb-0 text-danger';
    } else {
        display.innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
        display.className = 'fw-bold mb-0 text-success';
    }
}

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const total = getCartTotal();
    const bayar = parseInt(document.getElementById('bayar').value) || 0;
    if (bayar < total) {
        alert('Uang pembayaran kurang!');
        e.preventDefault();
    }
});

function printReceipt(id) {
    const width = 450;
    const height = 650;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    window.open('<?= BASEURL; ?>/kasir/cetakStruk/' + id, 'Cetak Struk', `width=${width},height=${height},top=${top},left=${left},menubar=no,toolbar=no,location=no,status=no`);
}

function resetToNewTransaction() {
    window.location.href = '<?= BASEURL; ?>/kasir/transaksi';
}

<?php if ($successTrans): ?>
document.addEventListener("DOMContentLoaded", function() {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
});
<?php endif; ?>
</script>
