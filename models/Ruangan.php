<?php
class Ruangan {
    private $conn;
    private $table_name = "ruangan";

    public $id;
    public $nama_ruangan;
    public $kapasitas;
    public $fasilitas;
    public $status;

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
        $query = "INSERT INTO " . $this->table_name . " SET nama_ruangan=:nama_ruangan, kapasitas=:kapasitas, fasilitas=:fasilitas, status=:status";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_ruangan", $this->nama_ruangan);
        $stmt->bindParam(":kapasitas", $this->kapasitas);
        $stmt->bindParam(":fasilitas", $this->fasilitas);
        $stmt->bindParam(":status", $this->status);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_ruangan=:nama_ruangan, kapasitas=:kapasitas, fasilitas=:fasilitas, status=:status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_ruangan", $this->nama_ruangan);
        $stmt->bindParam(":kapasitas", $this->kapasitas);
        $stmt->bindParam(":fasilitas", $this->fasilitas);
        $stmt->bindParam(":status", $this->status);
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
