<?php

class cart {

    private $conn;

    private $id;
    private $user_id;
    private $pid;
    private $name;
    private $price;
    private $quantity;
    private $image;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // =====================
    // Getters et Setters
    // =====================

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function getPid() {
        return $this->pid;
    }

    public function setPid($pid) {
        $this->pid = $pid;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    // =====================
    // Méthodes de base de données
    // =====================
public function getItemsByUser($userId): array {
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearCart($userId): void {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
    public function getCartItems($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteItem($cart_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = ?");
        return $stmt->execute([$cart_id]);
    }

    public function deleteAllItems($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    public function updateQuantity($cart_id, $qty) {
        $qty = filter_var($qty, FILTER_SANITIZE_STRING);
        $stmt = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        return $stmt->execute([$qty, $cart_id]);
    }

    public function getItemById($cart_id) {
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE id = ?");
        $stmt->execute([$cart_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertItem($data) {
        $stmt = $this->conn->prepare("INSERT INTO cart (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['pid'],
            $data['name'],
            $data['price'],
            $data['quantity'],
            $data['image']
        ]);
    }
}
