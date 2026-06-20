<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--espresso-dark);">Manajemen Pengguna</h2>
        <p class="text-muted mb-0">Kelola akun kasir dan admin</p>
    </div>
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#tambahAkunModal">
        <i class="bi bi-plus-lg"></i> Tambah Akun
    </button>
</div>

<!-- Success and Error Alerts -->
<?php if (!empty($success_msg)): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="background-color: #E6F4EA; color: #137333; border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success_msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="background-color: #FCE8E6; color: #C5221F; border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error_msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Users Grid -->
<div class="row g-4">
    <?php foreach ($users as $user): ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="user-card text-start" style="padding: 20px; border-radius: 16px; border: 1px solid var(--border-color); background-color: #fff; position: relative;">
                <div class="d-flex align-items-center">
                    <!-- Avatar box based on role -->
                    <?php if ($user['role'] == 'admin'): ?>
                        <div class="user-card-avatar admin">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    <?php else: ?>
                        <div class="user-card-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="user-card-info" style="margin-left: 15px;">
                        <div class="user-card-name" style="font-weight: 600; color: var(--espresso-dark);"><?= htmlspecialchars($user['nama']) ?></div>
                        <div class="user-card-role d-inline-block badge bg-light text-dark mb-1" style="font-weight: 500; font-size: 0.75rem; border-radius: 6px; border: 1px solid var(--border-color); padding: 4px 8px;">
                            <?= ucfirst(htmlspecialchars($user['role'])) ?>
                        </div>
                        <div class="user-card-pin text-muted" style="font-size: 0.8rem;">
                            PIN: <span class="text-secondary">••••</span>
                            <span class="ms-2 text-muted" style="font-size: 0.75rem;">(@<?= htmlspecialchars($user['username']) ?>)</span>
                        </div>
                    </div>
                </div>
                
                <div class="user-card-actions" style="position: absolute; top: 15px; right: 15px;">
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-link text-muted p-2 edit-user-btn" 
                            data-id="<?= $user['id'] ?>" 
                            data-nama="<?= htmlspecialchars($user['nama']) ?>" 
                            data-username="<?= htmlspecialchars($user['username']) ?>" 
                            data-role="<?= $user['role'] ?>"
                            title="Edit Akun"
                            style="border-radius: 8px; transition: background-color 0.2s; border: none; background: transparent;">
                        <i class="bi bi-pencil" style="font-size: 1.1rem;"></i>
                    </button>
                    
                    <!-- Delete Button -->
                    <a href="<?= BASEURL; ?>/admin/pengguna?action=hapus&id=<?= $user['id'] ?>" 
                       class="btn btn-link text-danger p-2" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus akun <?= htmlspecialchars($user['nama']) ?>?')"
                       title="Hapus Akun"
                       style="border-radius: 8px; transition: background-color 0.2s; text-decoration: none;">
                        <i class="bi bi-trash" style="font-size: 1.1rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Tambah Akun -->
<div class="modal fade" id="tambahAkunModal" tabindex="-1" aria-labelledby="tambahAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="tambahAkunModalLabel" style="color: var(--espresso-dark);">Tambah Akun Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASEURL; ?>/admin/pengguna?action=tambah" method="POST">
                <div class="modal-body py-4 text-start">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold" style="font-size: 0.9rem;">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Kasir Baru" required style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold" style="font-size: 0.9rem;">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Contoh: kasirbaru" required style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold" style="font-size: 0.9rem;">PIN / Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password/PIN login" required style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                    </div>
                    <div class="mb-0">
                        <label for="role" class="form-label fw-semibold" style="font-size: 0.9rem;">Role Hak Akses</label>
                        <select class="form-select" id="role" name="role" required style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                            <option value="kasir" selected>Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn text-secondary fw-semibold" data-bs-dismiss="modal" style="border-radius: 10px; padding: 8px 16px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 8px 20px;">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Akun -->
<div class="modal fade" id="editAkunModal" tabindex="-1" aria-labelledby="editAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="editAkunModalLabel" style="color: var(--espresso-dark);">Ubah Akun Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASEURL; ?>/admin/pengguna?action=edit" method="POST">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body py-4 text-start">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label fw-semibold" style="font-size: 0.9rem;">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                    </div>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label fw-semibold" style="font-size: 0.9rem;">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label fw-semibold" style="font-size: 0.9rem;">PIN / Password Baru</label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah PIN" style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                    </div>
                    <div class="mb-0">
                        <label for="edit_role" class="form-label fw-semibold" style="font-size: 0.9rem;">Role Hak Akses</label>
                        <select class="form-select" id="edit_role" name="role" required style="border-radius: 10px; padding: 10px; border-color: var(--border-color);">
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn text-secondary fw-semibold" data-bs-dismiss="modal" style="border-radius: 10px; padding: 8px 16px;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 8px 20px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll(".edit-user-btn");
    const editModal = new bootstrap.Modal(document.getElementById('editAkunModal'));
    
    editButtons.forEach(button => {
        button.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            const nama = this.getAttribute("data-nama");
            const username = this.getAttribute("data-username");
            const role = this.getAttribute("data-role");
            
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_nama").value = nama;
            document.getElementById("edit_username").value = username;
            document.getElementById("edit_role").value = role;
            document.getElementById("edit_password").value = "";
            
            editModal.show();
        });
    });
});
</script>
