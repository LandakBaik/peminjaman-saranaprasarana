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
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>
        <div id="layoutSidenav">
            <?php
            include 'sidebar.php';

            $page = $_GET['page'] ?? 'dashboard';

            switch ($page) {
                case 'layout-sidenav-light':
                    include 'pages/layout-sidenav-light.php';
                    break;
                case 'layout-static':
                    include 'pages/layout-static.php';
                    break;
                case 'tables':
                    include 'pages/tables.php';
                    break;
                case 'select-room':
                    include 'pages/select-room.php';
                    break;
                case 'pinjam':
                    include 'pages/pinjam.php';
                    break;
                case 'peminjaman-saya':
                    include 'pages/peminjaman-saya.php';
                    break;
                default:
                    include 'pages/dashboard.php';
            }
            ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <!-- <script src="assets/demo/chart-area-demo.js"></script> -->
        <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script src="assets/demo/calender-demo.js"></script>
        <!-- <script src="assets/demo/chart-pie-demo.js"></script> -->
    </body>
</html>
