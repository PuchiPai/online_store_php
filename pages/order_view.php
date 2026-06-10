<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$userId = currentUserId();

if ($id <= 0) {
    die("Некорректный ID");
}

$stmt = $conn->prepare("
    SELECT id, status, total_amount, created_at, items_json
    FROM orders
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Заказ не найден");
}

$items = json_decode($order['items_json'], true);
if (!is_array($items)) {
    $items = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказ #<?= (int)$order['id'] ?></title>
</head>
<body>
<h2>Заказ #<?= (int)$order['id'] ?></h2>

<p>Статус: <b><?= h($order['status']) ?></b></p>
<p>Дата: <?= h($order['created_at']) ?></p>

<h3>Товары</h3>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Название</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Сумма</th>
    </tr>

    <?php foreach ($items as $item): ?>
        <?php
        $qty = (int)$item['quantity'];
        $price = (float)$item['price'];
        $sum = $qty * $price;
        ?>
        <tr>
            <td><?= h($item['name']) ?></td>
            <td><?= $qty ?></td>
            <td><?= $price ?></td>
            <td><?= $sum ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p><b>Итого: <?= h($order['total_amount']) ?> ₽</b></p>

<p><a href="orders.php">← Назад</a></p>
</body>
</html>