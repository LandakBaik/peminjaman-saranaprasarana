<?php
require_once 'config/Database.php';
$database = new \App\Config\Database();
$conn = $database->getConnection();

function describeTable($conn, $table) {
    $stmt = $conn->query("DESCRIBE " . $table);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "--- Table: $table ---\n";
    foreach($result as $row) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
}

describeTable($conn, "peminjaman");
describeTable($conn, "detail_peminjaman");
describeTable($conn, "ruangan");
describeTable($conn, "barang");
