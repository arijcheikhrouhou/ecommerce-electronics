<?php
session_start();

// Vérification de la session admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Inclusion des modèles nécessaires
require_once '../models/Database.php';
require_once '../models/messages.php';  // Note : la classe est nommée Message (singulier)

$db = new Database();
$conn = $db->getConnection();

// Instanciation du modèle Message avec la connexion PDO
$messagesModel = new Message($conn);

// Traitement de la suppression d'un message via POST (plus sûr que GET)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    $messagesModel->deleteMessage($deleteId);
    header('Location: messages.php');
    exit;
}

// Récupération de tous les messages
$messages = $messagesModel->getAllMessages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <title>Messages</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
  
   <link rel="stylesheet" href="../css/admin_style.css" />

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contacts">
    <h1 class="heading">Messages</h1>
    <div class="box-container">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $msg): ?>
            <div class="box">
                <p>User ID : <span><?= htmlspecialchars($msg['user_id']); ?></span></p>
                <p>Name : <span><?= htmlspecialchars($msg['name']); ?></span></p>
                <p>Email : <span><?= htmlspecialchars($msg['email']); ?></span></p>
                <p>Number : <span><?= htmlspecialchars($msg['number']); ?></span></p>
                <p>Message : <span><?= htmlspecialchars($msg['message']); ?></span></p>
                <form method="post" onsubmit="return confirm('Delete this message?');">
                    <input type="hidden" name="delete_id" value="<?= (int)$msg['id']; ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">You have no messages.</p>
        <?php endif; ?>
    </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
