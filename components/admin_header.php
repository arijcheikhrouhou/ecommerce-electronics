<?php
require_once '../models/Database.php'; 

if (!class_exists('AdminHeader')) {
    class AdminHeader  {
        private $conn;
        private $admin_id;

        public function __construct($conn, $admin_id) {
            $this->conn = $conn;
            $this->admin_id = $admin_id;
        }

        public function isLoggedIn() {
            return !empty($this->admin_id);
        }

        public function getProfile() {
            if (!$this->isLoggedIn()) return null;

            $stmt = $this->conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $stmt->execute([$this->admin_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function displayMessages($messages) {
            if (!empty($messages)) {
                foreach ($messages as $msg) {
                    echo '
                    <div class="message">
                        <span>' . htmlspecialchars($msg) . '</span>
                        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>';
                }
            }
        }
    }
}

$database = new Database();
$conn = $database->getConnection();

$admin_id = $_SESSION['admin_id'] ?? null;

$admin = new AdminHeader($conn, $admin_id);

AdminHeader::displayMessages($message ?? []);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
<header class="header">
   <section class="flex">
      <a href="../views/dashboard.php" class="logo">
 <img src="../images/logo.png" alt="Logo Bloomera" style="height: 30px; width: 70px;">      
      <nav class="navbar">
         <a href="../views/dashboard.php">home</a>
         <a href="../views/products.php">products</a>
         <a href="../views/placed_orders.php">orders</a>
         <a href="../views/admin_accounts.php">admins</a>
         <a href="../views/users_accounts.php">users</a>
         <a href="../views/messages.php">messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
         if ($admin->isLoggedIn()) {
            $profile = $admin->getProfile();
            if ($profile) {
               echo '<p>' . htmlspecialchars($profile['name']) . '</p>';
               echo '<a href="../views/update_profile.php" class="btn">update profile</a>';
               echo '<div class="flex-btn">
                        <a href="../views/register_admin.php" class="option-btn">register</a>
                        <a href="../views/admin_login.php" class="option-btn">login</a>
                     </div>';
               echo '<a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm(\'logout from the website?\');">logout</a>';
            } else {
               echo "<p>Profil non trouvé.</p>";
            }
         } else {
            echo "<p>Administrateur non connecté.</p>";
         }
         ?>
      </div>
   </section>
</header>
</body>
</html>
