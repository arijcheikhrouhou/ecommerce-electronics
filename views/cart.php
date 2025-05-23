<?php
session_start();
require_once '../models/Database.php';
$db = new Database();
$conn = $db->getConnection();
// ======== CONTROLLER ========
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:user_login.php');
    exit;
}

$message = [];
$grand_total = 0;

// Supprimer un produit du panier
if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$cart_id]);
}

// Supprimer tous les produits du panier
if (isset($_GET['delete_all'])) {
    $delete_cart_items = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_items->execute([$user_id]);
    header('location:cart.php');
    exit;
}

// Mettre à jour la quantité
if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);
    $message[] = 'Quantité mise à jour !';
}

// Récupérer les articles du panier
$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart->execute([$user_id]);
$cart_items = $select_cart->fetchAll(PDO::FETCH_ASSOC);

// Calculer le total
foreach ($cart_items as $item) {
    $grand_total += $item['price'] * $item['quantity'];
}

$disabled = $grand_total > 1 ? '' : 'disabled';

?>

<!-- ======== VIEW ======== -->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Cart</title>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css?v=1.0">
</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="products shopping-cart">
   <h3 class="heading">Your Cart</h3>

   <?php foreach ($message as $msg): ?>
      <p class="message"><?= $msg ?></p>
   <?php endforeach; ?>

   <div class="box-container">
      <?php if (!empty($cart_items)): ?>
         <?php foreach ($cart_items as $item): 
            $sub_total = $item['price'] * $item['quantity'];
         ?>
         <form action="" method="post" class="box">
            <input type="hidden" name="cart_id" value="<?= $item['id']; ?>">
            <a href="quick_view.php?pid=<?= $item['pid']; ?>" class="fas fa-eye"></a>
            <img src="../uploaded_img/<?= $item['image']; ?>" alt="">
            <div class="name"><?= htmlspecialchars($item['name']); ?></div>
            <div class="flex">
               <div class="price">$<?= $item['price']; ?></div>
               <input type="number" name="qty" class="qty" min="1" max="99"
                  onkeypress="if(this.value.length == 2) return false;" value="<?= $item['quantity']; ?>">
               <button type="submit" class="fas fa-edit" name="update_qty"></button>
            </div>
            <div class="sub-total">Subtotal : <span>$<?= $sub_total; ?></span></div>
            <input type="submit" value="Delete" onclick="return confirm('Delete this item from cart?');"
               class="delete-btn" name="delete">
         </form>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">Your cart is empty.</p>
      <?php endif; ?>
   </div>

   <div class="cart-total">
      <p>Total : <span>$<?= $grand_total; ?></span></p>
      <a href="shop.php" class="option-btn">Continue Shopping</a>
      <a href="cart.php?delete_all" class="delete-btn <?= $disabled ?>" onclick="return confirm('Delete all items?');">Delete All</a>
      <a href="checkout.php" class="btn <?= $disabled ?>">Proceed to Checkout</a>
   </div>

</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>

</body>
</html>
