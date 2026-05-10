<?php if (isset($_GET['success']) && isset($_GET['token'])): ?>
    <div class="alert alert-success text-center small">
        Kode OTP berhasil dikirim!<br>
        <a href="Login.php?page=reset&token=<?= htmlspecialchars($_GET['token']) ?>">
            Klik di sini untuk reset password
        </a>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center small">
        <?php
        if ($_GET['error'] == 'email_not_found') {
            echo "Email tidak ditemukan!";
        } else {
            echo "Terjadi kesalahan!";
        }
        ?>
    </div>
<?php endif; ?>

<div class="text-center mb-4">
    <img src="../assets/img/pinjam_jti-removebg-preview-1.png" class="img-fluid mb-3" style="max-width:220px;">
    <p>Lupa Kata Sandi?</p>
</div>

<form action="../controllers/ForgotController.php" method="POST">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" placeholder="Masukkan email Anda">
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background-color: #0F2854; border-color: #0F2854;">
        Kirim Kode OTP
    </button>

    <p class="text-center small mt-3">
        Sudah ingat password Anda?
        <a href="Login.php?page=login">Kembali ke Login</a>
    </p>

</form>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        if (typeof showLoadingOverlay === 'function') {
            showLoadingOverlay();
        }
        
        setTimeout(() => {
            form.submit();
        }, 2000);
    });
</script>