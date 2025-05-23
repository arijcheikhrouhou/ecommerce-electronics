<?php
session_start();
var_dump($_SESSION);
require_once '../models/Database.php';
require_once '../models/users.php';

$messages = [];
$profileData = null;

$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
   $user = new Users($conn, $user_id);

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

      if ($user->updateProfile($name, $email)) {
         $messages[] = "Profil mis à jour avec succès.";
      } else {
         $messages[] = "Erreur lors de la mise à jour du profil.";
      }

      if (!empty($_POST['old_pass']) || !empty($_POST['new_pass']) || !empty($_POST['cpass'])) {
         $passwordMessages = $user->updatePassword($_POST['old_pass'], $_POST['new_pass'], $_POST['cpass']);
         $messages = array_merge($messages, $passwordMessages);
      }
   }

   $profileData = $user->data;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <title>Modifier le profil</title>
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=1.0">
</head>
<body>

<?php include '../components/user_header.php'; ?>

<?php if (!empty($messages)): ?>
   <?php foreach ($messages as $msg): ?>
      <div class="message"><?= htmlspecialchars($msg) ?></div>
   <?php endforeach; ?>
<?php endif; ?>

<?php if ($profileData): ?>
<section class="form-container">
   <form action="" method="post">
      <h3>Modifier le profil</h3>
      <input type="text" name="name" required class="box" placeholder="Nom" value="<?= htmlspecialchars($profileData['name']) ?>">
      <input type="email" name="email" required class="box" placeholder="Email" value="<?= htmlspecialchars($profileData['email']) ?>">
      <input type="password" name="old_pass" class="box" placeholder="Ancien mot de passe">
      <input type="password" name="new_pass" class="box" placeholder="Nouveau mot de passe">
      <input type="password" name="cpass" class="box" placeholder="Confirmer le mot de passe">
      <input type="submit" name="submit" class="btn" value="Mettre à jour">
   </form>
</section>
<?php else: ?>
   <p class="empty">Utilisateur non trouvé.</p>
<?php endif; ?>

<?php include '../components/footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>
