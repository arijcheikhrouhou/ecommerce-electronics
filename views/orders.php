<?php
session_start();
include '../models/Database.php';
include '../models/orders.php';

$database = new Database();
$db = $database->getConnection();

$orderManager = new Order($db);

// Si c'est un utilisateur normal
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $orders = $orderManager->getOrdersByUser($user_id);
} else {
    $orders = [];
}
?>

<!-- Affichage HTML comme tu l’as déjà fait -->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Orders</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="orders">
   <h1 class="heading">placed orders</h1>
   <div class="box-container">

   <?php if ($user_id == ''): ?>
      <p class="empty">please login to see your orders</p>
   <?php else: ?>
      <?php
         $orders = $orderManager->getOrdersByUser($user_id);
         if ($orders->rowCount() > 0):
            while ($order = $orders->fetch(PDO::FETCH_ASSOC)):
      ?>
      <div class="box">
         <p>placed on : <span><?= $order['placed_on']; ?></span></p>
         <p>name : <span><?= $order['name']; ?></span></p>
         <p>email : <span><?= $order['email']; ?></span></p>
         <p>number : <span><?= $order['number']; ?></span></p>
         <p>address : <span><?= $order['address']; ?></span></p>
         <p>payment method : <span><?= $order['method']; ?></span></p>
         <p>your orders : <span><?= $order['total_products']; ?></span></p>
         <p>total price : <span>$<?= $order['total_price']; ?>/-</span></p>
         <p>payment status : 
            <span style="color:<?= ($order['payment_status'] === 'pending') ? 'red' : 'green'; ?>">
               <?= $order['payment_status']; ?>
            </span>
         </p>
      </div>
      <?php endwhile; ?>
      <?php else: ?>
         <p class="empty">no orders placed yet!</p>
      <?php endif; ?>
   <?php endif; ?>

   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>