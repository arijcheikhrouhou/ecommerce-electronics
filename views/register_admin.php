<?php
session_start();
require_once '../models/admins.php';

$adminModel = new Admin();
$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $cpass = $_POST['cpass'] ?? '';

    // Vérification simple des champs
    if (empty($name) || empty($pass) || empty($cpass)) {
        $message[] = "Tous les champs sont obligatoires.";
    } elseif ($pass !== $cpass) {
        $message[] = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'admin existe déjà
        $stmt = $adminModel->checkIfExists($name);
        if ($stmt->rowCount() > 0) {
            $message[] = "Ce nom d'utilisateur existe déjà.";
        } else {
            // Enregistrer l'admin (stockage en clair ici, idéalement hasher le mot de passe)
            $adminModel->registerAdmin($name, $pass);
            $message[] = "Compte admin enregistré avec succès.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <title>Enregistrer un admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Enregistrer un admin</h3>

      <?php
      if (!empty($message)) {
         foreach ($message as $msg) {
            echo '
            <div class="message">
               <span>' . htmlspecialchars($msg) . '</span>
               <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
         }
      }
      ?>

      <input type="text" name="name" required placeholder="Nom d'utilisateur" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirmer mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Enregistrer" class="btn" name="submit">
   </form>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
