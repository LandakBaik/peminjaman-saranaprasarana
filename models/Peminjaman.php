<?php
class Peminjaman {
    private $conn;
    private $table_name = "peminjaman";

    public $id;
    public $user_id;
    public $jenis_peminjaman;
    public $item_id;
    public $jumlah;
    public $tanggal_pinjam;
    public $tanggal_kembali;
    public $keperluan;
    public $status;
    public $approved_by;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT p.*, u.name as user_name, 
                  CASE 
                    WHEN p.jenis_peminjaman = 'barang' THEN b.nama_barang
                    WHEN p.jenis_peminjaman = 'ruangan' THEN r.nama_ruangan
                  END as item_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN users u ON p.user_id = u.id
                  LEFT JOIN barang b ON p.item_id = b.id AND p.jenis_peminjaman = 'barang'
                  LEFT JOIN ruangan r ON p.item_id = r.id AND p.jenis_peminjaman = 'ruangan'
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByUser($user_id) {
        $query = "SELECT p.*, 
                  CASE 
                    WHEN p.jenis_peminjaman = 'barang' THEN b.nama_barang
                    WHEN p.jenis_peminjaman = 'ruangan' THEN r.nama_ruangan
                  END as item_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN barang b ON p.item_id = b.id AND p.jenis_peminjaman = 'barang'
                  LEFT JOIN ruangan r ON p.item_id = r.id AND p.jenis_peminjaman = 'ruangan'
                  WHERE p.user_id = :user_id
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET user_id=:user_id, jenis_peminjaman=:jenis_peminjaman, item_id=:item_id, 
                      jumlah=:jumlah, tanggal_pinjam=:tanggal_pinjam, tanggal_kembali=:tanggal_kembali, 
                      keperluan=:keperluan, status='pending'";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":jenis_peminjaman", $this->jenis_peminjaman);
        $stmt->bindParam(":item_id", $this->item_id);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":tanggal_pinjam", $this->tanggal_pinjam);
        $stmt->bindParam(":tanggal_kembali", $this->tanggal_kembali);
        $stmt->bindParam(":keperluan", $this->keperluan);

        return $stmt->execute();
    }

    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status=:status, approved_by=:approved_by WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":approved_by", $this->approved_by);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
?>
