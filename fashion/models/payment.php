<?php
class Payment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($orderId, $amount, $method, $transactionId = null, $status = 'pending') {
        $sql = "INSERT INTO payments (order_id, amount, payment_method, transaction_id, status) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$orderId, $amount, $method, $transactionId, $status]);
    }

    public function getByOrder($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }

    public function updateStatus($paymentId, $status, $gatewayResponse = null) {
        $sql = "UPDATE payments SET status = ?";
        $params = [$status];
        if ($gatewayResponse) {
            $sql .= ", gateway_response = ?";
            $params[] = $gatewayResponse;
        }
        $sql .= " WHERE id = ?";
        $params[] = $paymentId;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
?>