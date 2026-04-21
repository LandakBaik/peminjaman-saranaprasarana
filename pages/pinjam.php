<div id="layoutSidenav_content">
    <link href="css/pinjam-custom.css" rel="stylesheet" />
    <main id="layout-static">
        <div class="container-fluid px-4">
            <h1 class="mt-4">Pinjam</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Pinjam</li>
            </ol>
            <div class="card mb-4">
                <div class="calendar-container">
                    <div class="calendar-header">
                        <button id="prevMonth">&#10094;</button>

                        <div class="calendar-title">
                            <select id="monthSelect"></select>
                            <select id="yearSelect"></select>
                        </div>

                        <button id="nextMonth">&#10095;</button>
                    </div>

                    <div class="calendar-days-header">
                        <div>Minggu</div>
                        <div>Senin</div>
                        <div>Selasa</div>
                        <div>Rabu</div>
                        <div>Kamis</div>
                        <div>Jumat</div>
                        <div>Sabtu</div>
                    </div>

                    <div id="calendarDates" class="calendar-grid"></div>
                </div>

                <!-- Popup Form Peminjaman -->
                <div id="loanModal" class="modal">
                    <div class="modal-content large-modal">
                        <span class="close">&times;</span>

                        <h1 class="room-title">Ruangan 3.11</h1>
                        <div class="section-title">
                            <span></span>
                            <h3>Data Peminjaman</h3>
                            <span></span>
                        </div>

                        <form class="row g-3">
                            <div class="col-12 col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">JENIS PEMINJAMAN</label>
                                    <select class="form-select" id="jenismPinjam">
                                        <option selected>Pilih</option>
                                        <option>Barang</option>
                                        <option>Ruangan</option>
                                    </select>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Nama Peminjam</label>
                                        <input type="text" class="form-control" placeholder="Nama peminjam">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Keperluan</label>
                                        <input type="text" class="form-control" placeholder="Keperluan peminjaman">
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Tanggal Pinjam</label>
                                        <input type="date" class="form-control">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" class="form-control">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Daftar Barang</label>
                                    <div class="table-wrapper">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 40px;"><input class="form-check-input" type="checkbox" id="selectAll"></th>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th style="width: 80px;">Jumlah</th>
                                                    <th style="width: 80px;">Tersedia</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201001</td>
                                                    <td>Kursi Besi</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>25</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201002</td>
                                                    <td>AC Split</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>4</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201003</td>
                                                    <td>White Board</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>1</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201001</td>
                                                    <td>Kursi Besi</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>25</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201002</td>
                                                    <td>AC Split</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>4</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201003</td>
                                                    <td>White Board</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>1</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201001</td>
                                                    <td>Kursi Besi</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>25</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201002</td>
                                                    <td>AC Split</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>4</td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-check-input" type="checkbox"></td>
                                                    <td>3050201003</td>
                                                    <td>White Board</td>
                                                    <td><input type="number" min="1" value="1" class="form-control form-control-sm"></td>
                                                    <td>1</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Catatan</label>
                                    <textarea class="form-control" rows="4" placeholder="Catatan..."></textarea>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="image-placeholder mb-3"></div>

                                <p class="room-description text-muted small mb-3">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempat ini dapat digunakan untuk kebutuhan peminjaman ruangan.
                                </p>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Jaminan</label>
                                    <div class="upload-box">
                                        <input type="file" class="d-none" id="fileInput">
                                        <label for="fileInput" class="d-block mb-0" style="cursor: pointer;">
                                            <small>Klik untuk upload file</small>
                                        </label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="reset" class="btn btn-outline-secondary">Bersihkan</button>
                                    <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</div>