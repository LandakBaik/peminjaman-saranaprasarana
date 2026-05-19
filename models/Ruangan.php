<?php

namespace App\Models;

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


    // Ambil semua ruangan
    public function readAll()
    {
        $query = "SELECT 
                    r.*,
                    COUNT(b.id_barang) AS total_barang
                  FROM " . $this->table_name . " r
                  LEFT JOIN barang b 
                    ON r.id_ruangan = b.id_ruangan
                  GROUP BY r.id_ruangan";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }


    // Tambah ruangan
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
              SET nama_ruangan = :nama_ruangan,
                  kapasitas = :kapasitas,
                  tipe_ruangan = :tipe_ruangan,
                  foto_ruangan = :foto_ruangan,
                  id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(
            ":nama_ruangan",
            $this->nama_ruangan
        );

        $stmt->bindParam(
            ":kapasitas",
            $this->kapasitas
        );

        $stmt->bindParam(
            ":tipe_ruangan",
            $this->tipe_ruangan
        );

        $stmt->bindParam(
            ":foto_ruangan",
            $this->foto_ruangan
        );

        $stmt->bindParam(
            ":id_pengguna",
            $this->id_pengguna
        );

        return $stmt->execute();
    }


    // Update ruangan
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

        $stmt->bindParam(
            ":nama_ruangan",
            $this->nama_ruangan
        );

        $stmt->bindParam(
            ":kapasitas",
            $this->kapasitas
        );

        $stmt->bindParam(
            ":tipe_ruangan",
            $this->tipe_ruangan
        );

        $stmt->bindParam(
            ":foto_ruangan",
            $this->foto_ruangan
        );

        $stmt->bindParam(
            ":id_pengguna",
            $this->id_pengguna
        );

        $stmt->bindParam(
            ":id_ruangan",
            $this->id_ruangan
        );

        return $stmt->execute();
    }


    // Ambil ruangan berdasarkan ID
    public function getByIds($ids)
    {
        $inQuery =
            implode(',', array_fill(0, count($ids), '?'));

        $query = "
            SELECT 
                r.*,
                COUNT(b.id_barang) AS total_barang
            FROM " . $this->table_name . " r
            LEFT JOIN barang b 
                ON r.id_ruangan = b.id_ruangan
            WHERE r.id_ruangan IN ($inQuery)
            GROUP BY r.id_ruangan
        ";

        $stmt = $this->conn->prepare($query);

        foreach ($ids as $k => $id) {

            $stmt->bindValue(($k + 1), $id);
        }

        $stmt->execute();

        return $stmt;
    }


    // Hapus ruangan
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE id_ruangan = :id_ruangan";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(
            ":id_ruangan",
            $this->id_ruangan
        );

        return $stmt->execute();
    }


    // Ambil detail ruangan
    public function getById($id_ruangan)
    {
        $query = "SELECT *
                  FROM ruangan
                  WHERE id_ruangan = :id_ruangan
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(
            ":id_ruangan",
            $id_ruangan
        );

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    // Ambil ruangan berdasarkan tipe
    public function getByTipe($tipe_ruangan)
    {
        $query = "SELECT *
                  FROM ruangan
                  WHERE tipe_ruangan = :tipe_ruangan";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(
            ":tipe_ruangan",
            $tipe_ruangan
        );

        $stmt->execute();

        return $stmt;
    }


    // Assign staff ke beberapa ruangan
    public function assignStaffToRooms(
        $id_pengguna,
        $ruangan_ids
    ) {

        if (empty($ruangan_ids)) {
            return false;
        }

        $placeholders =
            implode(
                ',',
                array_fill(0, count($ruangan_ids), '?')
            );

        $query = "UPDATE " . $this->table_name . " 
                  SET id_pengguna = ? 
                  WHERE id_ruangan IN ($placeholders)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(1, $id_pengguna);

        foreach (
            $ruangan_ids as $index => $id_ruangan
        ) {

            $stmt->bindValue(
                $index + 2,
                $id_ruangan
            );
        }

        return $stmt->execute();
    }


    // Kembalikan ruangan ke admin fallback
    public function unassignStaffFromAllRooms(
        $id_pengguna,
        $fallback_id
    ) {

        $query = "UPDATE " . $this->table_name . " 
                  SET id_pengguna = :fallback_id 
                  WHERE id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(
            ":fallback_id",
            $fallback_id
        );

        $stmt->bindParam(
            ":id_pengguna",
            $id_pengguna
        );

        return $stmt->execute();
    }


    // Ambil daftar ruangan staff
    public function getRoomsByStaff($id_pengguna)
    {
        $query = "SELECT id_ruangan
                  FROM " . $this->table_name . "
                  WHERE id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(
            ":id_pengguna",
            $id_pengguna
        );

        $stmt->execute();

        $ids = [];

        while (
            $row = $stmt->fetch(\PDO::FETCH_ASSOC)
        ) {

            $ids[] = $row['id_ruangan'];
        }

        return $ids;
    }
}