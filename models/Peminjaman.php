<?php
namespace App\Models;

class Peminjaman
{
    private $conn;
    private $table_name = "peminjaman";

    public $id_peminjaman;
    public $id_pengguna;
    public $jenis_peminjaman;
    public $waktu_mulai;
    public $waktu_selesai;
    // public $tanggal_kembali;
    public $status;
    public $approved_by;

    public $items = [];
    public $id_ruangan;
    public $keperluan;
    public $catatan;
    public $jaminan;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 🔹 READ ALL
    public function readAll($isHistory = false)
    {
        $query = "SELECT p.*,
                     u.nama as peminjam,
                     s.nama as staff_approval
              FROM peminjaman p
              LEFT JOIN pengguna u ON p.id_pengguna = u.id_pengguna
              LEFT JOIN pengguna s ON p.approved_by = s.id_pengguna
              WHERE 1=1 ";

        if ($isHistory) {
            $query .= " AND p.status IN ('Selesai', 'Ditolak', 'Dibatalkan', 'Returned', 'Dikembalikan')";
        } else {
            $query .= " AND p.status NOT IN ('Selesai', 'Ditolak', 'Dibatalkan', 'Returned', 'Dikembalikan')";
        }

        $query .= " ORDER BY p.tanggal_dibuat DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // 🔹 READ BY USER
    public function readByUser($id_pengguna, $isHistory = false)
    {
        $query = "SELECT p.*,
                     u.nama as peminjam,
                     s.nama as staff_approval,
                     GROUP_CONCAT(CONCAT(b.nama_barang, ' (', dp.kuantitas, ')') SEPARATOR ', ') as daftar_barang,
                     MAX(r.nama_ruangan) as nama_ruangan

              FROM peminjaman p

              LEFT JOIN pengguna u 
                    ON p.id_pengguna = u.id_pengguna

              LEFT JOIN pengguna s 
                    ON p.approved_by = s.id_pengguna

              LEFT JOIN detail_peminjaman dp 
                    ON dp.id_peminjaman = p.id_peminjaman

              LEFT JOIN barang b 
                    ON dp.id_barang = b.id_barang

              LEFT JOIN ruangan r 
                    ON b.id_ruangan = r.id_ruangan

              WHERE p.id_pengguna = :id_pengguna";

        if ($isHistory) {
            $query .= " AND p.status IN ('Selesai', 'Ditolak', 'Dibatalkan', 'Returned', 'Dikembalikan')";
        } else {
            $query .= " AND p.status NOT IN ('Selesai', 'Ditolak', 'Dibatalkan', 'Returned', 'Dikembalikan')";
        }

        $query .= " GROUP BY p.id_peminjaman
                ORDER BY p.tanggal_dibuat DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();

        return $stmt;
    }

    public function readByStaff($id_pengguna, $isHistory = false)
    {
        $query = "SELECT p.*,
                     u.nama as peminjam,
                     s.nama as staff_approval,
                     GROUP_CONCAT(b.nama_barang SEPARATOR ', ') as nama_barang,
                     MAX(r.nama_ruangan) as nama_ruangan

              FROM peminjaman p

              LEFT JOIN pengguna u 
                    ON p.id_pengguna = u.id_pengguna

              LEFT JOIN pengguna s 
                    ON p.approved_by = s.id_pengguna

              INNER JOIN detail_peminjaman dp 
                    ON dp.id_peminjaman = p.id_peminjaman

              INNER JOIN barang b 
                    ON dp.id_barang = b.id_barang

              INNER JOIN ruangan r 
                    ON b.id_ruangan = r.id_ruangan

              WHERE r.id_pengguna = :id_pengguna";

        if ($isHistory) {
            $query .= " AND p.status IN ('Selesai', 'Ditolak', 'Dibatalkan', 'Returned', 'Dikembalikan')";
        } else {
            $query .= " AND p.status NOT IN ('Selesai', 'Ditolak', 'Dibatalkan', 'Returned', 'Dikembalikan')";
        }

        $query .= " GROUP BY p.id_peminjaman
                ORDER BY p.tanggal_dibuat DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();

        return $stmt;
    }

   public function create()
{
    if ($this->jenis_peminjaman == 'barang') {
        if (
            date('Y-m-d', strtotime($this->waktu_mulai)) !=
            date('Y-m-d', strtotime($this->waktu_selesai))
        ) {
            die("Peminjaman barang hanya boleh 1 hari!");
        }
    }

    // generate ID manual
    // $this->id_peminjaman = $this->generateId();

    $query = "INSERT INTO peminjaman 
        SET id_pengguna=:id_pengguna,
            jenis_peminjaman=:jenis_peminjaman,
            waktu_mulai=:waktu_mulai,
            waktu_selesai=:waktu_selesai,
            keperluan=:keperluan,
            catatan=:catatan,
            jaminan=:jaminan,
            status='Pending'";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id_pengguna", $this->id_pengguna);
    $stmt->bindParam(":jenis_peminjaman", $this->jenis_peminjaman);
    $stmt->bindParam(":waktu_mulai", $this->waktu_mulai);
    $stmt->bindParam(":waktu_selesai", $this->waktu_selesai);
    $stmt->bindParam(":keperluan", $this->keperluan);
    $stmt->bindParam(":catatan", $this->catatan);
    $stmt->bindParam(":jaminan", $this->jaminan);

    $stmt->execute();

    // Get last insert ID untuk detail
    $this->id_peminjaman = $this->conn->lastInsertId();

    // DETAIL
    if ($this->jenis_peminjaman == 'barang') {

        foreach ($this->items as $id => $kuantitas) {
            $this->insertDetail($id, $kuantitas);
        }

    } elseif ($this->jenis_peminjaman == 'ruangan') {

        $barangModel = new \App\Models\Barang($this->conn);
        $barangList = $barangModel->getByRuangan($this->id_ruangan);

        foreach ($barangList as $b) {
            if ($b['stok_tersedia'] > 0) {
                $this->insertDetail($b['id_barang'], $b['stok_tersedia']);
            }
        }
    }

    return true;
}
    private function insertDetail($id_barang, $kuantitas)
{
    $query = "INSERT INTO detail_peminjaman 
              SET id_peminjaman=:id_peminjaman,
                  id_barang=:id_barang,
                  kuantitas=:kuantitas";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id_peminjaman", $this->id_peminjaman);
    $stmt->bindParam(":id_barang", $id_barang);
    $stmt->bindParam(":kuantitas", $kuantitas);

    $stmt->execute();
}
    // 🔹 UPDATE STATUS
    public function updateStatus()
    {
        $query = "UPDATE peminjaman 
                  SET status=:status, approved_by=:approved_by 
                  WHERE id_peminjaman = :id_peminjaman";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":approved_by", $this->approved_by);
        $stmt->bindParam(":id_peminjaman", $this->id_peminjaman);

        return $stmt->execute();
    }

    // 🔹 UPDATE STATUS ONLY
    public function updateStatusOnly()
    {
        $query = "UPDATE peminjaman 
                  SET status=:status 
                  WHERE id_peminjaman = :id_peminjaman";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id_peminjaman", $this->id_peminjaman);

        return $stmt->execute();
    }
}
