<div id="layoutSidenav_content">
    <div class="container-fluid px-4 mt-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h5 class="text-muted mb-4">Detail Profil</h5>
                <!-- Header Profil -->
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div class="d-flex align-items-start gap-3">
                        <img src="img/profile.jpg" class="rounded-3" width="115" height="115" style="object-fit:cover;">
                        <div>
                            <h3 class="mb-1 fw-semibold">Rolanda Rafa Ibra Augusta</h3>
                            <p class="text-muted mb-4">Peminjam</p>
                            <p class="text-secondary mb-0">rolan123@gmail.com</p>
                        </div>
                    </div>
                </div>
                <hr class="my-4">

                <!-- Form -->
                <form>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control" value="Rolanda Rafa Ibra Augusta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Panggilan</label>
                            <input type="text" class="form-control" value="Roland">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nomor Telepon</label>
                            <input type="text" class="form-control" value="08xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tahun, Bulan, Tanggal Lahir</label>
                            <input type="text" class="form-control" value="15/08/2006">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Jenis Kelamin</label>
                            <div class="d-flex gap-5 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" checked>
                                    <label class="form-check-label">Laki-Laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio">
                                    <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tombol -->
                    <div class="mt-5 pt-3">
                        <a href="index.php?page=detail-profil" class="btn btn-primary px-4 me-2">Simpan</a>
                        <a href="index.php?page=detail-profil" class="btn btn-danger px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>