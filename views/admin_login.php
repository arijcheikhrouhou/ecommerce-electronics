<?php
session_start();
require_once '../models/admins.php';

$adminModel = new Admin();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $password = $_POST['pass'] ?? '';

    $admin = $adminModel->login($name, $password);
    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8" />
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>


<?php if (!empty($error)) : ?>
   <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<section class="form-container">

<form action="" method="post">
   <h3>Login</h3>
   <p>Default username = <span>admin</span> & password = <span>111</span></p>
   <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   <input type="submit" value="Login" class="btn" name="submit">
</form>


</section>

</body>
</html>
