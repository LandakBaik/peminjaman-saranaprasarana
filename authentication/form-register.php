<div class="text-center mb-4">
    <img src="../assets/img/pinjam_jti-removebg-preview-1.png" class="img-fluid mb-3" style="max-width:220px;">
    <p>Registrasi Akun Baru</p>
</div>

<?php if(isset($_GET['error'])): ?>
<div class="alert alert-danger py-2 text-center small" role="alert">
    <?php 
        if($_GET['error'] == 'email_exists') {
            echo 'Email sudah digunakan, silakan gunakan email lain!';
        } elseif($_GET['error'] == 'domain_invalid') {
            echo 'Gunakan email dengan domain student.polije.ac.id!';
        } elseif($_GET['error'] == 'invalid_email') {
            echo 'Format email tidak valid!';
        } else {
            echo 'Terjadi kesalahan saat registrasi!';
        }
    ?>
</div>
<?php endif; ?>

<form action="../controllers/AuthController.php?action=register" method="POST">
    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" name="nama" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        <div class="invalid-feedback">Password tidak cocok!</div>
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background-color: #0F2854; border-color: #0F2854;">
        Daftar
    </button>

    <p class="text-center small mt-3">
        Sudah punya akun?
        <a href="Login.php?page=login">Login di sini</a>
    </p>

</form>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        e.preventDefault();
        document.getElementById('confirm_password').classList.add('is-invalid');
    } else {
        e.preventDefault();
        const form = this;
        
        if (typeof showLoadingOverlay === 'function') {
            showLoadingOverlay();
        }
        
        setTimeout(() => {
            form.submit();
        }, 2000);
    }
});

document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    if (this.value === password) {
        this.classList.remove('is-invalid');
    }
});
</script>
