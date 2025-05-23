<?php
session_start(); 
require_once '../models/Database.php';
require_once '../models/products.php';

$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'] ?? ''; 
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart']) || isset($_POST['add_to_wishlist'])) {
        
        $pid = $_POST['pid'];
        $qty = $_POST['qty'] ?? 1;

        if ($user_id) {
            if (isset($_POST['add_to_cart'])) {
                $message = "<p style='color:green;'>Product added to cart!</p>";
                // Ajoute ici le traitement réel du panier si besoin
            }

            if (isset($_POST['add_to_wishlist'])) {
                $check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND pid = ?");
                $check->execute([$user_id, $pid]);

                if ($check->rowCount() > 0) {
                    $message = "<p style='color:red;'>Already in your wishlist!</p>";
                } else {
                    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $user_id,
                        $pid,
                        $_POST['name'],
                        $_POST['price'],
                        $_POST['image']
                    ]);
                    $message = "<p style='color:green;'>Product added to wishlist!</p>";
                }
            }
        } else {
header('Location: ../views/user_login.php');
    exit();        }
    }
}

// Récupération du produit
$productData = null;
$pid = $_GET['pid'] ?? null;

if ($pid) {
    $product = new products($conn);
    $productData = $product->getById($pid);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Quick View</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=1.0">

</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="quick-view">
   <h1 class="heading">Quick View</h1>

   <div class="box-container">
   <?php if ($productData): ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= htmlspecialchars($productData['id']); ?>">
         <input type="hidden" name="name" value="<?= htmlspecialchars($productData['name']); ?>">
         <input type="hidden" name="price" value="<?= htmlspecialchars($productData['price']); ?>">
         <input type="hidden" name="image" value="<?= htmlspecialchars($productData['image_01']); ?>">

         <div class="row">
            <div class="image-container">
               <div class="main-image">
                  <img src="../uploaded_img/<?= htmlspecialchars($productData['image_01']); ?>" alt="">
               </div>
               <div class="sub-image">
                  <img src="../uploaded_img/<?= htmlspecialchars($productData['image_01']); ?>" alt="">
                  <img src="../uploaded_img/<?= htmlspecialchars($productData['image_02']); ?>" alt="">
                  <img src="../uploaded_img/<?= htmlspecialchars($productData['image_03']); ?>" alt="">
               </div>
            </div>

            <div class="content">
   <div class="name"><?= htmlspecialchars($productData['name']); ?></div>
   <div class="flex">
      <div class="price"><span>DT</span><?= htmlspecialchars($productData['price']); ?><span>/-</span></div>
      <input type="number" name="qty" class="qty" min="1" max="99" value="1"
         onkeypress="if(this.value.length == 2) return false;">
   </div>



<?php
$details = $productData['details'];

if (is_string($details)) {
    $jsonDecoded = json_decode($details, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($jsonDecoded)) {
        $details = implode(', ', $jsonDecoded);
    } else {
        $unserialized = @unserialize($details);
        if ($unserialized !== false && is_array($unserialized)) {
            $details = implode(', ', $unserialized);
        }
    }
}

if (is_array($details)) {
    $details = implode(', ', $details);
} elseif (!is_string($details)) {
    $details = print_r($details, true);
}

?>



<div class="details"><?= htmlspecialchars($details); ?></div>

   <div class="flex-btn">
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
      <input class="option-btn" type="submit" name="add_to_wishlist" value="Add to Wishlist">
   </div>
</div>

         </div>
      </form>
   <?php else: ?>
      <p class="empty"><?= $pid ? "Product not found!" : "No product selected!" ?></p>
   <?php endif; ?>
   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>