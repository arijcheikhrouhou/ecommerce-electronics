<!-- view/about_view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=1.0">

</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="../images/about-img.svg" alt="">
      </div>
      <div class="content">
         <h3>Why Choose Blooemra for Your Electronics and Phones?</h3>
         <p>
            At Blooemra, we believe technology should empower your everyday life.
            Our mission is to offer high-quality, reliable, and cutting-edge electronics—
            from the latest smartphones to essential tech accessories—all at competitive prices.
            We carefully select trusted brands and innovative products to ensure performance,
            durability, and style. Whether you're upgrading your phone, looking for smart home
            solutions, or shopping for the perfect gift, Blooemra is your go-to tech partner—
            bringing innovation to your fingertips, one device at a time.
         </p>
         <a href="contact.php" class="btn">contact us</a>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="heading">client's reviews</h1>
   <div class="swiper reviews-slider">
      <div class="swiper-wrapper">
         <?php for ($i = 1; $i <= 6; $i++): ?>
         <div class="swiper-slide slide">
            <img src="../images/pic-<?= $i ?>.png" alt="">
            <p>Good quality thnx.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>mohamed abid</h3>
         </div>
         <?php endfor; ?>
      </div>
      <div class="swiper-pagination"></div>
   </div>
</section>

<?php include '../components/footer.php'; ?>

<script src="../https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="../js/script.js"></script>
<script>
   var swiper = new Swiper(".reviews-slider", {
      loop: true,
      spaceBetween: 20,
      pagination: {
         el: ".swiper-pagination",
         clickable: true,
      },
      breakpoints: {
         0: {
            slidesPerView: 1,
         },
         768: {
            slidesPerView: 2,
         },
         991: {
            slidesPerView: 3,
         },
      },
   });
</script>

</body>
</html>

