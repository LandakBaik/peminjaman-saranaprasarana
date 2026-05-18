<?php

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config/Autoloader.php';
require 'middleware/auth.php';
require 'middleware/role.php';

$role = isset($_SESSION['user']['role']) ? strtolower($_SESSION['user']['role']) : '';
$page = $_GET['page'] ?? 'dashboard';

// Bypass HTML layout for API/AJAX requests
if ($page === 'dashboard-stats-details') {
    require 'routes/web.php';
    \App\Utils\Router::dispatch($page);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="sb-nav-fixed">
    <?php include 'navbar.php'; ?>
    <div id="layoutSidenav">
        <?php
        include 'sidebar.php';

        // Load Routes
        require 'routes/web.php';

        // Dispatch current page
        $page = $_GET['page'] ?? 'dashboard';
        \App\Utils\Router::dispatch($page);
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js?v=<?= time() ?>"></script>
    <script src="js/temperature.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/peminjaman-filter.js?v=<?= time() ?>"></script>
    <script src="js/barang-filter.js"></script>
    <script src="js/user-filter.js?v=<?= time() ?>"></script>
    <script src="js/ruangan-filter.js?v=<?= time() ?>"></script>
    <script src="js/riwayat-filter.js?v=<?= time() ?>"></script>
    <!-- <script src="assets/demo/chart-area-demo.js"></script> -->
    <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
    <!-- <script src="assets/demo/chart-pie-demo.js"></script> -->
</body>

</html>