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

    // Data hasil perhitungan
    public $dipinjam;
    public $tersedia;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    // Ambil semua data barang
    public function readAll()
    {
        $query = "SELECT 
                b.*,
                r.nama_ruangan,
                (SELECT COUNT(*) FROM barang WHERE id_ruangan = b.id_ruangan) AS total_barang_ruangan,
                COALESCE(SUM(
                    CASE 
                        WHEN p.status IN ('Dipinjam', 'Pengembalian', 'Menunggu Pengembalian') 
                        OR (p.status IN ('Disetujui', 'approved') AND p.waktu_mulai <= NOW()) 
                        THEN dp.kuantitas
                        ELSE 0
                    END
                ), 0) AS dipinjam,
                (b.total_stok - b.stok_rusak - COALESCE(SUM(
                    CASE 
                        WHEN p.status IN ('Dipinjam', 'Pengembalian', 'Menunggu Pengembalian') 
                        OR (p.status IN ('Disetujui', 'approved') AND p.waktu_mulai <= NOW()) 
                        THEN dp.kuantitas
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


    // Tambah barang
    public function create()
    {
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


    // Update barang
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


    // Hapus barang
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE id_barang = :id_barang";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_barang", $this->id_barang);

        return $stmt->execute();
    }


    // Ambil barang berdasarkan ruangan
    public function getByRuangan($id_ruangan)
    {
        // Stok dasar tanpa validasi waktu
        $query = "SELECT
                    b.*,
                    0 AS dipinjam,
                    (b.total_stok - b.stok_rusak) AS stok_tersedia
                  FROM barang b
                  WHERE b.id_ruangan = :id_ruangan
                  ORDER BY b.nama_barang ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_ruangan", $id_ruangan);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    // Hitung stok tersedia berdasarkan range waktu
    public function getAvailabilityByRange(
        $id_ruangan,
        $start_time,
        $end_time
    ) {

        $query = "SELECT
                    b.id_barang,
                    b.nama_barang,
                    b.total_stok,
                    b.stok_rusak,
                    COALESCE(SUM(
                        CASE
                            WHEN p.status IN ('Disetujui', 'Dipinjam')
                             AND p.waktu_mulai < :end_time
                             AND p.waktu_selesai > :start_time
                            THEN dp.kuantitas
                            ELSE 0
                        END
                    ), 0) AS terpinjam,
                    (b.total_stok - b.stok_rusak - COALESCE(SUM(
                        CASE
                            WHEN p.status IN ('Disetujui', 'Dipinjam')
                             AND p.waktu_mulai < :end_time2
                             AND p.waktu_selesai > :start_time2
                            THEN dp.kuantitas
                            ELSE 0
                        END
                    ), 0)) AS stok_tersedia
                  FROM barang b
                  LEFT JOIN detail_peminjaman dp 
                    ON dp.id_barang = b.id_barang
                  LEFT JOIN peminjaman p 
                    ON p.id_peminjaman = dp.id_peminjaman
                  WHERE b.id_ruangan = :id_ruangan
                  GROUP BY b.id_barang";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_ruangan', $id_ruangan);

        $stmt->bindParam(':start_time', $start_time);

        $stmt->bindParam(':end_time', $end_time);

        $stmt->bindParam(':start_time2', $start_time);

        $stmt->bindParam(':end_time2', $end_time);

        $stmt->execute();

        $result = [];

        foreach (
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
            as $row
        ) {

            $result[$row['id_barang']] = [

                'id_barang' =>
                    $row['id_barang'],

                'nama_barang' =>
                    $row['nama_barang'],

                'total_stok' =>
                    (int) $row['total_stok'],

                'stok_rusak' =>
                    (int) $row['stok_rusak'],

                'terpinjam' =>
                    (int) $row['terpinjam'],

                'stok_tersedia' =>
                    max(0, (int) $row['stok_tersedia']),
            ];
        }

        return $result;
    }


    // Ambil data barang berdasarkan ID
    public function getByIds($ids)
    {
        $inQuery =
            implode(',', array_fill(0, count($ids), '?'));

        $query = "SELECT 
                b.*,
                r.nama_ruangan,
                COALESCE(SUM(
                    CASE 
                        WHEN p.status IN ('Dipinjam', 'Pengembalian', 'Menunggu Pengembalian') 
                        OR (p.status IN ('Disetujui', 'approved') AND p.waktu_mulai <= NOW()) 
                        THEN dp.kuantitas
                        ELSE 0
                    END
                ), 0) AS dipinjam,
                (b.total_stok - b.stok_rusak - COALESCE(SUM(
                    CASE 
                        WHEN p.status IN ('Dipinjam', 'Pengembalian', 'Menunggu Pengembalian') 
                        OR (p.status IN ('Disetujui', 'approved') AND p.waktu_mulai <= NOW()) 
                        THEN dp.kuantitas
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

            $stmt->bindValue(($k + 1), $id);
        }

        $stmt->execute();

        return $stmt;
    }
}