<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS Mang Kojek</title>
    
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS Kustom -->
    <link href="<?= BASEURL; ?>/assets/css/login.css" rel="stylesheet">
</head>
<body>

<div class="split-container">
    <!-- Sisi Kiri: Latar Belakang Estetik Kedai Kopi -->
    <div class="image-side">
        <div class="brand-header">
            <h1 class="brand-title">
                <span class="brand-icon">☕</span> POS Mang Kojek
            </h1>
        </div>
        <div class="quote-container">
            <p class="quote-text">"Kopi yang baik selalu menyertai hari yang produktif. Kelola setiap transaksi dengan kemudahan dan kecepatan maksimal."</p>
            <div class="quote-author">Mang Kojek Cafe</div>
        </div>
        <div>
            <!-- Elemen kosong untuk menyeimbangkan tata letak flex -->
        </div>
    </div>

    <!-- Sisi Kanan: Formulir Masuk -->
    <div class="form-side">
        <div class="form-wrapper">
            <div class="form-header">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk ke akun Anda untuk mengelola kasir dan produk.</p>
            </div>

            <!-- Peringatan Kesalahan -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="custom-alert" id="errorAlert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div><?= htmlspecialchars($_SESSION['error']); ?></div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Formulir -->
            <form action="<?= BASEURL; ?>/auth/loginProcess" method="POST">
                <div class="custom-input-group">
                    <i class="bi bi-person input-icon"></i>
                    <input 
                        type="text" 
                        name="username" 
                        id="username"
                        class="custom-input" 
                        placeholder="Username" 
                        required 
                        autocomplete="username"
                    >
                </div>

                <div class="custom-input-group">
                    <i class="bi bi-lock input-icon"></i>
                    <input 
                        type="password" 
                        name="password" 
                        id="passwordInput"
                        class="custom-input" 
                        placeholder="Password" 
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Tampilkan Password">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>

                <button type="submit" class="btn-login">
                    Masuk ke POS
                </button>
            </form>

            <div class="form-footer">
                &copy; <?= date('Y'); ?> POS Mang Kojek. All Rights Reserved.
            </div>
        </div>
    </div>
</div>

<!-- Script Pengubah Visibilitas Password -->
<script>
function togglePassword() {
    const passwordInput = document.getElementById('passwordInput');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>

</body>
</html>
