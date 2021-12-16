<?php
require_once "./common.php";
checkAdminLogin();
$data = joinOrders();

//------------------------
$countProducts = count($data);
$numberOfPages = ceil($countProducts / 4);//4 product per page

//for beginning true for prev false for next
$isSetNext = 'false';
$isSetPrev = 'true';
$sort = '';
if (isset($_GET['sortOption'])) {
    $sort = 'sortOption=' . $_GET['sortOption'] . '&';
}

if (isset($_GET['numberOfPage'])) {
    if (intval($_GET['numberOfPage']) > 1) {
        $isSetPrev = 'false';
    }
    if ($_GET['numberOfPage'] == $numberOfPages) {
        $isSetNext = 'true';
    }
    if (!isset($_GET['sortOption'])) {
        $data = joinOrdersLimit(($_GET['numberOfPage'] - 1) * 4, 4);
    }
}

if (isset($_POST['prev'])) {
    if (isset($_GET['numberOfPage'])) {
        if ($_GET['numberOfPage'] > 1) {
            header('Location: orders.php?numberOfPage=' . strval(intval($_GET['numberOfPage']) - 1));
            die();
        }
    }
}

if (isset($_POST['next'])) {
    if (!isset($_GET['numberOfPage'])) {

        header('Location: orders.php?' . $sort . 'numberOfPage=2');
        die();
    } else {
        if ($_GET['numberOfPage'] < $numberOfPages) {
            header('Location: orders.php?numberOfPage=' . strval(intval($_GET['numberOfPage']) + 1));
            die();
        }
    }
}
$prevDisable = '';
$nextDisable = '';
if ($isSetPrev == 'true') {
    $prevDisable = 'disabled';
}
if ($isSetNext == 'true') {
    $nextDisable = 'disabled';
}
//page refresh ok
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Orders', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <div class="pagination">

        <form method="POST">
            <input type="submit" value="<Prev" name="prev" id="prev" <?= $prevDisable ?>>
            <input type="submit" value="Next>" name="next" id="next" <?= $nextDisable ?>>
        </form>

    </div>

    <?php foreach ($data as $product): ?>
        <div class="orders">
            <p id="datetime"><?= translate('Order date', 'en') ?>: <?= $product['datetime'] ?></p>
            <p><?= translate('Name', 'en') ?>: <?= $product['userName'] ?></p>
            <p><?= translate('Contact details', 'en') ?>: <?= $product['contactDetails'] ?></p>
            <p><?= translate('Comments', 'en') ?>: <?= $product['comments'] ?></p>
            <p><?= translate('Total price', 'en') ?>: <?= $product['total'] ?> $</p>
            <a href="order.php?orderLastInsertId=<?= $product['id'] ?>">View order</a>
        </div>
    <?php endforeach; ?>
</main>
</body>
</html>
