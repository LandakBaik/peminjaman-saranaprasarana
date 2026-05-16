<?php

use App\Utils\Router;

// Universal Routes
Router::get('dashboard', [\App\Controllers\DashboardController::class, 'index']);
Router::get('detail-profil', [\App\Controllers\PageController::class, 'detailProfil']);
Router::get('detail-profil-edit', [\App\Controllers\PageController::class, 'detailProfilEdit']);
Router::get('ketentuan', [\App\Controllers\PageController::class, 'ketentuan']);

// Peminjam Routes
Router::get('select-room', [\App\Controllers\PageController::class, 'selectRoom'], ['user']);
Router::get('pinjam', [\App\Controllers\PageController::class, 'pinjam'], ['user']);
Router::get('peminjaman-saya', [\App\Controllers\PageController::class, 'peminjamanSaya'], ['user', 'admin', 'staff']);
Router::get('riwayat-peminjaman', [\App\Controllers\PageController::class, 'riwayatPeminjaman'], ['user', 'admin', 'staff']);

Router::get('approve-peminjaman', [\App\Controllers\PageController::class, 'approvePeminjaman'], ['staff']);
Router::get('pengembalian', [\App\Controllers\PageController::class, 'pengembalian'], ['staff']);
Router::get('riwayat-peminjaman-staff', [\App\Controllers\PageController::class, 'riwayatPeminjamanStaff'], ['staff']);

Router::get('ruangan-barang', [\App\Controllers\PageController::class, 'ruanganBarang'], ['admin']);
Router::get('daftar-akun', [\App\Controllers\PageController::class, 'daftarAkun'], ['admin']);
Router::get('akun-staff', [\App\Controllers\PageController::class, 'akunStaff'], ['admin']);
Router::get('riwayat-peminjaman-admin', [\App\Controllers\PageController::class, 'riwayatPeminjamanAdmin'], ['admin']);
