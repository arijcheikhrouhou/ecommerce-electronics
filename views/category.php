<?php
session_start();

include_once '../models/Database.php';
include_once '../components/wishlist_cart.php'; // gère POST add_to_wishlist & add_to_cart
include_once '../models/products.php';

$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'] ?? '';

$productModel = new products($conn);

$category = $_GET['category'] ?? '';
$products = [];

if (!empty($category)) {
    $products = $productModel->getProductsByCategory($category);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <title>Category - <?= htmlspecialchars($category) ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=1.0">

</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="products">
   <h1 class="heading"><?= htmlspecialchars($category) ?: 'Category' ?></h1>
   <div class="box-container">

   <?php if ($products && $products->rowCount() > 0): ?>
      <?php while ($fetch_product = $products->fetch(PDO::FETCH_ASSOC)): ?>
         <form action="" method="post" class="box">
            <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_product['id']); ?>" />
            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_product['name']); ?>" />
            <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_product['price']); ?>" />
            <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_product['image_01']); ?>" />
            <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
            <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_product['id']); ?>" class="fas fa-eye"></a>
            <img src="../uploaded_img/<?= htmlspecialchars($fetch_product['image_01']); ?>" alt="<?= htmlspecialchars($fetch_product['name']); ?>" />
            <div class="name"><?= htmlspecialchars($fetch_product['name']); ?></div>
            <div class="flex">
               <div class="price"><span>DNT</span><?= htmlspecialchars($fetch_product['price']); ?><span>/-</span></div>
               <input
                  type="number"
                  name="qty"
                  class="qty"
                  min="1"
                  max="99"
                  value="1"
                  onkeypress="if(this.value.length == 2) return false;"
               />
            </div>
            <input type="submit" value="add to cart" class="btn" name="add_to_cart" />
         </form>
      <?php endwhile; ?>
   <?php else: ?>
      <p class="empty">No products found!</p>
   <?php endif; ?>

   </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>

</body>
</html>
