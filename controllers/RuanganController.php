<?php
session_start();
require_once '../config/Database.php';
require_once '../models/Ruangan.php';

$database = new Database();
$db = $database->getConnection();
$ruangan = new Ruangan($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action == 'create') {
        $ruangan->nama_ruangan = $_POST['nama_ruangan'];
        $ruangan->kapasitas = $_POST['kapasitas'];
        $ruangan->fasilitas = $_POST['fasilitas'];
        $ruangan->status = 'tersedia';

        if ($ruangan->create()) {
            header("Location: ../index.php?page=ruangan&success=added");
        } else {
            header("Location: ../index.php?page=ruangan&error=add_failed");
        }
        exit();
    } elseif ($action == 'update') {
        $ruangan->id = $_POST['id'];
        $ruangan->nama_ruangan = $_POST['nama_ruangan'];
        $ruangan->kapasitas = $_POST['kapasitas'];
        $ruangan->fasilitas = $_POST['fasilitas'];
        $ruangan->status = $_POST['status'] ?? 'tersedia';

        if ($ruangan->update()) {
            header("Location: ../index.php?page=ruangan&success=updated");
        } else {
            header("Location: ../index.php?page=ruangan&error=update_failed");
        }
        exit();
    }
} elseif ($action == 'delete') {
    $ruangan->id = $_GET['id'];
    if ($ruangan->delete()) {
        header("Location: ../index.php?page=ruangan&success=deleted");
    } else {
        header("Location: ../index.php?page=ruangan&error=delete_failed");
    }
    exit();
}
?>
