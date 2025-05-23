<?php
class users {
   private $conn;
   private $id;
   private $email;
      private $name;
    public $data;

   private $password;

   public function __construct($conn, $id = null) {
      $this->conn = $conn;
      if ($id !== null) {
         $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
         $stmt->execute([$id]);
         $this->data = $stmt->fetch(PDO::FETCH_ASSOC);
      }
   }

   private function loadData() {
      $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->execute([$this->id]);
      $this->data = $stmt->fetch(PDO::FETCH_ASSOC);
   }
  public function updateProfile($name, $email) {
      $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
      return $stmt->execute([$name, $email, $this->data['id']]);
   }

   public function updatePassword($old, $new, $confirm) {
      $messages = [];

      if ($new !== $confirm) {
         $messages[] = "Le mot de passe de confirmation ne correspond pas.";
         return $messages;
      }

      $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
      $stmt->execute([$this->data['id']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!password_verify($old, $user['password'])) {
         $messages[] = "Ancien mot de passe incorrect.";
         return $messages;
      }

      $hashed = password_hash($new, PASSWORD_DEFAULT);
      $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
      if ($stmt->execute([$hashed, $this->data['id']])) {
         $messages[] = "Mot de passe mis à jour.";
      } else {
         $messages[] = "Erreur lors de la mise à jour du mot de passe.";
      }

      return $messages;
   }

   public function getName() { return $this->name; }

   // Getters
   public function getId() {
      return $this->id;
   }

   public function getEmail() {
      return $this->email;
   }

   public function getPassword() {
      return $this->password;
   }

   // Setters
    public function setName($name) {
      $this->name = filter_var($name, FILTER_SANITIZE_STRING);
   }
   public function setEmail($email) {
      $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
   }

   public function setPassword($password) {
      $this->password = sha1(filter_var($password, FILTER_SANITIZE_STRING));
   }

   // Login method
   public function login() {
      $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
      $stmt->execute([$this->email, $this->password]);

      if ($stmt->rowCount() > 0) {
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
         $this->id = $user['id'];
         $_SESSION['user_id'] = $this->id;
         header('location:home.php');
         exit;
      } else {
         return 'Identifiant ou mot de passe incorrect !';
      }
   }
    public function register($confirmPassword) {
      $confirmHashed = sha1(filter_var($confirmPassword, FILTER_SANITIZE_STRING));
// Insertion en base
$insert = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$insert->execute([$this->name, $this->email, $this->password]);

      // Vérifier si email existe déjà
      $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->execute([$this->email]);
      if ($stmt->rowCount() > 0) {
         return 'Cet email est déjà utilisé !';
      }

      // Vérifier correspondance des mots de passe
      if ($this->password !== $confirmHashed) {
         return 'Les mots de passe ne correspondent pas.';
      }

      // Insertion en base
      $insert = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
      $insert->execute([$this->name, $this->email, $this->password]);

      return 'Inscription réussie ! Connectez-vous maintenant.';
   }

}
?>
