<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Peminjaman;

class DashboardController
{
    public function index()
    {
        // Ambil role dan user
        $currentRole = isset($_SESSION['user']['role'])
            ? strtolower($_SESSION['user']['role'])
            : '';

        $userId = isset($_SESSION['user']['id'])
            ? $_SESSION['user']['id']
            : null;

        // Filter dashboard
        $activeFilter = $_GET['filter'] ?? 'daily';

        $allowedFilters = [
            'daily',
            'weekly',
            'monthly',
            'yearly'
        ];

        if (!in_array($activeFilter, $allowedFilters)) {
            $activeFilter = 'daily';
        }

        // Koneksi database
        $database = new Database();
        $db = $database->getConnection();

        $peminjamanModel = new Peminjaman($db);

        // Data statistik
        $stats = $peminjamanModel->getStats(
            $currentRole,
            $userId,
            $activeFilter
        );

        // Data peminjaman terbaru
        $recent = $peminjamanModel->getRecent(
            $currentRole,
            $userId,
            5,
            $activeFilter
        );

        // Data trend chart
        $trendData = $peminjamanModel->getTrendData(
            $currentRole,
            $userId,
            $activeFilter
        );

        // Load halaman dashboard
        include 'pages/dashboard.php';
    }

    public function getStatsDetails()
    {
        // Response JSON
        header('Content-Type: application/json');

        // Ambil role dan user
        $currentRole = isset($_SESSION['user']['role'])
            ? strtolower($_SESSION['user']['role'])
            : '';

        $userId = isset($_SESSION['user']['id'])
            ? $_SESSION['user']['id']
            : null;

        // Filter dashboard
        $activeFilter = $_GET['filter'] ?? 'daily';

        $allowedFilters = [
            'daily',
            'weekly',
            'monthly',
            'yearly'
        ];

        if (!in_array($activeFilter, $allowedFilters)) {
            $activeFilter = 'daily';
        }

        // Status detail
        $statusType = $_GET['status'] ?? 'total';

        // Koneksi database
        $database = new Database();
        $db = $database->getConnection();

        $peminjamanModel = new Peminjaman($db);

        // Ambil detail statistik
        $list = $peminjamanModel->getStatsDetailsList(
            $currentRole,
            $userId,
            $activeFilter,
            $statusType
        );

        echo json_encode($list);

        exit();
    }
}