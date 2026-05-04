<?php
// require_once 'config/Autoload.php';

// koneksi database
$database = new \App\Config\Database();
$db = $database->getConnection();

// ambil id user dari session
$userId = $_SESSION['user']['id'] ?? 0;

// ambil data profil
$query = "SELECT * FROM detail_profil WHERE id_pengguna = :id LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $userId);
$stmt->execute();

$profil = $stmt->fetch(PDO::FETCH_ASSOC);

// kalau belum ada data
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
                            ? 'uploads/' . $profil['foto_profil'] 
                            : 'img/profile.jpg'; ?>" 
                            class="rounded-3" width="115" height="115" style="object-fit:cover;">

                        <div>
                            <!-- NAMA -->
                            <h3 class="fw-semibold mb-1">
                                <?= $profil['nama_panggilan'] ?? $_SESSION['user']['nama'] ?? 'User'; ?>
                            </h3>

                            <!-- ROLE -->
                            <p class="text-muted mb-3">
                                <?= $_SESSION['user']['role'] ?? '-'; ?>
                            </p>

                            <!-- EMAIL -->
                            <p class="text-secondary mb-0">
                                <?= $_SESSION['user']['email'] ?? '-'; ?>
                            </p>
                        </div>
                    </div>

                    <a href="index.php?page=detail-profil-edit" 
                       class="btn btn-primary btn-sm px-4 mt-3 mt-md-0">
                        Edit
                    </a>
                </div>

                <hr class="my-4">

                <!-- Detail Data -->
                <div class="row g-4">

                    <!-- Nama Lengkap -->
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nama Lengkap</label>
                        <div class="form-control bg-light">
                            <?= $_SESSION['user']['nama'] ?? '-'; ?>
                        </div>
                    </div>

                    <!-- Nama Panggilan -->
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nama Panggilan</label>
                        <div class="form-control bg-light">
                            <?= $profil['nama_panggilan'] ?? '-'; ?>
                        </div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nomor Telepon</label>
                        <div class="form-control bg-light">
                            <?= $profil['nomor_telepon'] ?? '-'; ?>
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tanggal Lahir</label>
                        <div class="form-control bg-light">
                            <?= isset($profil['tanggal_lahir']) 
                                ? date('d/m/Y', strtotime($profil['tanggal_lahir'])) 
                                : '-'; ?>
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="col-12">
                        <label class="form-label text-muted">Jenis Kelamin</label>
                        <div class="form-control bg-light">
                            <?= $profil['jenis_kelamin'] ?? '-'; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>