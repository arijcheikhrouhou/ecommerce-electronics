<?php

include '../models/Database.php';
session_start();

class Dashboard {
    private $conn;
    private $admin_id;

    public function __construct($conn, $admin_id) {
        $this->conn = $conn;
        $this->admin_id = $admin_id;
    }

    public function getProfile() {
        $select_profile = $this->conn->prepare("SELECT * FROM `admins` WHERE id = ?");
        $select_profile->execute([$this->admin_id]);
        return $select_profile->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotal($table, $condition = null, $value = null) {
        $query = "SELECT * FROM $table";
        if ($condition && $value) {
            $query .= " WHERE $condition = ?";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($value ? [$value] : []);
        return $stmt->rowCount();
    }

    public function getTotalAmount($table, $condition, $value) {
        $total = 0;
        $stmt = $this->conn->prepare("SELECT * FROM $table WHERE $condition = ?");
        $stmt->execute([$value]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total += $row['total_price'];
        }
        return $total;
    }
}

$database = new Database();
$conn = $database->getConnection();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

$dashboard = new Dashboard($conn, $admin_id);
$profile = $dashboard->getProfile();
$total_pendings = $dashboard->getTotalAmount('orders', 'payment_status', 'pending');
$total_completes = $dashboard->getTotalAmount('orders', 'payment_status', 'completed');
$number_of_orders = $dashboard->getTotal('orders');
$number_of_products = $dashboard->getTotal('products');
$number_of_users = $dashboard->getTotal('users');
$number_of_admins = $dashboard->getTotal('admins');
$number_of_messages = $dashboard->getTotal('messages');

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">
   <h1 class="heading">Dashboard</h1>

   <div class="box-container">
      <div class="box">
         <h3>Welcome!</h3>
         <p><?= $profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Update Profile</a>
      </div>

      <div class="box">
         <h3><span>DNT</span><?= $total_pendings; ?><span></span></h3>
         <p>Total Pending Orders</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <h3><span>DNT</span><?= $total_completes; ?><span></span></h3>
         <p>Completed Orders</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <h3><?= $number_of_orders; ?></h3>
         <p>Orders Placed</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <h3><?= $number_of_products; ?></h3>
         <p>Products Added</p>
         <a href="products.php" class="btn">See Products</a>
      </div>

      <div class="box">
         <h3><?= $number_of_users; ?></h3>
         <p>Normal Users</p>
         <a href="users_accounts.php" class="btn">See Users</a>
      </div>

      <div class="box">
         <h3><?= $number_of_admins; ?></h3>
         <p>Admin Users</p>
         <a href="admin_accounts.php" class="btn">See Admins</a>
      </div>

      <div class="box">
         <h3><?= $number_of_messages; ?></h3>
         <p>New Messages</p>
         <a href="messages.php" class="btn">See Messages</a>
      </div>
   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
