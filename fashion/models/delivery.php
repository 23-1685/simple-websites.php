<?php
class Delivery {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($orderId, $addressId, $carrier = null, $tracking = null) {
        $sql = "INSERT INTO deliveries (order_id, delivery_address_id, carrier, tracking_number, status) 
                VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$orderId, $addressId, $carrier, $tracking]);
    }

    public function getByOrder($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM deliveries WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }

    public function updateStatus($deliveryId, $status, $shippedAt = null, $deliveredAt = null) {
        $sql = "UPDATE deliveries SET status = ?";
        $params = [$status];
        if ($shippedAt) {
            $sql .= ", shipped_at = ?";
            $params[] = $shippedAt;
        }
        if ($deliveredAt) {
            $sql .= ", delivered_at = ?";
            $params[] = $deliveredAt;
        }
        $sql .= " WHERE id = ?";
        $params[] = $deliveryId;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
?>