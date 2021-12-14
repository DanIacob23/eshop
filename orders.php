<?php
require_once "./common.php";
checkAdminLogin();
$data = joinOrders();
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Orders', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <?php foreach ($data as $product): ?>
        <div class="orders">
            <p id="datetime"><?= translate('Order date', 'en') ?>: <?= $product['datetime'] ?></p>
            <p><?= translate('Name', 'en') ?>: <?= $product['userName'] ?></p>
            <p><?= translate('Contact details', 'en') ?>: <?= $product['contactDetails'] ?></p>
            <p><?= translate('Comments', 'en') ?>: <?= $product['comments'] ?></p>
            <p><?= translate('Total price', 'en') ?>: <?= $product['total'] ?> $</p>
            <a href="order.php?lastInsertId=<?= $product['id'] ?>">View order</a>
        </div>
    <?php endforeach; ?>
</main>
</body>
</html>
