<div class="mb-4">
    <h2 class="fw-bold mb-1" style="color: var(--espresso-dark);">Tambah Produk</h2>
    <p class="text-muted mb-0">Tambah data produk baru ke inventaris toko</p>
</div>

<div class="card shadow p-4 border-0">
    <form method="POST" action="<?= BASEURL; ?>/admin/produkTambah">
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Kopi Susu Gula Aren" required style="border-radius: 8px;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="kategori" class="form-select" required style="border-radius: 8px;">
                <option value="">-- Pilih Kategori --</option>
                <option value="Kopi">Kopi</option>
                <option value="Susu">Susu</option>
                <option value="Minuman Lain">Minuman Lain</option>
                <option value="Nasi">Nasi</option>
                <option value="Cemilan">Cemilan</option>
                <option value="Tambahan">Tambahan</option>
                <option value="Mie">Mie</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                <input type="number" name="harga" class="form-control" placeholder="Masukkan harga jual..." required style="border-radius: 8px;">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Harga Modal (Rp)</label>
                <input type="number" name="modal" class="form-control" placeholder="Masukkan harga modal..." required style="border-radius: 8px;">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Stok Awal</label>
            <input type="number" name="stok" class="form-control" placeholder="Masukkan jumlah stok awal..." required style="border-radius: 8px;">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="simpan" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
            <a href="<?= BASEURL; ?>/admin/produk" class="btn btn-secondary px-4 py-2" style="border-radius: 8px; background-color: #EFE6D8; border-color: #EFE6D8; color: var(--text-primary);">
                Batal
            </a>
        </div>
    </form>
</div>
