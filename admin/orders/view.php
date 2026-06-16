<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
requireAdmin();

$order_id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("
    SELECT o.id, o.status, o.total_amount, o.created_at, o.items_json,
           u.name AS user_name, u.email AS user_email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Заказ не найден");
}

$order = $result->fetch_assoc();
$items = json_decode($order['items_json'], true);
if (!is_array($items)) {
    $items = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Просмотр заказа</title>
</head>
<body>
<h2>Заказ #<?= (int)$order["id"] ?></h2>

<p><b>Пользователь:</b> <?= h($order["user_name"]) ?></p>
<p><b>Email:</b> <?= h($order["user_email"]) ?></p>
<p><b>Статус:</b> <?= h($order["status"]) ?></p>
<p><b>Дата:</b> <?= h($order["created_at"]) ?></p>

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
            <td><?= h($item["name"]) ?></td>
            <td><?= $qty ?></td>
            <td><?= $price ?></td>
            <td><?= $sum ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p><b>Итого: <?= h($order["total_amount"]) ?> ₽</b></p>

<h3>Изменить статус</h3>
<form action="update_status.php" method="post">
    <input type="hidden" name="id" value="<?= (int)$order["id"] ?>">
    <select name="status">
        <option value="new" <?= $order["status"] === "new" ? "selected" : "" ?>>new</option>
        <option value="processing" <?= $order["status"] === "processing" ? "selected" : "" ?>>processing</option>
        <option value="done" <?= $order["status"] === "done" ? "selected" : "" ?>>done</option>
        <option value="cancelled" <?= $order["status"] === "cancelled" ? "selected" : "" ?>>cancelled</option>
    </select>
    <button type="submit">Сохранить</button>
</form>

<p><a href="list.php">← Назад</a></p>
</body>
</html>