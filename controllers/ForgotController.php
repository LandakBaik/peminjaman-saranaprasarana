<?php

require_once '../config/Autoloader.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Koneksi database
$database = new \App\Config\Database();
$db = $database->getConnection();

$user = new \App\Models\User($db);

$email = trim($_POST['email'] ?? '');

// Validasi email kosong
if (empty($email)) {

    header("Location: ../authentication/Login.php?page=forgot&error=empty_email");

    exit();
}

$mailerConfig = require '../config/mailer.php';

try {

    // Cari user
    $query = "
        SELECT * FROM pengguna 
        WHERE email = :email 
        LIMIT 1
    ";

    $stmt = $db->prepare($query);

    $stmt->bindParam(':email', $email);

    $stmt->execute();

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Tetap tampil sukses
    if ($userData) {

        // Hapus token lama
        $delete = $db->prepare("
            DELETE FROM password_resets
            WHERE email = :email
        ");

        $delete->bindParam(':email', $email);

        $delete->execute();

        // Generate token reset
        $token = bin2hex(random_bytes(32));

        $user->createResetToken(
            $email,
            $token
        );

        // Generate reset link
        $protocol =
            (!empty($_SERVER['HTTPS']) &&
                $_SERVER['HTTPS'] !== 'off')
            ? "https"
            : "http";

        $host = $_SERVER['HTTP_HOST'];

        $baseUrl =
            $protocol .
            "://" .
            $host .
            dirname(dirname($_SERVER['PHP_SELF']));

        $resetLink =
            $baseUrl .
            "/authentication/Login.php?page=reset&token=" .
            urlencode($token);

        // Konfigurasi mail
        $mail = new PHPMailer(true);

        $mail->isSMTP();

        $mail->Host =
            $mailerConfig['host'];

        $mail->SMTPAuth = true;

        $mail->AuthType = 'LOGIN';

        $mail->Username =
            trim($mailerConfig['username']);

        $mail->Password =
            trim($mailerConfig['password']);

        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port =
            (int) $mailerConfig['port'];

        $mail->Timeout = 15;

        // Sender email
        $mail->setFrom(
            $mailerConfig['from_email'],
            $mailerConfig['from_name']
        );

        // Penerima email
        $mail->addAddress(
            $email,
            $userData['nama']
        );

        $mail->isHTML(true);

        $mail->Subject =
            'Reset Password - Sistem Peminjaman';

        // Isi email
        $mail->Body = "

        <div style='font-family:Arial'>

            <h2>
            Halo {$userData['nama']}
            </h2>

            <p>
            Kami menerima permintaan reset password akun Anda.
            </p>

            <p>
            Klik tombol berikut:
            </p>

            <p>

            <a href='{$resetLink}'
            style='
            display:inline-block;
            padding:10px 20px;
            background:#0F2854;
            color:#fff;
            text-decoration:none;
            border-radius:6px;
            '>

            Reset Password

            </a>

            </p>

            <p>
            Link berlaku selama 1 jam.
            </p>

            <p>

            Jika tombol tidak bekerja:

            <br><br>

            <a href='{$resetLink}'>
            {$resetLink}
            </a>

            </p>

            <br>

            <p>
            Abaikan email ini jika Anda tidak meminta reset password.
            </p>

            <p>
            Sistem Peminjaman
            </p>

        </div>

        ";

        // Kirim email
        $mail->send();
    }

    header(
        "Location: ../authentication/Login.php?page=forgot&success=1"
    );

    exit();

} catch (Exception $e) {

    die(
        "MAIL ERROR:<br><br>"
        . $mail->ErrorInfo
        . "<br><br>"
        . $e->getMessage()
    );
}