<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
requireAdmin();

$result = $conn->query("
    SELECT
        o.id,
        o.status,
        o.created_at,
        o.total_amount,
        u.name AS user_name,
        u.email AS user_email
    FROM orders o
    JOIN users u ON u.id = o.user_id
    ORDER BY o.id DESC
    LIMIT 20
");

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

jsonResponse([
    'ok' => true,
    'orders' => $orders
]);