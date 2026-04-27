<?php
class Barang {
    private $conn;
    private $table_name = "barang";

    public $id;
    public $nama_barang;
    public $total;
    public $rusak;
    public $dipinjam;
    public $tersedia;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        // Automatically calculate tersedia
        $this->tersedia = $this->total - $this->rusak - $this->dipinjam;

        $query = "INSERT INTO " . $this->table_name . " SET nama_barang=:nama_barang, total=:total, rusak=:rusak, dipinjam=:dipinjam, tersedia=:tersedia";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_barang", $this->nama_barang);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":rusak", $this->rusak);
        $stmt->bindParam(":dipinjam", $this->dipinjam);
        $stmt->bindParam(":tersedia", $this->tersedia);

        return $stmt->execute();
    }

    public function update() {
        $this->tersedia = $this->total - $this->rusak - $this->dipinjam;

        $query = "UPDATE " . $this->table_name . " SET nama_barang=:nama_barang, total=:total, rusak=:rusak, dipinjam=:dipinjam, tersedia=:tersedia WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_barang", $this->nama_barang);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":rusak", $this->rusak);
        $stmt->bindParam(":dipinjam", $this->dipinjam);
        $stmt->bindParam(":tersedia", $this->tersedia);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
