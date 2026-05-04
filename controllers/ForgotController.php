<?php
require_once '../config/Autoloader.php';

$database = new \App\Config\Database();
$db = $database->getConnection();
$user = new \App\Models\User($db);

$email = $_POST['email'] ?? '';

if (!$email) {
    header("Location: ../authentication/Login.php?page=forgot&error=1");
    exit();
}

$query = "SELECT * FROM pengguna WHERE email = :email LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":email", $email);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    header("Location: ../authentication/Login.php?page=forgot&error=email_not_found");
    exit();
}

$token = bin2hex(random_bytes(32));

$user->createResetToken($email, $token);

// Redirect back with success message
header("Location: ../authentication/Login.php?page=forgot&success=1&token=" . urlencode($token));
exit();
