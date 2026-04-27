<?php
require_once 'config/Database.php';
require_once 'models/Ruangan.php';

$database = new Database();
$db = $database->getConnection();

$ruanganModel = new Ruangan($db);
$stmt = $ruanganModel->readAll();
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4 mb-4 fw-semibold text-dark">Daftar Ruangan</h1>

            <div class="row g-4">

                <?php while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>

                    <?php
                        // bikin kode room otomatis (kalau id kamu RG001 dll)
                        $roomCode = $r['id_ruangan'];

                        // mapping kapasitas kalau ada
                        $kapasitas = $r['kapasitas'] ?? '-';
                        $nama = $r['nama_ruangan'];
                    ?>

                    <div class="col-md-4">
                        <a href="index.php?page=pinjam&id_ruangan=<?= $roomCode ?>" class="text-decoration-none text-dark">
                            <div class="card shadow-sm h-100 border-1">
                                <div class="card-body">

                                    <div class="d-flex justify-content-between align-items-start">

                                        <div>
                                            <h5 class="fw-semibold mb-1">
                                                <?= htmlspecialchars($nama) ?>
                                            </h5>
                                            <small class="text-muted">Lantai 3</small>
                                        </div>

                                        <div class="text-end">
                                            <div class="fs-5 fw-semibold">
                                                <?= $kapasitas ?>
                                            </div>
                                            <small class="text-muted">Kapasitas</small>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>

                <?php endwhile; ?>

            </div>
        </div>
    </main>
</div>