<?php
class Wishlist {
    private $conn;
    private $user_id;

    public function __construct($conn, $user_id) {
        $this->conn = $conn;
        $this->user_id = $user_id;
    }

    // --- GETTERS ---
    public function getConnection() {
        return $this->conn;
    }

    public function getUserId() {
        return $this->user_id;
    }

    // --- SETTERS ---
    public function setConnection($conn) {
        $this->conn = $conn;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    // --- MÉTHODES MÉTIER ---
    public function getItems() {
        $stmt = $this->conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
        $stmt->execute([$this->user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteItem($wishlist_id) {
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE id = ?");
        $stmt->execute([$wishlist_id]);
    }

    public function deleteAllItems() {
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
        $stmt->execute([$this->user_id]);
    }
     public function handleForm() {
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
         $pid = $_POST['pid'] ?? null;
         $name = $_POST['name'] ?? '';
         $price = $_POST['price'] ?? 0;
         $image = $_POST['image'] ?? '';

         if (!$this->user_id || !$pid) return;

         $stmt = $this->conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND pid = ?");
         $stmt->execute([$this->user_id, $pid]);

         if ($stmt->rowCount() === 0) {
            $insert = $this->conn->prepare("INSERT INTO wishlist (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$this->user_id, $pid, $name, $price, $image]);
         }
      }}
}
