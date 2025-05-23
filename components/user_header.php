<?php
require_once '../models/Database.php'; 
$database = new Database();
$conn = $database->getConnection();

class User {
    private $conn;
    private $user_id;

    public function __construct($conn, $user_id) {
        $this->conn = $conn;
        $this->user_id = $user_id;
    }

    public function getCartCount() {
        $query = $this->conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $query->execute([$this->user_id]);
        return $query->rowCount();
    }

    public function getWishlistCount() {
        $query = $this->conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
        $query->execute([$this->user_id]);
        return $query->rowCount();
    }

    public function getProfile() {
        $query = $this->conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $query->execute([$this->user_id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}

class Message {
    public static function displayMessages($messages) {
        if (isset($messages)) {
            foreach ($messages as $msg) {
                echo '
                <div class="message">
                    <span>' . htmlspecialchars($msg) . '</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>';
            }
        }
    }
}

$message = []; 
$user_id = $_SESSION['user_id'] ?? null;

Message::displayMessages($message);

if ($user_id !== null) {
    $user = new User($conn, $user_id);
    $userProfile = $user->getProfile();
    $total_wishlist_counts = $user->getWishlistCount();
    $total_cart_counts = $user->getCartCount();
} else {
    $userProfile = null;
    $total_wishlist_counts = 0;
    $total_cart_counts = 0;
}
?>

<header class="header">
    <section class="flex">
        <a href="home.php" class="logo">Bloomera<span>.</span></a>
        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about.php">about</a>
            <a href="orders.php">orders</a>
            <a href="shop.php">shop</a>
            <a href="contact.php">contact</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php"><i class="fas fa-search"></i></a>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php if ($userProfile !== null) { ?>
                <p>welcome, <?= htmlspecialchars($userProfile["name"]); ?>!</p>
                <a href="update_user.php" class="btn">update profile</a>
                <div class="flex-btn">
                    <a href="user_register.php" class="option-btn">register</a>
                    <a href="user_login.php" class="option-btn">login</a>
                </div>
                <a href="../components/user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a>
            <?php } else { ?>
                <p>please login or register first!</p>
                <div class="flex-btn">
                    <a href="user_register.php" class="option-btn">register</a>
                    <a href="user_login.php" class="option-btn">login</a>
                </div>
            <?php } ?>
        </div>
    </section>
</header>
