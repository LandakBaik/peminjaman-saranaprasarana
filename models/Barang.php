<?php
namespace App\Models;

class Barang
{
    private $conn;
    private $table_name = "barang";

    public $id_barang;
    public $id_ruangan;
    public $nama_barang;
    public $deskripsi_barang;
    public $total_stok;
    public $stok_rusak;

    // ini tidak disimpan di DB
    public $dipinjam;
    public $tersedia;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readAll()
    {
        $query = "SELECT 
                b.*,
                r.nama_ruangan,
                COALESCE(SUM(
                    CASE 
                        WHEN p.status = 'dipinjam' THEN 1
                        ELSE 0
                    END
                ), 0) AS dipinjam,
                (b.total_stok - b.stok_rusak - COALESCE(SUM(
                    CASE 
                        WHEN p.status = 'dipinjam' THEN 1
                        ELSE 0
                    END
                ), 0)) AS tersedia
              FROM " . $this->table_name . " b
              LEFT JOIN ruangan r 
                ON b.id_ruangan = r.id_ruangan
              LEFT JOIN detail_peminjaman dp 
                ON dp.id_barang = b.id_barang
              LEFT JOIN peminjaman p 
                ON p.id_peminjaman = dp.id_peminjaman
              GROUP BY b.id_barang";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        // $this->id_barang = $this->generateId();

        $query = "INSERT INTO " . $this->table_name . "
              SET id_ruangan=:id_ruangan,
                  nama_barang=:nama_barang,
                  deskripsi_barang=:deskripsi_barang,
                  total_stok=:total_stok,
                  stok_rusak=:stok_rusak";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_ruangan", $this->id_ruangan);
        $stmt->bindParam(":nama_barang", $this->nama_barang);
        $stmt->bindParam(":deskripsi_barang", $this->deskripsi_barang);
        $stmt->bindParam(":total_stok", $this->total_stok);
        $stmt->bindParam(":stok_rusak", $this->stok_rusak);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                  SET nama_barang=:nama_barang,
                      deskripsi_barang=:deskripsi_barang,
                      total_stok=:total_stok,
                      stok_rusak=:stok_rusak
                  WHERE id_barang=:id_barang";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_barang", $this->nama_barang);
        $stmt->bindParam(":deskripsi_barang", $this->deskripsi_barang);
        $stmt->bindParam(":total_stok", $this->total_stok);
        $stmt->bindParam(":stok_rusak", $this->stok_rusak);
        $stmt->bindParam(":id_barang", $this->id_barang);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_barang = :id_barang";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_barang", $this->id_barang);
        return $stmt->execute();
    }

    public function getByRuangan($id_ruangan)
    {
        $query = "SELECT 
                b.*,
                COALESCE(SUM(
                    CASE WHEN p.status = 'dipinjam' THEN dp.kuantitas ELSE 0 END
                ),0) AS dipinjam,

                (b.total_stok - b.stok_rusak - COALESCE(SUM(
                    CASE WHEN p.status = 'dipinjam' THEN dp.kuantitas ELSE 0 END
                ),0)) AS stok_tersedia

              FROM barang b
              LEFT JOIN detail_peminjaman dp 
                ON dp.id_barang = b.id_barang
              LEFT JOIN peminjaman p 
                ON p.id_peminjaman = dp.id_peminjaman

              WHERE b.id_ruangan = :id_ruangan
              GROUP BY b.id_barang";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_ruangan", $id_ruangan);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByIds($ids)
    {
        $inQuery = implode(',', array_fill(0, count($ids), '?'));
        
        $query = "SELECT 
                b.*,
                r.nama_ruangan,
                COALESCE(SUM(
                    CASE 
                        WHEN p.status = 'dipinjam' THEN 1
                        ELSE 0
                    END
                ), 0) AS dipinjam,
                (b.total_stok - b.stok_rusak - COALESCE(SUM(
                    CASE 
                        WHEN p.status = 'dipinjam' THEN 1
                        ELSE 0
                    END
                ), 0)) AS tersedia
              FROM " . $this->table_name . " b
              LEFT JOIN ruangan r 
                ON b.id_ruangan = r.id_ruangan
              LEFT JOIN detail_peminjaman dp 
                ON dp.id_barang = b.id_barang
              LEFT JOIN peminjaman p 
                ON p.id_peminjaman = dp.id_peminjaman
              WHERE b.id_barang IN ($inQuery)
              GROUP BY b.id_barang";

        $stmt = $this->conn->prepare($query);
        foreach ($ids as $k => $id) {
            $stmt->bindValue(($k+1), $id);
        }
        $stmt->execute();
        return $stmt;
    }
}
