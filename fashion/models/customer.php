<?php
class Customer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO customers (email, password_hash, first_name, last_name, phone) 
                VALUES (:email, :password_hash, :first_name, :last_name, :phone)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':phone' => $data['phone'] ?? null
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE customers SET first_name=:first_name, last_name=:last_name, phone=:phone WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':phone' => $data['phone'],
            ':id' => $id
        ]);
    }
}
?>