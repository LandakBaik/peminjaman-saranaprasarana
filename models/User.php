<?php
class User {
    private $conn;
    private $table_name = "pengguna";

    public $id_pengguna;
    public $nama;
    public $email;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            if($password === $row['password']) {
                $this->id_pengguna = $row['id_pengguna'];
                $this->nama = $row['nama'];
                $this->role = $row['role'];
                return true;
            }
        }
        return false;
    }

    public function register() {

    // Ambil id terakhir
    $queryId = "SELECT id_pengguna 
                FROM " . $this->table_name . " 
                ORDER BY id_pengguna DESC 
                LIMIT 1";

    $stmtId = $this->conn->prepare($queryId);
    $stmtId->execute();

    $lastId = $stmtId->fetch(PDO::FETCH_ASSOC);

    if($lastId) {
        $num = (int) substr($lastId['id_pengguna'], 3); // ambil angka setelah USR
        $num++;
        $newId = "USR" . str_pad($num, 3, "0", STR_PAD_LEFT);
    } else {
        $newId = "USR001";
    }

    // Insert data
    $query = "INSERT INTO " . $this->table_name . "
              SET id_pengguna=:id_pengguna,
                  nama=:nama,
                  email=:email,
                  password=:password,
                  role=:role";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id_pengguna", $newId);
    $stmt->bindParam(":nama", $this->nama);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":role", $this->role);

    if($stmt->execute()) {
        return true;
    }

    return false;
}

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
