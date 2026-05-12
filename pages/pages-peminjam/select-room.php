<?php
$database = new \App\Config\Database();
$db = $database->getConnection();

$ruanganModel = new \App\Models\Ruangan($db);
$stmt = $ruanganModel->readAll();

$labs = [];
$nonLabs = [];

while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (strtolower($r['tipe_ruangan'] ?? '') === 'laboratorium') {
        $labs[] = $r;
    } else {
        $nonLabs[] = $r;
    }
}
?>

<div id="layoutSidenav_content" class="bg-light">
    <main>
        <div class="container-fluid px-4 pb-5">
            <div class="mt-4 mb-5">
                <h1 class="fw-bold text-dark">Pilih Ruangan</h1>
                <p class="text-muted">Silakan pilih ruangan yang ingin Anda pinjam untuk kegiatan atau praktikum.</p>
            </div>

            <!-- SECTION LABORATORIUM -->
            <?php if (!empty($labs)) : ?>
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-flask text-white"></i>
                        </div>
                        <h3 class="fw-bold mb-0">Laboratorium</h3>
                    </div>
                    
                    <div class="row g-4">
                        <?php foreach ($labs as $r) : ?>
                            <?php
                                $roomCode = $r['id_ruangan'];
                                $kapasitas = $r['kapasitas'] ?? '-';
                                $nama = $r['nama_ruangan'];
                                $foto = !empty($r['foto_ruangan']) ? $r['foto_ruangan'] : 'assets/img/no-image.png';
                            ?>
                            <div class="col-xl-4 col-md-6">
                                <div class="text-decoration-none">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                        <div class="position-relative">
                                            <img src="<?= $foto ?>" class="card-img-top" alt="<?= htmlspecialchars($nama) ?>" style="height: 200px; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm">
                                                    <i class="fas fa-users me-1"></i> <?= $kapasitas ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($nama) ?></h5>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-map-marker-alt me-1"></i> Gedung Jurusan Teknologi Informasi
                                            </p>
                                        </div>
                                        <div class="card-footer bg-white border-0 p-4 pt-0">
                                            <a href="index.php?page=pinjam&id_ruangan=<?= $roomCode ?>" class="btn btn-outline-primary w-100 rounded-pill">
                                                Pilih Ruangan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- SECTION NON-LABORATORIUM -->
            <?php if (!empty($nonLabs)) : ?>
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                        <h3 class="fw-bold mb-0">Non-Laboratorium</h3>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($nonLabs as $r) : ?>
                            <?php
                                $roomCode = $r['id_ruangan'];
                                $kapasitas = $r['kapasitas'] ?? '-';
                                $nama = $r['nama_ruangan'];
                                $foto = !empty($r['foto_ruangan']) ? $r['foto_ruangan'] : 'assets/img/no-image.png';
                            ?>
                            <div class="col-xl-4 col-md-6">
                                <a href="index.php?page=pinjam&id_ruangan=<?= $roomCode ?>" class="text-decoration-none">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                        <div class="position-relative">
                                            <img src="<?= $foto ?>" class="card-img-top" alt="<?= htmlspecialchars($nama) ?>" style="height: 200px; object-fit: cover;">
                                            <div class="position-absolute top-0 end-0 m-3">
                                                <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                                    <i class="fas fa-users me-1"></i> <?= $kapasitas ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($nama) ?></h5>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-map-marker-alt me-1"></i> Gedung Jurusan Teknologi Informasi
                                            </p>
                                        </div>
                                        <div class="card-footer bg-white border-0 p-4 pt-0">
                                            <div class="btn btn-outline-success w-100 rounded-pill">
                                                Pilih Ruangan
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($labs) && empty($nonLabs)) : ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada ruangan yang tersedia saat ini.</h4>
                </div>
            <?php endif; ?>

        </div>
    </main>
</div>

<style>
    .rounded-4 {
        border-radius: 1rem !important;
    }
    .card {
        transition: none !important;
    }
</style>