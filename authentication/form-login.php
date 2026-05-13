<?php if (isset($_GET['success']) && $_GET['success'] == 'reset'): ?>
    <div class="alert alert-success py-2 text-center small">
        Password berhasil direset! Silakan login.
    </div>
<?php endif; ?>

<div class="text-center mb-4">
    <img src="../assets/img/pinjam_jti-removebg-preview-1.png" class="img-fluid mb-3" style="max-width:220px;">
    <p>Selamat Datang Di Halaman Login</p>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <div class="alert alert-danger py-2 text-center small" role="alert">
        Email atau password salah!
    </div>
<?php endif; ?>

<form action="../controllers/AuthController.php?action=login" method="POST">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
    </div>

    <div class="d-flex justify-content-between small mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="showPassword">
            <label class="form-check-label" for="showPassword">
                Tampilkan Password
            </label>
        </div>

        <a href="Login.php?page=forgot">Lupa Password?</a>
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background-color: #0F2854; border-color: #0F2854;">
        Login
    </button>

    <p class="text-center small mt-3">
        Pengguna baru?
        <a href="Login.php?page=register">Daftar Sekarang</a>
    </p>

</form>


<script>
    const checkbox = document.getElementById("showPassword");
    const password = document.getElementById("password");

    checkbox.addEventListener("change", function() {
        if (this.checked) {
            password.type = "text";
        } else {
            password.type = "password";
        }
    });
    // Loading
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            return;
        }

        e.preventDefault();
        const form = this;
        
        if (typeof showLoadingOverlay === 'function') {
            showLoadingOverlay();
        }
        
        setTimeout(() => {
            form.submit();
        }, 1500);
    });
</script>