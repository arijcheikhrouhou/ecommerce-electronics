<?php
session_start();

// Redirection si non connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

require_once '../models/Database.php';
require_once '../models/OrderModel.php';

$database = new Database();
$conn = $database->getConnection();

$orderModel = new OrderModel($conn);

$message = [];

// Mise à jour du statut du paiement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $orderId = (int)$_POST['order_id'];
    $paymentStatus = filter_input(INPUT_POST, 'payment_status', FILTER_SANITIZE_STRING);

    if ($orderId > 0 && in_array($paymentStatus, ['pending', 'completed'])) {
        if ($orderModel->updatePaymentStatus($orderId, $paymentStatus)) {
            $message[] = "Statut de paiement mis à jour.";
        } else {
            $message[] = "Erreur lors de la mise à jour du statut.";
        }
    } else {
        $message[] = "Données invalides.";
    }
}

// Suppression d'une commande
if (isset($_GET['delete'])) {
    $orderId = (int)$_GET['delete'];
    if ($orderId > 0) {
        if ($orderModel->deleteOrder($orderId)) {
            header('Location: placed_orders.php');
            exit;
        } else {
            $message[] = "Impossible de supprimer cette commande.";
        }
    }
}

// Récupération des commandes
$orders = $orderModel->getAllOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  
   <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">
<h1 class="heading">placed orders</h1>

<div class="box-container">

   <?php if (!empty($orders)): ?>
      <?php foreach ($orders as $order): ?>
         <div class="box">
            <p>placed on : <span><?= htmlspecialchars($order['placed_on']) ?></span></p>
            <p>name : <span><?= htmlspecialchars($order['name']) ?></span></p>
            <p>number : <span><?= htmlspecialchars($order['number']) ?></span></p>
            <p>address : <span><?= htmlspecialchars($order['address']) ?></span></p>
            <p>total products : <span><?= htmlspecialchars($order['total_products']) ?></span></p>
            <p>total price : <span>DT<?= htmlspecialchars($order['total_price']) ?>/-</span></p>
            <p>payment method : <span><?= htmlspecialchars($order['method']) ?></span></p>
            <form action="" method="post">
               <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
               <select name="payment_status" class="select">
                  <option selected disabled><?= htmlspecialchars($order['payment_status']) ?></option>
                  <option value="pending">pending</option>
                  <option value="completed">completed</option>
               </select>
               <div class="flex-btn">
                  <input type="submit" value="update" class="option-btn" name="update_payment">
                  <a href="placed_orders.php?delete=<?= $order['id'] ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
               </div>
            </form>
         </div>
      <?php endforeach; ?>
   <?php else: ?>
      <p class="empty">no orders placed yet!</p>
   <?php endif; ?>

</div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
