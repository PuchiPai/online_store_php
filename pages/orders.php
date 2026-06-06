<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();

$orders = $stmt->get_result();
?>

    <h2>Мои заказы</h2>

<?php while ($order = $orders->fetch_assoc()): ?>

    <?php
    $orderId = $order['id'];

    $totalStmt = $conn->prepare("
    SELECT SUM(quantity * price) AS total
    FROM order_items
    WHERE order_id = ?
");
    $totalStmt->bind_param("i", $orderId);
    $totalStmt->execute();
    $total = $totalStmt->get_result()->fetch_assoc()['total'];
    ?>

    <div style="border:1px solid #ccc; margin:10px; padding:10px;">
        <p><b>Заказ #<?= $orderId ?></b></p>
        <p>Статус: <b><?= h($order['status']) ?></b></p>
        <p>Сумма: <b><?= $total ?? 0 ?> ₽</b></p>
        <p>Дата: <?= $order['created_at'] ?></p>

        <a href="order_view.php?id=<?= $orderId ?>">Подробнее</a>
    </div>

    <a href="profile.php">← Назад</a>

<?php endwhile; ?>