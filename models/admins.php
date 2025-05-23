<?php
require_once 'Database.php'; // ajuste si besoin

class Admin {
    // Attributs représentant un administrateur
    private $id;
    private $name;
    private $password;

    // Connexion à la base
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // --- Getters ---
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getPassword() {
        return $this->password;
    }

    // --- Setters ---
    public function setId($id) {
        $this->id = (int)$id;
    }
    public function setName($name) {
        $this->name = trim($name);
    }
    public function setPassword($password) {
        $this->password = $password;
    }

    // --- Méthodes CRUD / Auth ---

    public function login($name, $pass) {
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE name = ? AND password = ?");
        $stmt->execute([$name, $pass]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkIfExists($name) {
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt;
    }

    public function registerAdmin($name, $password) {
        $stmt = $this->conn->prepare("INSERT INTO admins (name, password) VALUES (?, ?)");
        $stmt->execute([$name, $password]);
    }

    public function getAllAdmins() {
        $stmt = $this->conn->prepare("SELECT * FROM admins");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAdmin($id) {
        $stmt = $this->conn->prepare("DELETE FROM admins WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAdminById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateName($id, $name) {
        $stmt = $this->conn->prepare("UPDATE admins SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function updatePassword($id, $password) {
        $stmt = $this->conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
        return $stmt->execute([$password, $id]);
    }


    
}
