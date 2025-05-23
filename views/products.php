<?php
require_once '../models/database.php';
require_once '../models/products.php';
session_start();

// Redirection si l'admin n'est pas connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Connexion DB et modèle
$db = new Database();
$conn = $db->getConnection();
$productModel = new Products($conn);

$message = [];

// Contrôleur : Ajout produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    $img1 = $_FILES['image_01']['name'];
    $img2 = $_FILES['image_02']['name'];
    $img3 = $_FILES['image_03']['name'];

    $tmp1 = $_FILES['image_01']['tmp_name'];
    $tmp2 = $_FILES['image_02']['tmp_name'];
    $tmp3 = $_FILES['image_03']['tmp_name'];

    $size1 = $_FILES['image_01']['size'];
    $size2 = $_FILES['image_02']['size'];
    $size3 = $_FILES['image_03']['size'];

    if ($productModel->getByName($name)) {
        $message[] = 'Product name already exists!';
    } elseif ($size1 > 2000000 || $size2 > 2000000 || $size3 > 2000000) {
        $message[] = 'One or more image files are too large!';
    } else {
        if ($productModel->add($name, $details, $price, $img1, $img2, $img3)) {
            move_uploaded_file($tmp1, '../uploaded_img/' . $img1);
            move_uploaded_file($tmp2, '../uploaded_img/' . $img2);
            move_uploaded_file($tmp3, '../uploaded_img/' . $img3);
            $message[] = 'Product add with succefuly ';
        }
    }
}

// Contrôleur : Suppression
if (isset($_GET['delete'])) {
    $productModel->deleteProductWithImages($_GET['delete']);
    header('Location: products.php');
    exit;
}

// Vue
$products = $productModel->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Products</title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<section class="add-products">
   <h1 class="heading">Add Product</h1>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="text" name="name" class="box" placeholder="Enter product name" required>
      <input type="number" name="price" class="box" placeholder="Enter product price" required>
      <textarea name="details" class="box" placeholder="Enter product details" required></textarea>
      <input type="file" name="image_01" class="box" accept="image/*" required>
      <input type="file" name="image_02" class="box" accept="image/*" required>
      <input type="file" name="image_03" class="box" accept="image/*" required>
      <input type="submit" name="add_product" value="Add Product" class="btn">
   </form>

 
</section>

<section class="show-products">
   <h1 class="heading">Your Products</h1>
   <div class="box-container">
      <?php if (count($products) > 0): ?>
         <?php foreach ($products as $product): ?>
            <div class="box">
               <img src="../uploaded_img/<?= htmlspecialchars($product['image_01']); ?>" alt="">
               <div class="name"><?= htmlspecialchars($product['name']); ?></div>
               <div class="price"><?= htmlspecialchars($product['price']); ?> DT</div>
               <div class="details"><?= htmlspecialchars($product['details']); ?></div>
               <a href="update_product.php?update=<?= $product['id']; ?>" class="option-btn">Update</a>
               <a href="products.php?delete=<?= $product['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No products added yet!</p>
      <?php endif; ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
