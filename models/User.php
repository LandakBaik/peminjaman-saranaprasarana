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

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // login
    public function login($email, $password)
    {

        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE email = :email 
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
                  role     = :role";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            $this->id_pengguna = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    public function readAll()
    {

        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
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
}
