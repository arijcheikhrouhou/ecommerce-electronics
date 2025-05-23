<?php
// Démarrer la session
session_start();

// === Connexion à la base de données ===
require_once '../models/Database.php'; // Doit contenir la classe Database

$database = new Database();
$conn = $database->getConnection(); // Création de la connexion PDO

// === Charger la classe products ===
require_once '../models/products.php'; // Assure-toi que ce fichier existe

// Utilisation de la connexion pour créer un objet Product
$product = new products($conn);

$message = [];

// === Gestion du formulaire de mise à jour ===
if (isset($_POST['update'])) {
    $pid = $_POST['pid'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    // Mise à jour des champs principaux
    $product->updateProduct($pid, $name, $price, $details);
$message[] = 'Product updated successfully!';

    // Gestion des images
    foreach (['image_01', 'image_02', 'image_03'] as $imgKey) {
        if (!empty($_FILES[$imgKey]['name'])) {
            $oldImage = $_POST["old_$imgKey"];
            $image = $product->uploadImage($_FILES[$imgKey], '../uploaded_img/');
            if ($image) {
$product->updateProductImage($pid, $imgKey, $image, $oldImage);
               // unlink('../uploaded_img/' . $oldImage);
                $message[] = ucfirst(str_replace('_', ' ', $imgKey)) . ' updated!';
            } else {
                $message[] = ucfirst(str_replace('_', ' ', $imgKey)) . ' is too large!';
            }
        }
    }
}
?>

<!-- ----- VIEW ----- -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">
    <h1 class="heading">Update Product</h1>

    

   <?php
$update_id = $_GET['update'] ?? null;

$productData = $product->getProductById($update_id); // Retourne un tableau associatif

if ($productData): // Si produit trouvé
?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="pid" value="<?= htmlspecialchars($productData['id']); ?>">
        <input type="hidden" name="old_image_01" value="<?= htmlspecialchars($productData['image_01']); ?>">
        <input type="hidden" name="old_image_02" value="<?= htmlspecialchars($productData['image_02']); ?>">
        <input type="hidden" name="old_image_03" value="<?= htmlspecialchars($productData['image_03']); ?>">

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

     
        <!-- Le reste du formulaire -->


           <input type="text" name="name" required class="box" value="<?= htmlspecialchars($productData['name']); ?>">
<input type="number" name="price" required class="box" value="<?= htmlspecialchars($productData['price']); ?>">
<textarea name="details" class="box" required><?= htmlspecialchars($productData['details']); ?></textarea>

            <span>Update Image 01</span>
            <input type="file" name="image_01" class="box" accept="image/*">

            <span>Update Image 02</span>
            <input type="file" name="image_02" class="box" accept="image/*">

            <span>Update Image 03</span>
            <input type="file" name="image_03" class="box" accept="image/*">

            <div class="flex-btn">
                <input type="submit" name="update" class="btn" value="Update">
                <a href="products.php" class="option-btn">Go Back</a>
            </div>
        </form>
      

    <?php else: ?>
        <p class="empty">No product found!</p>
    <?php endif; ?>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>