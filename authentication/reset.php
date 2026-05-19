<?php
require_once '../config/Autoloader.php';

// Koneksi database
$database = new \App\Config\Database();
$db = $database->getConnection();
$user = new \App\Models\User($db);

// Ambil token reset
$token = $_GET['token'] ?? '';
$data = $user->findToken($token);

if (!$data):
?>

    <!-- Token tidak valid -->
    <div class="alert alert-danger text-center small">
        Token tidak valid atau sudah expired!
    </div>

    <p class="text-center small">
        <a href="Login.php?page=forgot">Kembali ke Forgot Password</a>
    </p>

<?php else: ?>

    <!-- Pesan error -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_match'): ?>
        <div class="alert alert-danger text-center small">
            Password tidak cocok!
        </div>

    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'password_too_short'): ?>
        <div class="alert alert-danger text-center small">
            Password harus minimal 6 karakter!
        </div>

    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'password_not_alnum'): ?>
        <div class="alert alert-danger text-center small">
            Password hanya boleh berisi huruf dan angka!
        </div>
    <?php endif; ?>

    <!-- Form reset password -->
    <div class="text-center mb-4">
        <img src="../assets/img/pinjam_jti-removebg-preview-1.png"
            class="img-fluid mb-3"
            style="max-width:220px;">

        <p class="fw-bold">Atur Password Baru</p>
    </div>

    <form method="POST" action="../controllers/ResetController.php">

        <input type="hidden" name="email" value="<?= $data['email'] ?>">

        <div class="mb-3">
            <label class="form-label">Password Baru</label>

            <input type="password"
                class="form-control"
                name="password"
                id="password"
                minlength="6"
                pattern="[a-zA-Z0-9]+"
                required>

            <div class="invalid-feedback">
                Password harus minimal 6 karakter dan hanya berisi huruf/angka!
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>

            <input type="password"
                class="form-control"
                name="confirm_password"
                id="confirm_password"
                required>

            <div class="invalid-feedback">
                Password tidak cocok!
            </div>
        </div>

        <button type="submit"
            class="btn btn-primary w-100 rounded-pill"
            style="background-color: #0F2854;">

            Reset Password
        </button>

        <p class="text-center small mt-3">
            <a href="Login.php?page=login">Kembali ke Login</a>
        </p>
    </form>

    <script>

        // Validasi form
        document.querySelector('form').addEventListener('submit', function(e) {

            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            const alnumRegex = /^[a-zA-Z0-9]+$/;

            let hasError = false;

            // Validasi password
            if (password.length < 6 || !alnumRegex.test(password)) {

                e.preventDefault();

                document
                    .getElementById('password')
                    .classList.add('is-invalid');

                hasError = true;

            } else {

                document
                    .getElementById('password')
                    .classList.remove('is-invalid');
            }

            // Validasi konfirmasi password
            if (password !== confirm) {

                e.preventDefault();

                document
                    .getElementById('confirm_password')
                    .classList.add('is-invalid');

                hasError = true;

            } else {

                document
                    .getElementById('confirm_password')
                    .classList.remove('is-invalid');
            }
        });

        // Hapus invalid password
        document.getElementById('password')
            .addEventListener('input', function() {

                const alnumRegex = /^[a-zA-Z0-9]+$/;

                if (
                    this.value.length >= 6 &&
                    alnumRegex.test(this.value)
                ) {
                    this.classList.remove('is-invalid');
                }
            });

        // Hapus invalid confirm password
        document.getElementById('confirm_password')
            .addEventListener('input', function() {

                const password =
                    document.getElementById('password').value;

                if (this.value === password) {
                    this.classList.remove('is-invalid');
                }
            });

    </script>

<?php endif; ?>