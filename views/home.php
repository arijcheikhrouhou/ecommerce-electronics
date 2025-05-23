<?php


require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/products.php';
require_once __DIR__ . '/../models/wishlist.php';

$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'] ?? '';

$productModel = new products($conn);
$wishlistCart = new wishlist($conn, $user_id);
$wishlistCart->handleForm();

// Get latest products
$latestProducts = $productModel->getLatestProducts(6);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bloomera - Home</title>

   <!-- Swiper CSS -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=1.0">
<script src="../https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
</head>
<body>
   
<?php include '../components/user_header.php'; ?>

<div class="home-bg">
   <section class="home">
      <div class="swiper home-slider">
         <div class="swiper-wrapper">
    

            <div class="swiper-slide slide">
               <div class="image">
                  <img src="../images/home-img-2.png" alt="Latest Watches">
               </div>
               <div class="content">
                  <span>upto 50% off</span>
                  <h3>Latest Watches</h3>
                  <a href="shop.php" class="btn">Shop Now</a>
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <img src="../images/home-img-3.png" alt="Latest Headsets">
               </div>
               <div class="content">
                  <span>upto 50% off</span>
                  <h3>Latest Headsets</h3>
                  <a href="shop.php" class="btn">Shop Now</a>
               </div>
            </div>
         </div>
         <div class="swiper-pagination"></div>
      </div>
   </section>
</div>

<section class="category">
   <h1 class="heading">Shop By Category</h1>
   <div class="swiper category-slider">
      <div class="swiper-wrapper">
         <a href="category.php?category=laptop" class="swiper-slide slide">
            <img src="../images/icon-1.png" alt="laptop">
            <h3>laptop</h3>
         </a>

         <a href="category.php?category=tv" class="swiper-slide slide">
            <img src="../images/icon-2.png" alt="tv">
            <h3>tv</h3>
         </a>

         <a href="category.php?category=camera" class="swiper-slide slide">
            <img src="../images/icon-3.png" alt="camera">
            <h3>camera</h3>
         </a>

         <a href="category.php?category=mouse" class="swiper-slide slide">
            <img src="../images/icon-4.png" alt="mouse">
            <h3>mouse</h3>
         </a>

         <a href="category.php?category=fridge" class="swiper-slide slide">
            <img src="../images/icon-5.png" alt="fridge">
            <h3>fridge</h3>
         </a>

         <a href="category.php?category=washing" class="swiper-slide slide">
            <img src="../images/icon-6.png" alt="washing machine">
            <h3>washing machine</h3>
         </a>

         <a href="category.php?category=smartphone" class="swiper-slide slide">
            <img src="../images/icon-7.png" alt="smartphone">
            <h3>smartphone</h3>
         </a>

         <a href="category.php?category=watch" class="swiper-slide slide">
            <img src="../images/icon-8.png" alt="watch">
            <h3>watch</h3>
         </a>
      </div>
      <div class="swiper-pagination"></div>
   </div>
</section>



<?php include '../components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js">
   <script src="../https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
   <script src="../js/script.js"></script>

<script>
var swiper = new Swiper(".home-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable: true,
   },
   autoplay: {
      delay: 5000,
      disableOnInteraction: false,
   },
});

var swiper = new Swiper(".category-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable: true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
      },
      650: {
         slidesPerView: 3,
      },
      768: {
         slidesPerView: 4,
      },
      1024: {
         slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable: true,
   },
   breakpoints: {
      550: {
         slidesPerView: 2,
      },
      768: {
         slidesPerView: 2,
      },
      1024: {
         slidesPerView: 3,
      },
   },
});
</script>

</body>
</html>