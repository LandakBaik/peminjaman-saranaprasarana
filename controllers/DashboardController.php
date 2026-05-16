<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Peminjaman;

class DashboardController
{
    public function index()
    {
        // Ensure session and role are available
        $currentRole = isset($_SESSION['user']['role']) ? strtolower($_SESSION['user']['role']) : '';
        $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

        // Filter logic
        $activeFilter = $_GET['filter'] ?? 'daily';
        $allowedFilters = ['daily', 'weekly', 'monthly', 'yearly'];
        if (!in_array($activeFilter, $allowedFilters)) {
            $activeFilter = 'daily';
        }

        $database = new Database();
        $db = $database->getConnection();
        $peminjamanModel = new Peminjaman($db);

        // Fetch Stats based on role
        $stats = $peminjamanModel->getStats($currentRole, $userId, $activeFilter);

        // Fetch Recent Peminjaman based on role
        $recent = $peminjamanModel->getRecent($currentRole, $userId, 5, $activeFilter);

        // Fetch Trend Data for Line Chart
        $trendData = $peminjamanModel->getTrendData($currentRole, $userId, $activeFilter);

        // Load the view
        include 'pages/dashboard.php';
    }
}
