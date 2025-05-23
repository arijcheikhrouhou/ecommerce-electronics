<?php

if(file_exists('../components/connect.php')) {
   include '../components/connect.php';
} else {
   include 'components/connect.php';
}

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$message = []; // Tableau pour stocker les messages

if(isset($_POST['send'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   // Vérifie si le message existe déjà
   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = '<div class="message error">⚠️ Message already sent!</div>';
   } else {
      // Insère le nouveau message
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);
      $message[] = '<div class="message success">✅ Thank you! Your message has been sent successfully. We will contact you soon.</div>';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Us - Bloomera</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css ">
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include '../components/user_header.php'; ?>

<section class="contact">

   <h1 class="heading">GET IN TOUCH</h1>

   <!-- Affichage des messages -->
   <?php if (!empty($message)): ?>
      <?php foreach ($message as $msg): ?>
         <?= $msg; ?>
      <?php endforeach; ?>
   <?php endif; ?>

   <form action="" method="post">
      <h3>Send Us a Message</h3>
      <input type="text" name="name" placeholder="Your Full Name" required maxlength="50" class="box">
      <input type="email" name="email" placeholder="Your Email Address" required maxlength="50" class="box">
      <input type="number" name="number" placeholder="Your Phone Number" required min="0" max="9999999999" maxlength="10" class="box">
      <textarea name="msg" class="box" placeholder="How can we help you today?" required maxlength="500" cols="30" rows="10"></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>

</section>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Us - Bloomera</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
</head>
<body>
   
<?php include '../components/user_header.php'; ?>

<section class="contact">

   <h1 class="heading">GET IN TOUCH</h1>

   <form action="" method="post">
      <h3>Send Us a Message</h3>
      <input type="text" name="name" placeholder="Your Full Name" required maxlength="50" class="box">
      <input type="email" name="email" placeholder="Your Email Address" required maxlength="50" class="box">
      <input type="number" name="number" placeholder="Your Phone Number" required min="0" max="9999999999" maxlength="10" class="box">
      <textarea name="msg" class="box" placeholder="How can we help you today?" required maxlength="500" cols="30" rows="10"></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/script.js"></script>

</body>
</html>
