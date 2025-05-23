<?php
session_start();
include '../models/Database.php';
require_once '../models/wishlist.php';

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

// Vérification de la session utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$wishlistModel = new Wishlist($conn, $user_id);

// Contrôleur : Traitement des actions utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete']) && isset($_POST['wishlist_id'])) {
        $wishlistModel->deleteItem($_POST['wishlist_id']);
    }
}

if (isset($_GET['delete_all'])) {
    $wishlistModel->deleteAllItems();
    header('Location: wishlist.php');
    exit;
}

// Données pour la vue
$wishlist_items = $wishlistModel->getItems();
$grand_total = array_sum(array_column($wishlist_items, 'price'));
?>

<!-- Vue : Affichage HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css?v=1.0">
</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="products">
    <h3 class="heading">WISHLIST</h3>

    <div class="box-container">
        <?php if (!empty($wishlist_items)): ?>
            <?php foreach ($wishlist_items as $item): ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="pid" value="<?= $item['pid']; ?>">
                    <input type="hidden" name="wishlist_id" value="<?= $item['id']; ?>">
                    <input type="hidden" name="name" value="<?= $item['name']; ?>">
                    <input type="hidden" name="price" value="<?= $item['price']; ?>">
                    <input type="hidden" name="image" value="<?= $item['image']; ?>">

                    <a href="quick_view.php?pid=<?= $item['pid']; ?>" class="fas fa-eye"></a>
                    <img src="../uploaded_img/<?= $item['image']; ?>" alt="">
                    <div class="name"><?= htmlspecialchars($item['name']); ?></div>

                    <div class="flex">
                        <div class="price">$<?= $item['price']; ?>/-</div>
                        <input type="number" name="qty" class="qty" min="1" max="99" value="1"
                               onkeypress="if(this.value.length == 2) return false;">
                    </div>

                    <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
                    <input type="submit" value="Delete Item" class="delete-btn" name="delete"
                           onclick="return confirm('Delete this item?');">
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">Your wishlist is empty.</p>
        <?php endif; ?>
    </div>

    <div class="wishlist-total">
        <p>Total : <span>$<?= $grand_total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">Continue Shopping</a>
        <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 0 ? '' : 'disabled'); ?>"
           onclick="return confirm('Delete all items?');">Delete All</a>
    </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>