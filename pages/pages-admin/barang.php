<?php
require_once 'config/Database.php';
require_once 'models/Barang.php';
require_once 'models/Ruangan.php';

$database = new Database();
$db = $database->getConnection();
$barang = new Barang($db);
$stmt = $barang->readAll();
$ruangan = new Ruangan($db);
$ruanganStmt = $ruangan->readAll();

$ruanganData = [];
while ($r = $ruanganStmt->fetch(PDO::FETCH_ASSOC)) {
    $ruanganData[] = $r;
}


?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <!-- Title -->
            <h1 class="mt-4">Barang</h1>

            <!-- Action -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Daftar barang yang tersedia</span>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
                    <i class="fas fa-plus me-1"></i> Tambah Barang
                </button>
            </div>

            <!-- Card -->
            <div class="card mb-4">
                <div class="card-body">

                    <!-- Search + Filter -->
                    <div class="d-flex mb-3">
                        <button class="btn btn-light border me-2">
                            <i class="fas fa-filter"></i>
                        </button>
                        <input type="text" class="form-control w-25" placeholder="Search...">
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-muted small fw-bold">
                                <tr class="text-center text-uppercase">
                                    <th><input type="checkbox"></th>
                                    <th>NO</th>
                                    <th class="text-start">NAMA BARANG</th>
                                    <th>RUANGAN</th>
                                    <th>TOTAL</th>
                                    <th>RUSAK</th>
                                    <th>DIPINJAM</th>
                                    <th>TERSEDIA</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td class="text-center"><input type="checkbox"></td>
                                        <td class="text-center"><?= $no++ ?></td>

                                        <td>
                                            <strong><?= htmlspecialchars($row['nama_barang']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($row['deskripsi_barang']) ?></small>
                                        </td>

                                        <td class="text-center"><?= htmlspecialchars($row['id_ruangan']) ?></td>

                                        <td class="text-center"><?= $row['total_stok'] ?></td>
                                        <td class="text-center"><?= $row['stok_rusak'] ?></td>

                                        <!-- hasil dari query -->
                                        <td class="text-center"><?= $row['dipinjam'] ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success">
                                                <?= $row['tersedia'] ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-sm bg-info-subtle text-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editBarangModal"
                                                onclick='fillEditForm(<?= json_encode($row) ?>)'>
                                                Edit
                                            </button>

                                            <a href="controllers/BarangController.php?action=delete&id_barang=<?= $row['id_barang'] ?>"
                                                class="btn btn-sm bg-danger-subtle text-danger"
                                                onclick="return confirm('Yakin ingin menghapus?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">1–2 of 2</small>

                        <div class="d-flex align-items-center">
                            <small class="me-2">Rows per page: 10</small>
                            <button class="btn btn-light btn-sm me-1">&lt;</button>
                            <span>1</span>
                            <button class="btn btn-light btn-sm ms-1">&gt;</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Modal Tambah Barang -->
        <div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="tambahBarangModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-2">
                    <div class="modal-header border-0 pb-0">
                        <h4 class="modal-title text-dark fw-bold" id="tambahBarangModalLabel">Tambah Barang</h4>
                    </div>
                    <div class="modal-body">
                        <form action="controllers/BarangController.php?action=create" method="POST">

                            <div class="mb-3">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" required>
                            </div>

                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea class="form-control" name="deskripsi_barang"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Nama Ruangan</label>
                            </div>
                            <select class="form-control" name="id_ruangan" id="tambah_id_ruangan" required>
                                <option value="">-- Pilih Ruangan --</option>
                                <?php foreach ($ruanganData as $r) { ?>
                                    <option value="<?= $r['id_ruangan'] ?>">
                                        <?= htmlspecialchars($r['nama_ruangan']) ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <div class="mb-4">
                                <label>Total Stok</label>
                                <input type="number" class="form-control" name="total_stok" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Barang -->
        <div class="modal fade" id="editBarangModal" tabindex="-1" aria-labelledby="editBarangModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-2">
                    <div class="modal-header border-0 pb-0">
                        <h4 class="modal-title text-dark fw-bold" id="editBarangModalLabel">Edit Barang</h4>
                    </div>
                    <div class="modal-body">
                        <form action="controllers/BarangController.php?action=update" method="POST">

                            <input type="hidden" name="id_barang" id="edit_id_barang">

                            <div class="mb-3">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" id="edit_nama_barang" required>
                            </div>

                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea class="form-control" name="deskripsi_barang" id="edit_deskripsi"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Nama Ruangan</label>
                            </div>
                            <select class="form-control" name="id_ruangan" id="edit_id_ruangan" required>
                                <option value="">-- Pilih Ruangan --</option>
                                <?php foreach ($ruanganData as $r) { ?>
                                    <option value="<?= $r['id_ruangan'] ?>">
                                        <?= htmlspecialchars($r['nama_ruangan']) ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <div class="mb-3">
                                <label>Total Stok</label>
                                <input type="number" class="form-control" name="total_stok" id="edit_total_stok" required>
                            </div>

                            <div class="mb-4">
                                <label>Stok Rusak</label>
                                <input type="number" class="form-control" name="stok_rusak" id="edit_stok_rusak">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-primary me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        function fillEditForm(data) {
            document.getElementById('edit_id_barang').value = data.id_barang;
            document.getElementById('edit_nama_barang').value = data.nama_barang;
            document.getElementById('edit_deskripsi').value = data.deskripsi_barang;
            document.getElementById('edit_id_ruangan').value = data.id_ruangan;
            document.getElementById('edit_total_stok').value = data.total_stok;
            document.getElementById('edit_stok_rusak').value = data.stok_rusak;
        }
    </script>


    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>