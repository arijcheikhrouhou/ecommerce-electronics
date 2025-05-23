<?php
class products {
    private $conn;
    private $table = 'products';

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }
    public function searchProducts(string $keyword): array {
        $query = "SELECT * FROM products WHERE name LIKE :search_query";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':search_query' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductsByCategory(string $category): PDOStatement {
        $query = "SELECT * FROM products WHERE name LIKE :category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':category' => '%' . $category . '%']);
        return $stmt;
    }
 public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // ✅ garde bien FETCH_ASSOC
    }
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByName($name) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($name, $details, $price, $img1, $img2, $img3) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, details, price, image_01, image_02, image_03) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $details, $price, $img1, $img2, $img3]);
    }

    public function deleteProductWithImages($id) {
        $stmt = $this->conn->prepare("SELECT image_01, image_02, image_03 FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $images = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($images) {
            foreach ($images as $img) {
                $path = '../uploaded_img/' . $img;
                if (file_exists($path)) unlink($path);
            }
        }

        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getLatestProducts($limit = 6) {
        $stmt = $this->conn->prepare("SELECT * FROM products LIMIT :limit");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public function getAllProducts(): array {
        $stmt = $this->conn->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public function getProductById(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product ?: null;
    }
 

   public function updateProduct($pid, $name, $price, $details) {
      $stmt = $this->conn->prepare("UPDATE products SET name = ?, price = ?, details = ? WHERE id = ?");
      $stmt->execute([$name, $price, $details, $pid]);
   }

   public function updateProductImage($pid, $imageField, $image, $oldImage) {
      $stmt = $this->conn->prepare("UPDATE products SET $imageField = ? WHERE id = ?");
      $stmt->execute([$image, $pid]);
      if (file_exists('../uploaded_img/' . $oldImage)) {
         unlink('../uploaded_img/' . $oldImage);
      }
   }

   public function uploadImage($file, $targetFolder) {
      $imageName = filter_var($file['name'], FILTER_SANITIZE_STRING);
      $tmpName = $file['tmp_name'];
      $size = $file['size'];
      $targetPath = $targetFolder . $imageName;

      if ($size > 2000000) {
         return false;
      } else {
         move_uploaded_file($tmpName, $targetPath);
         return $imageName;
      }
   }}