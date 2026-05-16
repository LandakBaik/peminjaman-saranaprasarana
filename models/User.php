<?php
namespace App\Models;

class User
{
    private $conn;
    private $table_name = "pengguna";

    public $id_pengguna;
    public $nama;
    public $email;
    public $password;
    public $role;
    public $status = 'aktif';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // login
    public function login($email, $password)
    {

        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE email = :email AND status = 'aktif'
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {

            if (password_verify($password, $row['password'])) {

                $this->id_pengguna = $row['id_pengguna'];
                $this->nama        = $row['nama'];
                $this->email       = $row['email'];
                $this->role        = $row['role'];
                $this->status      = $row['status'];

                return true;
            }
        }

        return false;
    }

    // register
    public function register()
    {
        // cek email
        $check = "SELECT email FROM " . $this->table_name . " 
              WHERE email = :email LIMIT 1";

        $stmtCheck = $this->conn->prepare($check);
        $stmtCheck->bindParam(":email", $this->email);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            return false;
        }

        // hash password
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        // insert tanpa id_pengguna
        $query = "INSERT INTO " . $this->table_name . "
              SET nama     = :nama,
                  email    = :email,
                  password = :password,
                  role     = :role,
                  status   = :status";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            $this->id_pengguna = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function readAll()
    {

        $query = "SELECT p.*, d.nomor_telepon, d.tanggal_lahir, d.jenis_kelamin, d.nama_panggilan, d.foto_profil 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN detail_profil d ON p.id_pengguna = d.id_pengguna";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readByRoles($roles)
    {
        if (empty($roles)) {
            return $this->readAll();
        }

        $placeholders = implode(',', array_fill(0, count($roles), '?'));
        $query = "SELECT p.*, d.nomor_telepon, d.tanggal_lahir, d.jenis_kelamin, d.nama_panggilan, d.foto_profil 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN detail_profil d ON p.id_pengguna = d.id_pengguna
                  WHERE p.role IN ($placeholders)";

        $stmt = $this->conn->prepare($query);
        foreach ($roles as $k => $role) {
            $stmt->bindValue(($k + 1), $role);
        }
        $stmt->execute();

        return $stmt;
    }

    public function getByIds($ids)
    {
        $inQuery = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna IN ({$inQuery})";
        
        $stmt = $this->conn->prepare($query);
        foreach ($ids as $k => $id) {
            $stmt->bindValue(($k + 1), $id);
        }
        $stmt->execute();
        
        return $stmt;
    }

    public function createResetToken($email, $token)
    {
        $delete = "DELETE FROM password_resets WHERE email = :email";
        $stmtDel = $this->conn->prepare($delete);
        $stmtDel->bindParam(":email", $email);
        $stmtDel->execute();

        $query = "INSERT INTO password_resets 
              (email, token, expires_at) 
              VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":token", $token);

        return $stmt->execute();
    }

    public function findToken($token)
    {
        $query = "SELECT * FROM password_resets 
              WHERE token = :token 
              AND expires_at > NOW() 
              LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    public function updatePasswordByEmail($email, $password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE " . $this->table_name . "
              SET password = :password
              WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashed);
        $stmt->bindParam(":email", $email);

        return $stmt->execute();
    }
    public function deleteToken($email)
    {
        $query = "DELETE FROM password_resets WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                  SET nama = :nama,
                      email = :email,
                      role = :role,
                      status = :status
                  WHERE id_pengguna = :id_pengguna";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id_pengguna", $this->id_pengguna);

        return $stmt->execute();
    }
}
