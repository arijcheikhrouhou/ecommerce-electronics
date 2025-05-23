<?php
session_start();
require_once '../models/Database.php';
require_once '../models/users.php';

$database = new Database();
$conn = $database->getConnection();

$user = new users($conn);
$message = null;

if (isset($_POST['submit'])) {
   $user->setEmail($_POST['email'] ?? '');
   $user->setPassword($_POST['pass'] ?? '');
   $message = $user->login();

   if ($message === true) {
      header('location:home.php');
      exit();
   } else {
      $message = 'incorrect email or password!';
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Login</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css?v=1.0">
<style>
   
</style>
</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>login</h3>
      <?php if ($message): ?>
         <div class="message"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="submit">
      <p>don't have an account?</p>
      <a href="user_register.php" class="option-btn">register now</a>
   </form>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>
