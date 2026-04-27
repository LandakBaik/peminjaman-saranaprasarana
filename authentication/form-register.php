<div class="text-center mb-4">
    <img src="../assets/img/pinjam_jti-removebg-preview-1.png" class="img-fluid mb-3" style="max-width:220px;">
    <p>Registrasi Akun Baru</p>
</div>

<form action="../controllers/AuthController.php?action=register" method="POST">
    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" name="name" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background-color: #0F2854; border-color: #0F2854;">
        Daftar
    </button>

    <p class="text-center small mt-3">
        Sudah punya akun?
        <a href="Login.php?page=login">Login di sini</a>
    </p>

</form>
