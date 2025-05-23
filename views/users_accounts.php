<?php
session_start();
require_once '../models/Database.php';

// Vérifier que l'admin est connecté
$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('Location: admin_login.php');
    exit;
}

// Connexion à la base de données
$database = new Database();
$conn = $database->getConnection();

$message = null;

// Suppression d’un utilisateur (si formulaire posté)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $delete_id = (int)$_POST['delete_user_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$delete_id]);
        // Redirection avec message de succès
        header("Location: users_accounts.php?msg=" . urlencode("Utilisateur supprimé avec succès"));
        exit;
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// Récupérer tous les utilisateurs
try {
    $stmt = $conn->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8" />
   <title>Comptes Utilisateurs</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
   <link rel="stylesheet" href="../css/admin_style.css" />
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">
   <h1 class="heading">Comptes Utilisateurs</h1>

   <?php if ($message): ?>
       <p class="error-message"><?= htmlspecialchars($message) ?></p>
   <?php elseif (!empty($_GET['msg'])): ?>
       <p class="success-message"><?= htmlspecialchars($_GET['msg']) ?></p>
   <?php endif; ?>

   <div class="box-container">
      <?php if (!empty($users)) : ?>
         <?php foreach ($users as $user) : ?>
            <div class="box">
               <p> ID utilisateur : <span><?= (int)$user['id'] ?></span> </p>
               <p> Nom : <span><?= htmlspecialchars($user['name']) ?></span> </p>
               <p> Email : <span><?= htmlspecialchars($user['email']) ?></span> </p>

               <form method="post" onsubmit="return confirm('Supprimer ce compte ? Les données liées seront également supprimées.')">
                   <input type="hidden" name="delete_user_id" value="<?= (int)$user['id'] ?>" />
                   <button type="submit" class="delete-btn">Supprimer</button>
               </form>
            </div>
         <?php endforeach; ?>
      <?php else : ?>
         <p class="empty">Aucun compte utilisateur disponible.</p>
      <?php endif; ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
