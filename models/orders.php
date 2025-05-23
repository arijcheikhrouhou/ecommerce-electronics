<?php

class Order {
    private $id;
    private $user_id;
    private $placed_on;
    private $name;
    private $email;
    private $number;
    private $address;
    private $method;
    private $total_products;
    private $total_price;
    private $payment_status;

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
 public function getOrdersByUser($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt;
    }
    // Méthode pour récupérer les commandes par user_id
    

    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getPlacedOn() { return $this->placed_on; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getNumber() { return $this->number; }
    public function getAddress() { return $this->address; }
    public function getMethod() { return $this->method; }
    public function getTotalProducts() { return $this->total_products; }
    public function getTotalPrice() { return $this->total_price; }
    public function getPaymentStatus() { return $this->payment_status; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setPlacedOn($placed_on) { $this->placed_on = $placed_on; }
    public function setName($name) { $this->name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setNumber($number) { $this->number = $number; }
    public function setAddress($address) { $this->address = $address; }
    public function setMethod($method) { $this->method = $method; }
    public function setTotalProducts($total_products) { $this->total_products = $total_products; }
    public function setTotalPrice($total_price) { $this->total_price = $total_price; }
    public function setPaymentStatus($payment_status) { $this->payment_status = $payment_status; }

  public function getOrderById($orderId) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 public function isOrderBelongsToUser($orderId, $userId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        return $stmt->fetchColumn() > 0;
    }
public function updatePaymentStatus($orderId, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }
 public function getAllOrders() {
        $stmt = $this->conn->prepare("SELECT * FROM orders ORDER BY placed_on DESC");
        $stmt->execute();
        return $stmt;
    }
}