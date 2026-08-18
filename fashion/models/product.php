<?php
class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getFeatured($limit = 6) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getVariants($productId) {
        $stmt = $this->pdo->prepare("SELECT * FROM product_variants WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getVariantById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM product_variants WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getCategory($categoryId) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$categoryId]);
        return $stmt->fetch();
    }

    // Admin methods
    public function create($data) {
        $sql = "INSERT INTO products (sku, name, description, category_id, base_price, stock_quantity, image_url) 
                VALUES (:sku, :name, :description, :category_id, :base_price, :stock_quantity, :image_url)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "UPDATE products SET sku=:sku, name=:name, description=:description, category_id=:category_id, 
                base_price=:base_price, stock_quantity=:stock_quantity, image_url=:image_url, is_active=:is_active 
                WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>