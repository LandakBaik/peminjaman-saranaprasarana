<?php
require_once '../config/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$token = $_GET['token'] ?? '';
$data = $user->findToken($token);

if (!$data):
?>

    <div class="alert alert-danger text-center small">
        Token tidak valid atau sudah expired!
    </div>

    <p class="text-center small">
        <a href="Login.php?page=forgot">Kembali ke Forgot Password</a>
    </p>

<?php else: ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_match'): ?>
        <div class="alert alert-danger text-center small">
            Password tidak cocok!
        </div>
    <?php endif; ?>

    <div class="text-center mb-4">
        <img src="../assets/img/pinjam_jti-removebg-preview-1.png" class="img-fluid mb-3" style="max-width:220px;">
        <p class="fw-bold">Atur Password Baru</p>
    </div>

    <form method="POST" action="../controllers/ResetController.php">
        <input type="hidden" name="email" value="<?= $data['email'] ?>">

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
            <div class="invalid-feedback">
                Password tidak cocok!
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-pill"
            style="background-color: #0F2854;">
            Reset Password
        </button>

        <p class="text-center small mt-3">
            <a href="Login.php?page=login">Kembali ke Login</a>
        </p>
    </form>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (password !== confirm) {
                e.preventDefault();
                document.getElementById('confirm_password').classList.add('is-invalid');
            }
        });

        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;

            if (this.value === password) {
                this.classList.remove('is-invalid');
            }
        });
    </script>

<?php endif; ?>