<?php
require_once 'config/Database.php';
require_once 'models/Barang.php';

$database = new Database();
$db = $database->getConnection();
$barang = new Barang($db);
$stmt = $barang->readAll();
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
                                    <th>NO <i class="fas fa-sort ms-1"></i></th>
                                    <th class="text-start">NAMA BARANG <i class="fas fa-sort ms-1"></i></th>
                                    <th>TOTAL</th>
                                    <th>RUSAK</th>
                                    <th>DIPINJAM</th>
                                    <th>TERSEDIA</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr class="align-middle">
                                    <td class="text-center"><input type="checkbox"></td>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($row['nama_barang']) ?></strong></td>
                                    <td class="text-center"><?= htmlspecialchars($row['total']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['rusak']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['dipinjam']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['tersedia']) ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm bg-info-subtle text-info fw-semibold px-3 border-0" data-bs-toggle="modal" data-bs-target="#editBarangModal" onclick="fillEditForm(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
                                        <a href="controllers/BarangController.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-sm bg-danger-subtle text-danger fw-semibold px-3 border-0 ms-1" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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
                                <label class="form-label text-muted fw-semibold">Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" placeholder="Laptop" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted fw-semibold">Kuantitas (Total)</label>
                                <input type="number" class="form-control" name="total" placeholder="Harus angka" required>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-end align-items-center pt-2">
                                <button type="button" class="btn btn-outline-primary px-4 me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4">Kirim</button>
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
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold">Nama Barang</label>
                                <input type="text" class="form-control" name="nama_barang" id="edit_nama_barang" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold">Total</label>
                                <input type="number" class="form-control" name="total" id="edit_total" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold">Rusak</label>
                                <input type="number" class="form-control" name="rusak" id="edit_rusak">
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-muted fw-semibold">Dipinjam</label>
                                <input type="number" class="form-control" name="dipinjam" id="edit_dipinjam">
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-end align-items-center pt-2">
                                <button type="button" class="btn btn-outline-primary px-4 me-2" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </main>

    <script>
        function fillEditForm(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_nama_barang').value = data.nama_barang;
            document.getElementById('edit_total').value = data.total;
            document.getElementById('edit_rusak').value = data.rusak;
            document.getElementById('edit_dipinjam').value = data.dipinjam;
        }
    </script>


    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>