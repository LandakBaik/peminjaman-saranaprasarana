<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Sistem Informasi Peminjaman Sarana dan Prasarana" />
        <meta name="author" content="" />
        <title>Autentikasi - PinjamJTI</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="bg-light">

        <div class="container-fluid">
            <div class="row min-vh-100">

                <!-- Left Panel -->
                <div class="col-lg-5 d-flex flex-column align-items-center justify-content-center bg-white shadow-sm">
                    <div class="w-75">
                        <?php
                        $page = $_GET['page'] ?? 'login';

                        switch ($page) {
                            case 'forgot':
                                include 'form-forgot.php';
                                break;
                            case 'register':
                                include 'form-register.php';
                                break;
                            case 'reset':
                                include 'reset.php';
                                break;
                            default:
                                include 'form-login.php';
                                break;
                        }
                        ?>
                    </div>
                </div>

                <?php include 'right-panel.php'; ?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <!-- loading -->
        <?php
        require_once '../config/Autoloader.php';
        $loader = new \App\Utils\LoadingScreen("Memproses...", "Mohon Tunggu Sebentar");
        $loader->render();
        ?>
    </body>
</html>
