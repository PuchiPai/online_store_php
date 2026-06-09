<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$userId = $_SESSION['user_id'];

if ($id <= 0) {
    die("Некорректный ID");
}

$orderStmt = $conn->prepare("
    SELECT * FROM orders
    WHERE id = ? AND user_id = ?
");
$orderStmt->bind_param("ii", $id, $userId);
$orderStmt->execute();

$order = $orderStmt->get_result()->fetch_assoc();

if (!$order) {
    die("Заказ не найден");
}

$itemsStmt = $conn->prepare("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
");
$itemsStmt->bind_param("i", $id);
$itemsStmt->execute();

$items = $itemsStmt->get_result();
?>

<h2>Заказ #<?= $order['id'] ?></h2>

<p>Статус: <b><?= h($order['status']) ?></b></p>

<h3>Товары:</h3>

<?php
$total = 0;
?>

<?php while ($i = $items->fetch_assoc()): ?>
    <?php
    $sum = $i['quantity'] * $i['price'];
    $total += $sum;
    ?>
    <p>
        <?= h($i['name']) ?> —
        <?= $i['quantity'] ?> шт × <?= $i['price'] ?> ₽ =
        <b><?= $sum ?> ₽</b>
    </p>
<?php endwhile; ?>

<hr>

<p><b>ИТОГО: <?= $total ?> ₽</b></p>

<a href="orders.php">← Назад</a>