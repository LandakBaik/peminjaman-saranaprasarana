<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth/Login.php?page=login");
    exit;
}
