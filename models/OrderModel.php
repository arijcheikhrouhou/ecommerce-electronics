<?php
class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Récupère toutes les commandes
     */
    public function getAllOrders(): array {
        $stmt = $this->conn->prepare("SELECT * FROM `orders` ORDER BY placed_on DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le statut de paiement d'une commande
     */
    public function updatePaymentStatus(int $orderId, string $status): bool {
        $stmt = $this->conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }

    /**
     * Supprime une commande par ID
     */
    public function deleteOrder(int $orderId): bool {
        $stmt = $this->conn->prepare("DELETE FROM `orders` WHERE id = ?");
        return $stmt->execute([$orderId]);
    }
}