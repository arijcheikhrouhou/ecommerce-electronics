<?php
session_start();
include '../models/Database.php';

class Userheader {
   private $conn;
   private $id;
   public $data;

   public function __construct($conn, $id) {
      $this->conn = $conn;
      $this->id = $id;
      $this->loadData();
   }

   private function loadData() {
      $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->execute([$this->id]);
      $this->data = $stmt->fetch(PDO::FETCH_ASSOC);
   }

   public function updateProfile($name, $email) {
      $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
      return $stmt->execute([$name, $email, $this->id]);
   }

   public function updatePassword($oldPassInput, $newPassInput, $cpassInput) {
      $messages = [];

      $oldHash = sha1($oldPassInput);
      $newHash = sha1($newPassInput);
      $confirmHash = sha1($cpassInput);

      if (empty($oldPassInput)) {
         $messages[] = 'Veuillez saisir l\'ancien mot de passe.';
      } elseif ($oldHash !== $this->data['password']) {
         $messages[] = 'L\'ancien mot de passe est incorrect.';
      } elseif ($newHash !== $confirmHash) {
         $messages[] = 'Le mot de passe de confirmation ne correspond pas.';
      } else {
         $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
         $stmt->execute([$confirmHash, $this->id]);
         $messages[] = 'Mot de passe mis à jour avec succès !';
         $this->loadData(); // Recharger les données
      }

      return $messages;
   }
}

// Connexion à la base
$database = new Database();
$conn = $database->getConnection();

// Récupération de l'utilisateur
$user_id = $_SESSION['user_id'] ?? '';
$message = [];

if (!empty($user_id)) {
   $user = new Userheader($conn, $user_id);

   if (isset($_POST['submit'])) {
      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

      if ($user->updateProfile($name, $email)) {
         $message[] = 'Profil mis à jour avec succès !';
      } else {
         $message[] = 'Erreur lors de la mise à jour du profil.';
      }

      if (!empty($_POST['old_pass']) || !empty($_POST['new_pass']) || !empty($_POST['cpass'])) {
         $message = array_merge($message, $user->updatePassword($_POST['old_pass'], $_POST['new_pass'], $_POST['cpass']));
      }
   }

   $fetch_profile = $user->data;
} else {
   $fetch_profile = null;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <title>Update Profile</title>
   <meta name="viewport" content="../width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=1.0">

</head>
<body>

<?php
if (!empty($message)) {
   foreach ($message as $msg) {
      echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
   }
}
?>

<?php include '../components/user_header.php'; ?>

<?php if ($fetch_profile): ?>
<section class="form-container">
   <form action="" method="post">
      <h3>Update Profile</h3>
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" value="<?= htmlspecialchars($fetch_profile["name"]); ?>">
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" value="<?= htmlspecialchars($fetch_profile["email"]); ?>">
      <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="20" class="box">
      <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20" class="box">
      <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20" class="box">
      <input type="submit" value="Update Now" class="btn" name="submit">
   </form>
</section>
<?php else: ?>
   <p class="empty">Utilisateur non trouvé.</p>
<?php endif; ?>

<?php include '../components/footer.php'; ?>

<script src="../js/script.js"></script>
</body>
</html>
