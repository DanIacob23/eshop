<?php
require_once "./common.php";
if (isset($_GET['orderLastInsertId'])) {
    $leftJoin = leftJoinProducts($_GET['orderLastInsertId']);
} else die();
if (empty($leftJoin)) {
    die();
}

?>

<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Order', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <div class="order">
        <ul>
            <li><?= translate('Name', 'en') ?>: <?= $leftJoin[0]['userName'] ?></li>
            <li><?= translate('Contact details', 'en') ?>: <?= $leftJoin[0]['contactDetails'] ?></li>
            <li><?= translate('Comments', 'en') ?>: <?= $leftJoin[0]['comments'] ?></li>
        </ul>
        <h3><?= translate('Cart', 'en') ?>: </h3>
        <div class="cart">
            <?php foreach ($leftJoin as $item): ?>
                <div class="product">
                    <div>
                        <img class="img-product"
                             src="./images/<?= $item['id'] ?><?= $item['fileType'] ?>"
                             alt="<?= translate('Product Image', 'en') ?>">
                    </div>
                    <div class="infos">
                        <h3><?= translate('Title', 'en') ?> <?= $item['title'] ?></h3>
                        <p><?= translate('Description', 'en') ?> <?= $item['description'] ?></p>
                        <p id="price"><?= translate('Price', 'en') ?> <?= $item['price'] ?> $</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
</body>
