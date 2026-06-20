<div class="mb-4">
    <h2 class="fw-bold mb-1" style="color: var(--espresso-dark);">Edit Produk</h2>
    <p class="text-muted mb-0">Ubah informasi dan stok produk</p>
</div>

<div class="card shadow p-4 border-0">
    <form method="POST" action="<?= BASEURL; ?>/admin/produkEdit/<?= $produk['id'] ?>">
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Produk</label>
            <input type="text" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']) ?>" class="form-control" required style="border-radius: 8px;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="kategori" class="form-select" required style="border-radius: 8px;">
                <option value="">-- Pilih Kategori --</option>
                <option value="Kopi" <?= $produk['kategori'] === 'Kopi' ? 'selected' : '' ?>>Kopi</option>
                <option value="Susu" <?= $produk['kategori'] === 'Susu' ? 'selected' : '' ?>>Susu</option>
                <option value="Minuman Lain" <?= $produk['kategori'] === 'Minuman Lain' ? 'selected' : '' ?>>Minuman Lain</option>
                <option value="Nasi" <?= $produk['kategori'] === 'Nasi' ? 'selected' : '' ?>>Nasi</option>
                <option value="Cemilan" <?= $produk['kategori'] === 'Cemilan' ? 'selected' : '' ?>>Cemilan</option>
                <option value="Tambahan" <?= $produk['kategori'] === 'Tambahan' ? 'selected' : '' ?>>Tambahan</option>
                <option value="Mie" <?= $produk['kategori'] === 'Mie' ? 'selected' : '' ?>>Mie</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                <input type="number" name="harga" value="<?= htmlspecialchars($produk['harga']) ?>" class="form-control" required style="border-radius: 8px;">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Harga Modal (Rp)</label>
                <input type="number" name="modal" value="<?= htmlspecialchars($produk['modal']) ?>" class="form-control" required style="border-radius: 8px;">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Stok</label>
            <input type="number" name="stok" value="<?= htmlspecialchars($produk['stok']) ?>" class="form-control" required style="border-radius: 8px;">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="update" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
                <i class="bi bi-save me-1"></i> Update
            </button>
            <a href="<?= BASEURL; ?>/admin/produk" class="btn btn-secondary px-4 py-2" style="border-radius: 8px; background-color: #EFE6D8; border-color: #EFE6D8; color: var(--text-primary);">
                Batal
            </a>
        </div>
    </form>
</div>
