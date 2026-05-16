<?php

use App\Utils\Router;

// Universal Routes
Router::get('dashboard', 'pages/dashboard.php');
Router::get('detail-profil', 'pages/detail-profil.php');
Router::get('detail-profil-edit', 'pages/detail-profil-edit.php');
Router::get('ketentuan', 'pages/ketentuan.php');

// Peminjam Routes
Router::get('select-room', 'pages/pages-peminjam/select-room.php', ['user']);
Router::get('pinjam', 'pages/pages-peminjam/pinjam.php', ['user']);
Router::get('peminjaman-saya', 'pages/pages-peminjam/peminjaman-saya.php', ['user', 'admin', 'staff']);
Router::get('riwayat-peminjaman', 'pages/pages-peminjam/riwayat-peminjaman.php', ['user', 'admin', 'staff']);

// Staff Routes
Router::get('approve-peminjaman', 'pages/pages-staff/approve.php', ['staff']);
Router::get('pengembalian', 'pages/pages-staff/pengembalian.php', ['staff']);
Router::get('riwayat-peminjaman-staff', 'pages/pages-staff/riwayat-peminjaman.php', ['staff']);

// Admin Routes
Router::get('ruangan-barang', 'pages/pages-admin/ruangan-barang.php', ['admin']);
Router::get('daftar-akun', 'pages/pages-admin/daftar-akun.php', ['admin']);
Router::get('akun-staff', 'pages/pages-admin/akun-staff.php', ['admin']);
Router::get('riwayat-peminjaman-admin', 'pages/pages-admin/riwayat-peminjaman.php', ['admin']);
