<?php
// koneksi database
$database = new \App\Config\Database();
$db = $database->getConnection();

// ambil id user dari session
$userId = $_SESSION['user']['id'] ?? 0;

// ambil data profil dari database
$query = "SELECT * FROM detail_profil WHERE id_pengguna = :id LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $userId);
$stmt->execute();

$profil = $stmt->fetch(PDO::FETCH_ASSOC);

// jika belum ada data
if (!$profil) {
    $profil = [];
}
?>

<div id="layoutSidenav_content">
    <div class="container-fluid px-4 mt-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <h5 class="text-muted mb-4">Detail Profil</h5>

                <!-- Header Profil -->
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div class="d-flex align-items-start gap-3">

                        <!-- FOTO -->
                        <img src="<?= !empty($profil['foto_profil']) 
                            ? 'uploads/' . htmlspecialchars($profil['foto_profil']) 
                            : 'assets/img/no-image.png'; ?>" 
                            class="rounded-3" 
                            width="115" 
                            height="115" 
                            style="object-fit:cover;">

                        <div>
                            <h3 class="mb-1 fw-semibold">
                                <?= htmlspecialchars($profil['nama_panggilan'] ?? $_SESSION['user']['nama'] ?? 'User'); ?>
                            </h3>

                            <p class="text-muted mb-4">
                                <?= htmlspecialchars($_SESSION['user']['role'] ?? '-'); ?>
                            </p>

                            <p class="text-secondary mb-0">
                                <?= htmlspecialchars($_SESSION['user']['email'] ?? '-'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- FORM -->
                <form method="POST" 
                      action="controllers/ProfileController.php?action=save" 
                      enctype="multipart/form-data">

                    <div class="row g-4">

                        <!-- Nama Lengkap -->
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <input type="text" 
                                   class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user']['nama'] ?? '') ?>" 
                                   disabled>
                        </div>

                        <!-- Nama Panggilan -->
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Panggilan</label>
                            <input type="text" 
                                   name="nama_panggilan" 
                                   class="form-control"
                                   value="<?= htmlspecialchars($profil['nama_panggilan'] ?? '') ?>">
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nomor Telepon</label>
                            <input type="text" 
                                   name="nomor_telepon" 
                                   class="form-control"
                                   value="<?= htmlspecialchars($profil['nomor_telepon'] ?? '') ?>">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tanggal Lahir</label>
                            <input type="date" 
                                   name="tanggal_lahir" 
                                   class="form-control"
                                   value="<?= !empty($profil['tanggal_lahir']) 
                                        ? htmlspecialchars($profil['tanggal_lahir']) 
                                        : '' ?>">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="col-12">
                            <label class="form-label text-muted">Jenis Kelamin</label>

                            <div class="d-flex gap-5 mt-2">

                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="jenis_kelamin" 
                                           value="Laki-laki"
                                           <?= (($profil['jenis_kelamin'] ?? '') === 'Laki-laki') ? 'checked' : '' ?>>

                                    <label class="form-check-label">
                                        Laki-Laki
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="jenis_kelamin" 
                                           value="Perempuan"
                                           <?= (($profil['jenis_kelamin'] ?? '') === 'Perempuan') ? 'checked' : '' ?>>

                                    <label class="form-check-label">
                                        Perempuan
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Upload Foto -->
                        <div class="col-12">
                            <label class="form-label text-muted">Foto Profil</label>
                            <input type="file" 
                                   name="foto_profil" 
                                   class="form-control" 
                                   accept="image/*">
                        </div>

                    </div>

                    <!-- Tombol -->
                    <div class="mt-5 pt-3">
                        <button type="submit" class="btn btn-primary px-4 me-2">
                            Simpan
                        </button>

                        <a href="index.php?page=detail-profil" 
                           class="btn btn-danger px-4">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>