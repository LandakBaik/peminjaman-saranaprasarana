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
    public $keterangan = '';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 🔹 READ ALL
    public function readAll($isHistory = false)
    {
        $query = "SELECT p.*,
                     u.nama as peminjam,
                     s.nama as staff_approval,
                     MAX(r.nama_ruangan) as nama_ruangan,
                     GROUP_CONCAT(CONCAT(b.nama_barang, ' (', dp.kuantitas, ')') SEPARATOR ', ') as nama_barang
              FROM peminjaman p
              LEFT JOIN pengguna u ON p.id_pengguna = u.id_pengguna
              LEFT JOIN pengguna s ON p.approved_by = s.id_pengguna
              LEFT JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
              LEFT JOIN barang b ON dp.id_barang = b.id_barang
              LEFT JOIN ruangan r ON b.id_ruangan = r.id_ruangan
              WHERE 1=1 ";

        if ($isHistory) {
            $query .= " AND p.status IN ('Selesai', 'Ditolak', 'Returned', 'Dikembalikan', 'Dibatalkan')";
        } else {
            $query .= " AND p.status NOT IN ('Selesai', 'Ditolak', 'Returned', 'Dikembalikan', 'Dibatalkan')";
        }

        $query .= " GROUP BY p.id_peminjaman
                    ORDER BY p.tanggal_dibuat DESC";

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
            $query .= " AND p.status IN ('Selesai', 'Ditolak', 'Returned', 'Dikembalikan')";
        } else {
            $query .= " AND p.status NOT IN ('Selesai', 'Ditolak', 'Returned', 'Dikembalikan')";
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
                     GROUP_CONCAT(CONCAT(b.nama_barang, ' (', dp.kuantitas, ')') SEPARATOR ', ') as nama_barang,
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
            $query .= " AND p.status IN ('Selesai', 'Ditolak', 'Returned', 'Dikembalikan')";
        } else {
            $query .= " AND p.status NOT IN ('Selesai', 'Ditolak', 'Returned', 'Dikembalikan')";
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
            keterangan=:keterangan,
            status='Pending'";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id_pengguna", $this->id_pengguna);
    $stmt->bindParam(":jenis_peminjaman", $this->jenis_peminjaman);
    $stmt->bindParam(":waktu_mulai", $this->waktu_mulai);
    $stmt->bindParam(":waktu_selesai", $this->waktu_selesai);
    $stmt->bindParam(":keperluan", $this->keperluan);
    $stmt->bindParam(":catatan", $this->catatan);
    $stmt->bindParam(":jaminan", $this->jaminan);
    $stmt->bindParam(":keterangan", $this->keterangan);

    $stmt->execute();

    // Get last insert ID untuk detail
    $this->id_peminjaman = $this->conn->lastInsertId();

    // DETAIL
    if ($this->jenis_peminjaman == 'barang') {

        foreach ($this->items as $id => $kuantitas) {
            $this->insertDetail($id, $kuantitas);
        }

    } elseif ($this->jenis_peminjaman == 'ruangan') {

        $barangModel  = new \App\Models\Barang($this->conn);
        // Gunakan range waktu yang dipilih user agar konsisten dengan
        // sistem validasi stok baru (hanya Disetujui & Dipinjam yang dihitung)
        $availability = $barangModel->getAvailabilityByRange(
            $this->id_ruangan,
            $this->waktu_mulai,
            $this->waktu_selesai
        );

        foreach ($availability as $b) {
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

    public function updateDetailQuantity($id_peminjaman, $id_barang, $kuantitas)
    {
        $query = "UPDATE detail_peminjaman 
                  SET kuantitas = :kuantitas 
                  WHERE id_peminjaman = :id_peminjaman 
                  AND id_barang = :id_barang";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kuantitas", $kuantitas);
        $stmt->bindParam(":id_peminjaman", $id_peminjaman);
        $stmt->bindParam(":id_barang", $id_barang);
        
        return $stmt->execute();
    }
    // 🔹 GET BY ID (dengan detail barang)
    public function getById($id_peminjaman)
    {
        // Data peminjaman
        $query = "SELECT p.*, b.id_ruangan
                  FROM peminjaman p
                  LEFT JOIN detail_peminjaman dp ON dp.id_peminjaman = p.id_peminjaman
                  LEFT JOIN barang b ON b.id_barang = dp.id_barang
                  WHERE p.id_peminjaman = :id_peminjaman
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_peminjaman', $id_peminjaman);
        $stmt->execute();
        $peminjaman = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$peminjaman) return null;

        // Detail barang (id_barang => kuantitas)
        $qDetail = "SELECT id_barang, kuantitas FROM detail_peminjaman WHERE id_peminjaman = :id";
        $sDetail = $this->conn->prepare($qDetail);
        $sDetail->bindParam(':id', $id_peminjaman);
        $sDetail->execute();
        $peminjaman['items'] = $sDetail->fetchAll(\PDO::FETCH_KEY_PAIR); // [id_barang => kuantitas]

        return $peminjaman;
    }

    // 🔹 UPDATE STATUS
    public function updateStatus()
    {
        $query = "UPDATE peminjaman 
                  SET status=:status, approved_by=:approved_by, keterangan=:keterangan, tanggal_diubah = NOW()
                  WHERE id_peminjaman = :id_peminjaman";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":approved_by", $this->approved_by);
        $stmt->bindParam(":keterangan", $this->keterangan);
        $stmt->bindParam(":id_peminjaman", $this->id_peminjaman);

        return $stmt->execute();
    }

    // 🔹 UPDATE STATUS ONLY
    public function updateStatusOnly()
    {
        $query = "UPDATE peminjaman 
                  SET status=:status, tanggal_diubah = NOW()
                  WHERE id_peminjaman = :id_peminjaman";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id_peminjaman", $this->id_peminjaman);

        return $stmt->execute();
    }

    public function updateLateStatus($id_pengguna = null)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = 'Terlambat' 
                  WHERE (status = 'Dipinjam' OR status = 'Disetujui' OR status = 'Approved') 
                  AND waktu_selesai < NOW()";

        if ($id_pengguna) {
            $query .= " AND id_pengguna = :id_pengguna";
        }

        $stmt = $this->conn->prepare($query);
        if ($id_pengguna) {
            $stmt->bindParam(':id_pengguna', $id_pengguna);
        }
        return $stmt->execute();
    }

    // 🔹 DELETE
    public function delete()
    {
        // Detail peminjaman akan terhapus otomatis jika ada ON DELETE CASCADE di database.
        // Jika tidak, kita hapus manual detailnya dulu.
        
        $queryDetail = "DELETE FROM detail_peminjaman WHERE id_peminjaman = :id_peminjaman";
        $stmtDetail = $this->conn->prepare($queryDetail);
        $stmtDetail->bindParam(":id_peminjaman", $this->id_peminjaman);
        $stmtDetail->execute();

        $query = "DELETE FROM " . $this->table_name . " WHERE id_peminjaman = :id_peminjaman";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_peminjaman", $this->id_peminjaman);

        return $stmt->execute();
    }

    public function getStats($role, $id_pengguna = null, $filter = 'daily')
    {
        $stats = ['total' => 0, 'disetujui' => 0, 'ditolak' => 0, 'terlambat' => 0];
        $filterQuery = "";

        if ($filter === 'daily') {
            $filterQuery = " AND DATE(p.tanggal_dibuat) = CURDATE()";
        } elseif ($filter === 'weekly') {
            $filterQuery = " AND p.tanggal_dibuat >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)";
        } elseif ($filter === 'monthly') {
            $filterQuery = " AND p.tanggal_dibuat >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)";
        } elseif ($filter === 'yearly') {
            $filterQuery = " AND YEAR(p.tanggal_dibuat) = YEAR(CURDATE())";
        }

        $baseQuery = "SELECT COUNT(*) FROM peminjaman p ";
        $joinQuery = "";
        $whereQuery = " WHERE 1=1 " . $filterQuery;

        if ($role === 'user') {
            $whereQuery .= " AND p.id_pengguna = :id_pengguna";
        } elseif ($role === 'staff') {
            $joinQuery = " INNER JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman 
                           INNER JOIN barang b ON dp.id_barang = b.id_barang 
                           INNER JOIN ruangan r ON b.id_ruangan = r.id_ruangan ";
            $whereQuery .= " AND r.id_pengguna = :id_pengguna";
        }

        // Total
        $stmt = $this->conn->prepare($baseQuery . $joinQuery . $whereQuery);
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        $stats['total'] = $stmt->fetchColumn();

        // Disetujui
        $stmt = $this->conn->prepare($baseQuery . $joinQuery . $whereQuery . " AND p.status IN ('Disetujui', 'Dipinjam', 'Selesai')");
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        $stats['disetujui'] = $stmt->fetchColumn();

        // Ditolak
        $stmt = $this->conn->prepare($baseQuery . $joinQuery . $whereQuery . " AND p.status = 'Ditolak'");
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        $stats['ditolak'] = $stmt->fetchColumn();

        // Terlambat
        $stmt = $this->conn->prepare($baseQuery . $joinQuery . $whereQuery . " AND (p.status = 'Terlambat' OR (p.status IN ('Dipinjam', 'Disetujui', 'Approved') AND p.waktu_selesai < NOW()))");
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        $stats['terlambat'] = $stmt->fetchColumn();

        return $stats;
    }

    public function getRecent($role, $id_pengguna = null, $limit = 5, $filter = 'daily')
    {
        $filterQuery = "";
        if ($filter === 'daily') {
            $filterQuery = " AND DATE(p.tanggal_dibuat) = CURDATE()";
        } elseif ($filter === 'weekly') {
            $filterQuery = " AND p.tanggal_dibuat >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)";
        } elseif ($filter === 'monthly') {
            $filterQuery = " AND p.tanggal_dibuat >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)";
        } elseif ($filter === 'yearly') {
            $filterQuery = " AND YEAR(p.tanggal_dibuat) = YEAR(CURDATE())";
        }

        $query = "SELECT p.*, 
                         GROUP_CONCAT(DISTINCT b.nama_barang SEPARATOR ', ') as items,
                         MAX(r.nama_ruangan) as nama_ruangan
                  FROM peminjaman p
                  LEFT JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
                  LEFT JOIN barang b ON dp.id_barang = b.id_barang
                  LEFT JOIN ruangan r ON b.id_ruangan = r.id_ruangan ";

        if ($role === 'user') {
            $query .= " WHERE p.id_pengguna = :id_pengguna " . $filterQuery;
        } elseif ($role === 'staff') {
            // Re-join for filtering but keep outer joins for data
            $query .= " INNER JOIN detail_peminjaman dp2 ON p.id_peminjaman = dp2.id_peminjaman 
                        INNER JOIN barang b2 ON dp2.id_barang = b2.id_barang 
                        INNER JOIN ruangan r2 ON b2.id_ruangan = r2.id_ruangan 
                        WHERE r2.id_pengguna = :id_pengguna " . $filterQuery;
        } else {
            $query .= " WHERE 1=1 " . $filterQuery;
        }

        $query .= " GROUP BY p.id_peminjaman ORDER BY p.tanggal_dibuat DESC LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    public function getTrendData($role, $id_pengguna = null, $filter = 'daily')
    {
        $labels = [];
        $values = [];

        // DAILY → per 3 jam
        if ($filter === 'daily') {

            $hours = [0, 3, 6, 9, 12, 15, 18, 21];

            foreach ($hours as $hour) {

                $start = date('Y-m-d') . ' ' .
                    str_pad($hour, 2, '0', STR_PAD_LEFT) .
                    ':00:00';

                $endHour = $hour + 3;

                if ($endHour >= 24) {

                    $end = date('Y-m-d', strtotime('+1 day')) .
                        ' 00:00:00';

                } else {

                    $end = date('Y-m-d') . ' ' .
                        str_pad($endHour, 2, '0', STR_PAD_LEFT) .
                        ':00:00';
                }

                $labels[] = sprintf('%02d:00', $hour);

                $values[] = $this->getCountByRange(
                    $role,
                    $id_pengguna,
                    $start,
                    $end
                );
            }
        }

        // WEEKLY → 7 hari terakhir
        elseif ($filter === 'weekly') {

            for ($i = 6; $i >= 0; $i--) {

                $date = date('Y-m-d', strtotime("-$i days"));

                $labels[] = date('D', strtotime($date));

                $values[] = $this->getCountByDate(
                    $role,
                    $id_pengguna,
                    $date
                );
            }
        }

        // MONTHLY → Week 1-4
        elseif ($filter === 'monthly') {

            for ($i = 3; $i >= 0; $i--) {

                $labels[] = 'Week ' . (4 - $i);

                $values[] = $this->getCountByWeek(
                    $role,
                    $id_pengguna,
                    $i
                );
            }
        }

        // YEARLY → Jan-Dec
        elseif ($filter === 'yearly') {

            $year = date('Y');

            for ($m = 1; $m <= 12; $m++) {

                $monthStr = str_pad($m, 2, '0', STR_PAD_LEFT);

                $monthYear = "$year-$monthStr";

                $labels[] = date(
                    'M',
                    strtotime("$monthYear-01")
                );

                $values[] = $this->getCountByMonth(
                    $role,
                    $id_pengguna,
                    $monthYear
                );
            }
        }

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    private function getCountByDate($role, $id_pengguna, $date)
    {
        $query = "SELECT COUNT(*) FROM peminjaman p ";
        if ($role === 'staff') {
            $query .= " INNER JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman 
                        INNER JOIN barang b ON dp.id_barang = b.id_barang 
                        INNER JOIN ruangan r ON b.id_ruangan = r.id_ruangan ";
        }
        $query .= " WHERE DATE(p.tanggal_dibuat) = :date";
        if ($role === 'user') $query .= " AND p.id_pengguna = :id_pengguna";
        if ($role === 'staff') $query .= " AND r.id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function getCountByWeek($role, $id_pengguna, $weeksAgo)
    {
        $query = "SELECT COUNT(*) FROM peminjaman p ";
        if ($role === 'staff') {
            $query .= " INNER JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman 
                        INNER JOIN barang b ON dp.id_barang = b.id_barang 
                        INNER JOIN ruangan r ON b.id_ruangan = r.id_ruangan ";
        }
        $query .= " WHERE YEARWEEK(p.tanggal_dibuat, 1) = YEARWEEK(CURDATE() - INTERVAL :weeks WEEK, 1)";
        if ($role === 'user') $query .= " AND p.id_pengguna = :id_pengguna";
        if ($role === 'staff') $query .= " AND r.id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':weeks', $weeksAgo, \PDO::PARAM_INT);
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function getCountByMonth($role, $id_pengguna, $monthYear)
    {
        $query = "SELECT COUNT(*) FROM peminjaman p ";
        if ($role === 'staff') {
            $query .= " INNER JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman 
                        INNER JOIN barang b ON dp.id_barang = b.id_barang 
                        INNER JOIN ruangan r ON b.id_ruangan = r.id_ruangan ";
        }
        $query .= " WHERE DATE_FORMAT(p.tanggal_dibuat, '%Y-%m') = :month";
        if ($role === 'user') $query .= " AND p.id_pengguna = :id_pengguna";
        if ($role === 'staff') $query .= " AND r.id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':month', $monthYear);
        if ($role !== 'admin') $stmt->bindParam(':id_pengguna', $id_pengguna);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    private function getCountByRange($role, $id_pengguna, $start, $end)
    {
        $query = "SELECT COUNT(*) FROM peminjaman p ";

        if ($role === 'staff') {

            $query .= "
            INNER JOIN detail_peminjaman dp
                ON p.id_peminjaman = dp.id_peminjaman

            INNER JOIN barang b
                ON dp.id_barang = b.id_barang

            INNER JOIN ruangan r
                ON b.id_ruangan = r.id_ruangan
        ";
        }

        $query .= "
        WHERE p.tanggal_dibuat >= :start
        AND p.tanggal_dibuat < :end
    ";

        if ($role === 'user') {
            $query .= " AND p.id_pengguna = :id_pengguna";
        }

        if ($role === 'staff') {
            $query .= " AND r.id_pengguna = :id_pengguna";
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);

        if ($role !== 'admin') {
            $stmt->bindParam(':id_pengguna', $id_pengguna);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getApprovedDatesByRoom($id_ruangan)
    {
        $query = "SELECT DISTINCT DATE(p.waktu_mulai) as tanggal 
                  FROM peminjaman p
                  JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
                  JOIN barang b ON dp.id_barang = b.id_barang
                  WHERE b.id_ruangan = :id_ruangan 
                  AND p.status IN ('Disetujui', 'Dipinjam')
                  AND p.jenis_peminjaman = 'ruangan'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_ruangan', $id_ruangan);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function rejectConflictingPendingLoans($id_peminjaman_approved, $approved_by)
    {
        // 1. Ambil data peminjaman yang baru saja disetujui
        $approvedData = $this->getById($id_peminjaman_approved);
        if (!$approvedData) return;

        $start_approved = $approvedData['waktu_mulai'];
        $end_approved   = $approvedData['waktu_selesai'];
        $items_approved = $approvedData['items']; // [id_barang => kuantitas]

        if (empty($items_approved)) return;

        // 2. Cari peminjaman lain yang berstatus 'Pending' dan rentang waktunya bertabrakan
        // Serta memiliki setidaknya satu barang yang sama
        $itemIds = array_keys($items_approved);
        $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
        
        $query = "SELECT DISTINCT p.id_peminjaman
                  FROM peminjaman p
                  JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
                  WHERE p.id_peminjaman != ?
                  AND p.status = 'Pending'
                  AND p.waktu_mulai < ?
                  AND p.waktu_selesai > ?
                  AND dp.id_barang IN ($placeholders)";
        
        $stmt = $this->conn->prepare($query);
        $params = array_merge([$id_peminjaman_approved, $end_approved, $start_approved], $itemIds);
        $stmt->execute($params);
        $pendingIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($pendingIds)) return;

        $barangModel = new \App\Models\Barang($this->conn);

        // 3. Re-validasi stok untuk setiap peminjaman pending yang terdeteksi konflik
        foreach ($pendingIds as $pid) {
            $pData = $this->getById($pid);
            if (!$pData) continue;

            $id_ruangan_p = $pData['id_ruangan'];
            $items_p      = $pData['items'];
            $start_p      = $pData['waktu_mulai'];
            $end_p        = $pData['waktu_selesai'];

            if ($id_ruangan_p) {
                $availability = $barangModel->getAvailabilityByRange($id_ruangan_p, $start_p, $end_p);
                
                $shouldReject = false;
                foreach ($items_p as $id_barang => $qty_requested) {
                    $stok_tersedia = isset($availability[$id_barang]) ? $availability[$id_barang]['stok_tersedia'] : 0;
                    
                    if ($qty_requested > $stok_tersedia) {
                        $shouldReject = true;
                        break;
                    }
                }

                if ($shouldReject) {
                    // Pengecualian: Jika peminjaman yang pending adalah 'ruangan' 
                    // dan yang baru saja disetujui adalah 'barang', jangan ditolak (dibuat dinamis).
                    // Tapi jika sesama 'ruangan' atau jika yang pending adalah 'barang', tetap ditolak.
                    if ($pData['jenis_peminjaman'] === 'ruangan' && $approvedData['jenis_peminjaman'] === 'barang') {
                        continue;
                    }

                    $queryReject = "UPDATE peminjaman 
                                    SET status = 'Ditolak', 
                                        keterangan = 'telah dipinjam oleh user lain',
                                        approved_by = :approved_by,
                                        tanggal_diubah = NOW()
                                    WHERE id_peminjaman = :id_p";
                    $stmtReject = $this->conn->prepare($queryReject);
                    $stmtReject->bindParam(':approved_by', $approved_by);
                    $stmtReject->bindParam(':id_p', $pid);
                    $stmtReject->execute();
                }
            }
        }
    }
}
