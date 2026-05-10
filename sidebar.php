<?php
// Cek apakah session user ada dan role terdefinisi
$role = isset($_SESSION['user']['role']) ? strtolower($_SESSION['user']['role']) : '';
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Main</div>
                <a class="nav-link" href="index.php?page=dashboard">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Features</div>

                <!-- user -->
                 <?php if ($role === 'user') : ?>
                <a class="nav-link" href="index.php?page=select-room">
                    <div class="sb-nav-link-icon"><i class="fas fa-door-open"></i></div>
                    Pinjam
                </a>
                <a class="nav-link" href="index.php?page=peminjaman-saya">
                    <div class="sb-nav-link-icon"><i class="fa-regular fa-clipboard"></i></div>
                    Peminjaman Saya
                </a>
                <?php endif; ?>

                <!-- admin -->

                <?php if (in_array($role, ['admin'])) : ?>
                <a class="nav-link" href="index.php?page=ruangan">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-door-open"></i></div>
                    Ruangan
                </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin'])) : ?>
                <a class="nav-link" href="index.php?page=barang">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-box-open"></i></div>
                    Barang
                </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin'])) : ?>
                <a class="nav-link" href="index.php?page=daftar-akun">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Daftar Akun
                </a>
                <?php endif; ?>

                <!-- staff -->
                <?php if (in_array($role, ['staff'])) : ?>
                <a class="nav-link" href="index.php?page=approve-peminjaman">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-check"></i></div>
                    Approve Peminjaman
                </a>
                <?php endif; ?>

                <?php if (in_array($role, ['staff'])) : ?>
                <a class="nav-link" href="index.php?page=pengembalian">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-rotate-left"></i></div>
                    Pengembalian
                </a>
                <?php endif; ?>

                <?php if (in_array($role, ['staff'])) : ?>
                <a class="nav-link" href="index.php?page=riwayat-peminjaman-staff">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bookmark"></i></div>
                    Riwayat Peminjaman
                </a>
                <?php endif; ?>

                <?php if (in_array($role, ['admin'])) : ?>
                <a class="nav-link" href="index.php?page=riwayat-peminjaman-admin">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bookmark"></i></div>
                    Riwayat Peminjaman
                </a>
                <?php endif; ?>

                <!-- Select menu dropdown layout dan pages auth, sapa tau butuh jangan diapus -->

                <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Layouts
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="index.php?page=layout-static">Static Navigation</a>
                        <a class="nav-link" href="index.php?page=layout-sidenav-light">Light Sidenav</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Pages
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Authentication
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="login.html">Login</a>
                                <a class="nav-link" href="register.html">Register</a>
                                <a class="nav-link" href="password.html">Forgot Password</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                            Error
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="401.html">401 Page</a>
                                <a class="nav-link" href="404.html">404 Page</a>
                                <a class="nav-link" href="500.html">500 Page</a>
                            </nav>
                        </div>
                    </nav>
                </div> -->
                <div class="sb-sidenav-menu-heading">Settings</div>

                <?php if (in_array($role, ['user', 'admin', 'staff'])) : ?>
                <a class="nav-link" href="index.php?page=detail-profil">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                    User Profile
                </a>
                <?php endif; ?>

                <!-- Chart dan Table pages sementara yang mungkin butuh nanti, jangan diapus -->

                <!-- <a class="nav-link" href="index.php?page=charts">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Charts
                </a>
                <a class="nav-link" href="index.php?page=tables">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Tables
                </a> -->
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php echo $_SESSION['user']['nama'] ?? 'Guest'; ?>
        </div>
    </nav>
</div>