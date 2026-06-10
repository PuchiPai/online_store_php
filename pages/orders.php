<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$userId = currentUserId();

$stmt = $conn->prepare("
    SELECT id, status, total_amount, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
</head>
<body>
<h2>Мои заказы</h2>

<?php if ($result->num_rows === 0): ?>
    <p>У вас пока нет заказов</p>
<?php endif; ?>

<?php while ($order = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; margin:10px 0; padding:10px;">
        <p><b>Заказ #<?= (int)$order['id'] ?></b></p>
        <p>Статус: <b><?= h($order['status']) ?></b></p>
        <p>Сумма: <b><?= h($order['total_amount']) ?> ₽</b></p>
        <p>Дата: <?= h($order['created_at']) ?></p>
        <a href="order_view.php?id=<?= (int)$order['id'] ?>">Подробнее</a>
    </div>
<?php endwhile; ?>
</body>
</html>