<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--espresso-dark);">Data Produk</h2>
        <p class="text-muted mb-0">Kelola informasi produk dan stok toko Anda</p>
    </div>
    
    <div class="d-flex flex-wrap align-items-center gap-2 bg-white p-2 border shadow-sm" style="border-radius: 12px; border-color: var(--border-color) !important; min-width: 300px; max-width: 400px; flex-grow: 1;">
        <form method="GET" action="<?= BASEURL; ?>/admin/produk" class="d-flex align-items-center gap-2 w-100 m-0">
            <div class="d-flex align-items-center position-relative w-100">
                <i class="bi bi-search text-muted position-absolute" style="left: 10px; font-size: 0.9rem;"></i>
                <input type="text" name="search" class="form-control border-0 py-1" placeholder="Cari produk atau kategori..." value="<?= htmlspecialchars($search) ?>" style="font-size: 0.9rem; font-family: var(--font-outfit); padding-left: 32px; padding-right: 30px; background: transparent; width: 100%;" aria-label="Cari Produk">
                <?php if ($search !== ''): ?>
                    <a href="<?= BASEURL; ?>/admin/produk" class="text-muted position-absolute" style="right: 10px; font-size: 0.95rem; text-decoration: none;">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-3 py-2" style="border-radius: 8px;">
                Cari
            </button>
        </form>
    </div>

    <div>
        <a href="<?= BASEURL; ?>/admin/produkTambah" class="btn btn-primary d-flex align-items-center gap-1 px-3 py-2" style="border-radius: 10px;">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>
</div>

<table class="table table-bordered table-striped">
<thead>
<tr>
<th>No</th>
<th>Gambar</th>
<th>Nama Produk</th>
<th>Kategori</th>
<th>Harga Jual</th>
<th>Harga Modal</th>
<th>Stok</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php if (empty($products)): ?>
    <tr>
        <td colspan="8" class="text-center py-5 text-muted">
            <i class="bi bi-box-seam d-block mb-3" style="font-size: 2.5rem; color: var(--text-muted); opacity: 0.5;"></i>
            <span>Tidak ada produk yang cocok dengan pencarian Anda.</span>
        </td>
    </tr>
<?php else: ?>
    <?php
    $no = 1;
    foreach ($products as $row){
    ?>
    <tr>
    <td><?= $no++ ?></td>
    <td>
        <img 
            src="<?= BASEURL; ?>/assets/img/products/<?= $row['gambar'] ?: 'default.png'; ?>"
            alt="<?= htmlspecialchars($row['nama_produk']); ?>"
            width="32"
            height="32"
            style="object-fit: contain; border-radius: 6px;"
        >
    </td>
    <td class="fw-semibold"><?= htmlspecialchars($row['nama_produk']) ?></td>
    <td>
        <span class="badge-category"><?= htmlspecialchars($row['kategori']) ?></span>
    </td>
    <td>Rp <?= number_format($row['harga']) ?></td>
    <td>Rp <?= number_format($row['modal']) ?></td>
    <td><?= $row['stok'] ?></td>
    <td>
    <a href="<?= BASEURL; ?>/admin/produkEdit/<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
    <a href="<?= BASEURL; ?>/admin/produkHapus/<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus produk?')">Hapus</a>
    </td>
    </tr>
    <?php } ?>
<?php endif; ?>
</tbody>
</table>
