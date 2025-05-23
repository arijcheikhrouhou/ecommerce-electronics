<?php
include_once '../models/cart.php';
include_once '../models/Database.php';
session_start();

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$cartModel = new cart($conn);
$userId = $_SESSION['user_id'];
$cartItems = $cartModel->getItemsByUser($userId);

$message = [];
$grandTotal = 0;
$totalProducts = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $number = htmlspecialchars(trim($_POST['number']));
    $email = htmlspecialchars(trim($_POST['email']));
    $method = htmlspecialchars(trim($_POST['method']));
    $address = htmlspecialchars(
        trim($_POST['flat']) . ', ' .
        trim($_POST['street']) . ', ' .
        trim($_POST['city']) . ', ' .
        trim($_POST['state']) . ', ' .
        trim($_POST['country']) . ' - ' .
        trim($_POST['pin_code'])
    );





































    
    if (!empty($cartItems)) {
        $products = [];
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $products[] = $item['name'] . ' (' . $item['quantity'] . ')';
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $totalProducts = implode(' - ', $products);
        $placedOn = date('Y-m-d');

        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, number, email, address, method, total_products, total_price, payment_status, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
        $stmt->execute([$userId, $name, $number, $email, $address, $method, $totalProducts, $totalPrice, $placedOn]);

        $cartModel->clearCart($userId);

        $message[] = 'Order placed successfully!';
        $cartItems = []; // Réinitialiser l'affichage du panier
    } else {
        $message[] = 'Your cart is empty.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="checkout-orders">
    <form action="" method="POST">
        <h3>Your Orders</h3>
        <div class="display-orders">
            <?php if (!empty($cartItems)): ?>
                <?php foreach ($cartItems as $item): ?>
                    <p><?= htmlspecialchars($item['name']) ?> <span>(<?= htmlspecialchars($item['price']) ?> x <?= $item['quantity'] ?>)</span></p>
                    <?php $grandTotal += $item['price'] * $item['quantity']; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty">Your cart is empty!</p>
            <?php endif; ?>
            <div class="grand-total">Grand total: <span>DT<?= htmlspecialchars($grandTotal) ?>/-</span></div>
        </div>

        <h3>Place Your Orders</h3>
        <div class="flex">
            <div class="inputBox"><span>Your name :</span><input type="text" name="name" class="box" required></div>
            <div class="inputBox"><span>Your number :</span><input type="number" name="number" class="box" required></div>
            <div class="inputBox"><span>Your email :</span><input type="email" name="email" class="box" required></div>
            <div class="inputBox">
                <span>Payment method :</span>
                <select name="method" class="box" required>
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="credit card">Credit Card</option>
                    <option value="carte e-dinars">Carte e-Dinars</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <div class="inputBox"><span>Address line 01 :</span><input type="text" name="flat" class="box" required></div>
            <div class="inputBox"><span>Address line 02 :</span><input type="text" name="street" class="box" required></div>
            <div class="inputBox"><span>City :</span><input type="text" name="city" class="box" required></div>
            <div class="inputBox"><span>State :</span><input type="text" name="state" class="box" required></div>
            <div class="inputBox"><span>Country :</span><input type="text" name="country" class="box" required></div>
            <div class="inputBox"><span>Pin Code :</span><input type="number" name="pin_code" class="box" required></div>
        </div>

        <input type="submit" name="order" class="btn <?= ($grandTotal > 0) ? '' : 'disabled'; ?>" value="Place Order">
    </form>
</section>

<?php if (!empty($message)) foreach ($message as $msg): ?>
    <p class="message"><?= $msg ?></p>
<?php endforeach; ?>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>
