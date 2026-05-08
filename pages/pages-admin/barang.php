<?php
// require_once '../../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$barang = new \App\Models\Barang($db);
$stmt = $barang->readAll();
$ruangan = new \App\Models\Ruangan($db);
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

                <span class="text-muted">
                    Daftar barang yang tersedia
                </span>

                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#tambahBarangModal">

                    <i class="fas fa-plus me-1"></i>
                    Tambah Barang
                </button>

            </div>

            <!-- Card -->
            <div class="card mb-4">

                <div class="card-body">

                    <!-- Search + Filter -->
                    <div class="d-flex mb-3">

                        <!-- Filter Button -->
                        <button
                            class="btn btn-light border me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#filterBarangModal">

                            <i class="fas fa-filter"></i>
                        </button>

                        <!-- Search -->
                        <input
                            type="text"
                            class="form-control w-25"
                            placeholder="Search..."
                            id="searchBarang">
                    </div>

                    <!-- Filter Modal -->
                    <div
                        class="modal fade"
                        id="filterBarangModal"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0">

                                    <h5 class="modal-title fw-bold">
                                        Filter Barang
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <!-- Filter Ruangan -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Ruangan
                                        </label>

                                        <select
                                            class="form-select"
                                            id="filterRuangan">

                                            <option value="">
                                                Semua
                                            </option>

                                            <?php foreach ($ruanganData as $r) { ?>

                                                <option value="<?= strtolower($r['nama_ruangan']) ?>">
                                                    <?= htmlspecialchars($r['nama_ruangan']) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- Filter Stok -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Status Stok
                                        </label>

                                        <select
                                            class="form-select"
                                            id="filterStok">

                                            <option value="">
                                                Semua
                                            </option>

                                            <option value="tersedia">
                                                Tersedia
                                            </option>

                                            <option value="habis">
                                                Habis
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer border-0">

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        id="resetBarangFilter">
                                        Reset
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        id="applyBarangFilter"
                                        data-bs-dismiss="modal">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">

                        <table
                            class="table table-bordered align-middle"
                            id="barangTable">

                            <thead class="table-light text-muted small fw-bold">
                                <tr class="text-center text-uppercase">
                                    <th>
                                        <input
                                            type="checkbox"
                                            id="selectAllBarang">
                                    </th>

                                    <th>NO</th>

                                    <th class="text-start">
                                        NAMA BARANG
                                    </th>
                                    <th>RUANGAN</th>
                                    <th>TOTAL</th>
                                    <th>RUSAK</th>
                                    <th>DIPINJAM</th>
                                    <th>TERSEDIA</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>

                            <tbody id="barangBody">
                                <?php
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr
                                        data-ruangan="<?= strtolower(htmlspecialchars($row['nama_ruangan'] ?? '')) ?>"
                                        data-tersedia="<?= $row['tersedia'] > 0 ? 'tersedia' : 'habis' ?>">
                                        <!-- Checkbox -->
                                        <td class="text-center">
                                            <input
                                                type="checkbox"
                                                class="row-checkbox">
                                        </td>

                                        <!-- No -->
                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>

                                        <!-- Nama Barang -->
                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($row['nama_barang']) ?>
                                            </strong>

                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($row['deskripsi_barang']) ?>
                                            </small>
                                        </td>

                                        <!-- Ruangan -->
                                        <td class="text-center">
                                            <?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?>
                                        </td>

                                        <!-- Total -->
                                        <td class="text-center">
                                            <?= $row['total_stok'] ?>
                                        </td>

                                        <!-- Rusak -->
                                        <td class="text-center">
                                            <?= $row['stok_rusak'] ?>
                                        </td>

                                        <!-- Dipinjam -->
                                        <td class="text-center">
                                            <?= $row['dipinjam'] ?>
                                        </td>

                                        <!-- Tersedia -->
                                        <td class="text-center">

                                            <span class="badge bg-success">
                                                <?= $row['tersedia'] ?>
                                            </span>
                                        </td>
                                        <!-- Action -->
                                        <td class="text-center">
                                            <!-- Edit -->
                                            <button
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editBarangModal"
                                                onclick='fillEditForm(<?= json_encode($row) ?>)'>
                                                Edit
                                            </button>

                                            <!-- Delete -->
                                            <a
                                                href="controllers/BarangController.php?action=delete&id_barang=<?= $row['id_barang'] ?>"
                                                class="btn btn-danger btn-sm"
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

                        <small
                            class="text-muted"
                            id="rowCountBarang">
                            0 of 0
                        </small>

                        <div class="d-flex align-items-center">
                            <small class="me-2">
                                Rows per page: 10
                            </small>

                            <button class="btn btn-light btn-sm me-1">
                                &lt;
                            </button>
                            <span>1</span>

                            <button class="btn btn-light btn-sm ms-1">
                                &gt;
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Barang -->
        <div
            class="modal fade"
            id="tambahBarangModal"
            tabindex="-1"
            aria-labelledby="tambahBarangModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-2">
                    <div class="modal-header border-0 pb-0">
                        <h4
                            class="modal-title text-dark fw-bold"
                            id="tambahBarangModalLabel">
                            Tambah Barang
                        </h4>
                    </div>

                    <div class="modal-body">
                        <form
                            action="controllers/BarangController.php?action=create"
                            method="POST">

                            <div class="mb-3">
                                <label>Nama Barang</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_barang"
                                    required>

                            </div>

                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea
                                    class="form-control"
                                    name="deskripsi_barang"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Nama Ruangan</label>
                                <select
                                    class="form-control"
                                    name="id_ruangan"
                                    required>

                                    <option value="">
                                        -- Pilih Ruangan --
                                    </option>
                                    <?php foreach ($ruanganData as $r) { ?>
                                        <option value="<?= $r['id_ruangan'] ?>">
                                            <?= htmlspecialchars($r['nama_ruangan']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label>Total Stok</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="total_stok"
                                    required>
                            </div>

                            <div class="d-flex justify-content-end">

                                <button
                                    type="button"
                                    class="btn btn-outline-primary me-2"
                                    data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-primary">
                                    Kirim
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Barang -->
        <div
            class="modal fade"
            id="editBarangModal"
            tabindex="-1"
            aria-labelledby="editBarangModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-2">
                    <div class="modal-header border-0 pb-0">
                        <h4
                            class="modal-title text-dark fw-bold"
                            id="editBarangModalLabel">
                            Edit Barang
                        </h4>

                    </div>

                    <div class="modal-body">

                        <form
                            action="controllers/BarangController.php?action=update"
                            method="POST">

                            <input
                                type="hidden"
                                name="id_barang"
                                id="edit_id_barang">

                            <div class="mb-3">

                                <label>Nama Barang</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="nama_barang"
                                    id="edit_nama_barang"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label>Deskripsi</label>

                                <textarea
                                    class="form-control"
                                    name="deskripsi_barang"
                                    id="edit_deskripsi"></textarea>

                            </div>

                            <div class="mb-3">
                                <label>Nama Ruangan</label>
                                <select
                                    class="form-control"
                                    name="id_ruangan"
                                    id="edit_id_ruangan"
                                    required>
                                    <option value="">
                                        -- Pilih Ruangan --
                                    </option>
                                    <?php foreach ($ruanganData as $r) { ?>
                                        <option value="<?= $r['id_ruangan'] ?>">
                                            <?= htmlspecialchars($r['nama_ruangan']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Total Stok</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="total_stok"
                                    id="edit_total_stok"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label>Stok Rusak</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="stok_rusak"
                                    id="edit_stok_rusak">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button
                                    type="button"
                                    class="btn btn-outline-primary me-2"
                                    data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Fill Edit Form -->
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