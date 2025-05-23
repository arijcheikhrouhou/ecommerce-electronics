<html>
<head>
    <meta charset="UTF-8">
    <title>Wishlist & Panier</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Ton fichier CSS -->
</head>
<body>
<?php
require_once '../models/Database.php'; 
$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'] ?? '';

class WishlistCartHandler {
    private $conn;
    private $user_id;
    public $messages = [];

    public function __construct($conn, $user_id) {
        $this->conn = $conn;
        $this->user_id = $user_id;
    }

    private function sanitize($data) {
        return filter_var($data, FILTER_SANITIZE_STRING);
    }

    public function addToWishlist($post) {
        if ($this->user_id === '') {
            header('Location: user_login.php');
            exit();
        }

        $pid = $this->sanitize($post['pid']);
        $name = $this->sanitize($post['name']);
        $price = $this->sanitize($post['price']);
        $image = $this->sanitize($post['image']);

        $checkWishlist = $this->conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $checkWishlist->execute([$name, $this->user_id]);

        $checkCart = $this->conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $checkCart->execute([$name, $this->user_id]);

        if ($checkWishlist->rowCount() > 0) {
            $this->messages[] = 'already added to wishlist!';
        } elseif ($checkCart->rowCount() > 0) {
            $this->messages[] = 'already added to cart!';
        } else {
            $insert = $this->conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
            $insert->execute([$this->user_id, $pid, $name, $price, $image]);
            $this->messages[] = 'added to wishlist!';
        }
    }

    public function addToCart($post) {
        if ($this->user_id === '') {
            header('Location: user_login.php');
            exit();
        }

        $pid = $this->sanitize($post['pid']);
        $name = $this->sanitize($post['name']);
        $price = $this->sanitize($post['price']);
        $image = $this->sanitize($post['image']);
        $qty = $this->sanitize($post['qty']);

        $checkCart = $this->conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $checkCart->execute([$name, $this->user_id]);

        if ($checkCart->rowCount() > 0) {
            $this->messages[] = 'already added to cart!';
        } else {
            $checkWishlist = $this->conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
            $checkWishlist->execute([$name, $this->user_id]);

            if ($checkWishlist->rowCount() > 0) {
                $delete = $this->conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
                $delete->execute([$name, $this->user_id]);
            }

            $insert = $this->conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
            $insert->execute([$this->user_id, $pid, $name, $price, $qty, $image]);
            $this->messages[] = 'added to cart!';
        }
    }
}

// Utilisation
$handler = new WishlistCartHandler($conn, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_wishlist'])) {
        $handler->addToWishlist($_POST);
    }

    if (isset($_POST['add_to_cart'])) {
        $handler->addToCart($_POST);
    }

    // Stocker les messages pour les afficher dans la vue HTML
    $_SESSION['messages'] = $handler->messages;
    header("Location: " . $_SERVER['HTTP_REFERER']); // Redirection vers la page précédente
    exit();
}
?>
</body>
</html>