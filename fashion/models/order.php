<?php
class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($customerId, $shippingAddressId, $billingAddressId, $subtotal, $tax, $shippingCost, $discount, $total, $paymentMethod, $items) {
        try {
            $this->pdo->beginTransaction();

            // Insert order
            $sql = "INSERT INTO orders (customer_id, shipping_address_id, billing_address_id, subtotal, tax, shipping_cost, discount, total_amount, payment_method, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$customerId, $shippingAddressId, $billingAddressId, $subtotal, $tax, $shippingCost, $discount, $total, $paymentMethod]);
            $orderId = $this->pdo->lastInsertId();

            // Insert order items
            foreach ($items as $item) {
                $sql = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, unit_price, total_price) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$orderId, $item['product_id'], $item['variant_id'], $item['quantity'], $item['unit_price'], $item['total_price']]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByCustomer($customerId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    public function getItems($orderId) {
        $stmt = $this->pdo->prepare("SELECT oi.*, p.name as product_name, pv.size, pv.color 
                                     FROM order_items oi 
                                     LEFT JOIN products p ON oi.product_id = p.id 
                                     LEFT JOIN product_variants pv ON oi.variant_id = pv.id 
                                     WHERE oi.order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function updateStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }

    // Admin: Get all orders
    public function getAll() {
        $stmt = $this->pdo->query("SELECT o.*, c.first_name, c.last_name, c.email 
                                   FROM orders o 
                                   JOIN customers c ON o.customer_id = c.id 
                                   ORDER BY o.created_at DESC");
        return $stmt->fetchAll();
    }
}
?>