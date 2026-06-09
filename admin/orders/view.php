<?php
session_start();
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_role"] ?? "") !== "admin") {
    die("Доступ запрещен");
}

$order_id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("
    SELECT orders.id, orders.status, orders.created_at,
           users.name AS user_name, users.email AS user_email
    FROM orders
    JOIN users ON orders.user_id = users.id
    WHERE orders.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Заказ не найден");
}

$order = $result->fetch_assoc();

$stmt = $conn->prepare("
    SELECT order_items.quantity, order_items.price, products.name AS product_name
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<h2>Заказ #<?= $order["id"] ?></h2>

<p><b>Пользователь:</b> <?= htmlspecialchars($order["user_name"]) ?></p>
<p><b>Email:</b> <?= htmlspecialchars($order["user_email"]) ?></p>
<p><b>Статус:</b> <?= htmlspecialchars($order["status"]) ?></p>
<p><b>Дата:</b> <?= htmlspecialchars($order["created_at"]) ?></p>

<h3>Товары</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>Название</th>
        <th>Количество</th>
        <th>Цена</th>
        <th>Сумма</th>
    </tr>

    <?php
    $total = 0;
    while ($item = $items->fetch_assoc()):
        $sum = $item["quantity"] * $item["price"];
        $total += $sum;
        ?>
        <tr>
            <td><?= htmlspecialchars($item["product_name"]) ?></td>
            <td><?= $item["quantity"] ?></td>
            <td><?= $item["price"] ?></td>
            <td><?= $sum ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<p><b>Итого: <?= $total ?> ₽</b></p>

<h3>Изменить статус</h3>

<form method="post" action="update_status.php">
    <input type="hidden" name="id" value="<?= $order["id"] ?>">

    <select name="status">
        <option value="new" <?= $order["status"] === "new" ? "selected" : "" ?>>new</option>
        <option value="processing" <?= $order["status"] === "processing" ? "selected" : "" ?>>processing</option>
        <option value="done" <?= $order["status"] === "done" ? "selected" : "" ?>>done</option>
    </select>

    <button>Сохранить</button>
</form>

<p><a href="list.php">← Назад</a></p>
</body>
</html>