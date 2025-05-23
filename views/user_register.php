<?php
session_start();
require_once '../models/Database.php';
require_once '../models/users.php';

$database = new Database();
$conn = $database->getConnection();

$user = new users($conn);
$message = []; // ✅ Tu l'as déjà (parfait !)
$messages = []; // ✅ Ajout de cette ligne pour corriger l'erreur
$success = false;
if(isset($_POST['submit'])){
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
   $cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);

   if ($select_user->rowCount() > 0) {
    $messages[] = ['type' => 'error', 'text' => 'Email already exists!'];
} else {
    if ($pass != $cpass) {
        $messages[] = ['type' => 'error', 'text' => 'Passwords do not match!'];
    } else {
        $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
        $insert_user->execute([$name, $email, sha1($pass)]);
        $messages[] = ['type' => 'success', 'text' => 'Registration successful! You can now <a href="../view/user_login.php">log in</a>.'];
    }
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register - Bloomera</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
<style>
 .alert {
   padding: 10px;
   margin-bottom: 10px;
   border-radius: 5px;
   font-weight: bold;
   text-align: center;
}

.alert-success {
   background-color: #d1e7dd;
   color: #0f5132;
   border: 1px solid #badbcc;
}

.alert-error {
   background-color: #f8d7da;
   color: #842029;
   border: 1px solid #f5c2c7;
}

</style>
</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>REGISTER NOW</h3>
<?php if (!empty($messages)): ?>
   <?php foreach ($messages as $msg): ?>
      <div class="alert alert-<?= htmlspecialchars($msg['type']) ?>">
         <?= $msg['type'] === 'success' ? $msg['text'] : htmlspecialchars($msg['text']) ?>
      </div>
   <?php endforeach; ?>
<?php endif; ?>



      <input type="text" name="name" required placeholder="enter your name" maxlength="50" class="box">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box">
      <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20" class="box">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>already have an account? <a href="user_login.php">login now</a></p>
   </form>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/script.js"></script>

</body>
</html>
