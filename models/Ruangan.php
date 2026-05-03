<?php
class Ruangan
{
    private $conn;
    private $table_name = "ruangan";

    public $id_ruangan;
    public $nama_ruangan;
    public $kapasitas;
    public $tipe_ruangan;
    public $foto_ruangan;
    public $id_pengguna;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // READ ALL
    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function create()
    {
        // $this->id_ruangan = $this->generateId();

        $query = "INSERT INTO " . $this->table_name . "
              SET nama_ruangan = :nama_ruangan,
                  kapasitas = :kapasitas,
                  tipe_ruangan = :tipe_ruangan,
                  foto_ruangan = :foto_ruangan,
                  id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_ruangan", $this->nama_ruangan);
        $stmt->bindParam(":kapasitas", $this->kapasitas);
        $stmt->bindParam(":tipe_ruangan", $this->tipe_ruangan);
        $stmt->bindParam(":foto_ruangan", $this->foto_ruangan);
        $stmt->bindParam(":id_pengguna", $this->id_pengguna);

        return $stmt->execute();
    }

    // UPDATE
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                  SET nama_ruangan = :nama_ruangan,
                      kapasitas = :kapasitas,
                      tipe_ruangan = :tipe_ruangan,
                      foto_ruangan = :foto_ruangan,
                      id_pengguna = :id_pengguna
                  WHERE id_ruangan = :id_ruangan";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_ruangan", $this->nama_ruangan);
        $stmt->bindParam(":kapasitas", $this->kapasitas);
        $stmt->bindParam(":tipe_ruangan", $this->tipe_ruangan);
        $stmt->bindParam(":foto_ruangan", $this->foto_ruangan);
        $stmt->bindParam(":id_pengguna", $this->id_pengguna);
        $stmt->bindParam(":id_ruangan", $this->id_ruangan);

        return $stmt->execute();
    }

    // DELETE
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE id_ruangan = :id_ruangan";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_ruangan", $this->id_ruangan);

        return $stmt->execute();
    }

    public function getById($id_ruangan)
{
    $query = "SELECT * FROM ruangan WHERE id_ruangan = :id_ruangan LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id_ruangan", $id_ruangan);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
