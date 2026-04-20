<div class="text-center mb-4">
    <img src="../assets/img/pinjam_jti-removebg-preview-1.png" class="img-fluid mb-3" style="max-width:220px;">
    <p>Lupa Kata Sandi?</p>
</div>

<form action="forgot.php" method="POST">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" placeholder="Masukkan email Anda">
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill" style="background-color: #0F2854; border-color: #0F2854;">
        Kirim Link Reset
    </button>

    <p class="text-center small mt-3">
        Sudah ingat password Anda?
        <a href="Login.php?page=login">Kembali ke Login</a>
    </p>

</form>
