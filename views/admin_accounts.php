<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

require_once '../models/admins.php';

$admin_id = $_SESSION['admin_id'];
$adminModel = new Admin();

// Suppression si demandé
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    if ($deleteId !== $admin_id) {  // éviter suppression de soi-même
        $adminModel->deleteAdmin($deleteId);
        header('Location: admin_accounts.php');  // ici rediriger vers la même page
        exit;
    }
}

// Récupérer tous les admins
$admins = $adminModel->getAllAdmins();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Admin Accounts</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">
   <h1 class="heading">Administrator Accounts</h1>

   <div class="box-container">

      <div class="box">
         <p>Add an administrator</p>
         <a href="register_admin.php" class="option-btn">Register Admin</a>
      </div>

      <?php if (!empty($admins)) : ?>
         <?php foreach ($admins as $admin) : ?>
         <div class="box">
            <p>admin id : <span><?= htmlspecialchars($admin['id']) ?></span></p>
            <p>admin name : <span><?= htmlspecialchars($admin['name']) ?></span></p>
            <div class="flex-btn">
               <?php if ($admin['id'] != $admin_id) : ?>
                  <a href="admin_accounts.php?delete=<?= $admin['id'] ?>" onclick="return confirm('Delete this account?');" class="delete-btn">Delete</a>
               <?php endif; ?>
               <?php if ($admin['id'] == $admin_id) : ?>
                  <a href="update_profile.php" class="option-btn">Update</a>
               <?php endif; ?>
            </div>
         </div>
         <?php endforeach; ?>
      <?php else : ?>
         <p class="empty">No accounts found.</p>
      <?php endif; ?>

   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
