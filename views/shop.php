<?php
session_start();
require_once '../models/Database.php';
require_once '../models/products.php';

$database = new Database();
$conn = $database->getConnection();

$productModel = new products($conn);
$products = $productModel->getAllProducts();

$user_id = $_SESSION['user_id'] ?? '';

include '../components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=1.0">

</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="products">
    <h1 class="heading">Latest Products</h1>
    <div class="box-container">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= htmlspecialchars($product['id']) ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($product['name']) ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($product['price']) ?>">
                <input type="hidden" name="image" value="<?= htmlspecialchars($product['image_01']) ?>">
                <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                <a href="quick_view.php?pid=<?= htmlspecialchars($product['id']) ?>" class="fas fa-eye"></a>
                <img src="../uploaded_img/<?= htmlspecialchars($product['image_01']) ?>" alt="">
                <div class="name"><?= htmlspecialchars($product['name']) ?></div>
                <div class="flex">
                    <div class="price"><span>DT</span><?= htmlspecialchars($product['price']) ?><span>/-</span></div>
                    <input type="number" name="qty" class="qty" min="1" max="99" value="1"
                           onkeypress="if(this.value.length == 2) return false;">
                </div>
                <input type="submit" value="Add to cart" class="btn" name="add_to_cart">
            </form>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty">No products found!</p>
    <?php endif; ?>
    </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>
