<?php
require_once "./common.php";
checkAdminLogin();
$data = joinOrders();

//------------------------
$countProducts = count($data);
$numberOfPages = ceil($countProducts / 4);//4 product per page
$productByPage = [];
if (!isset($_SESSION['numberOfPage'])) {
    $_SESSION['numberOfPage'] = 1;
}

for ($id = 1; $id <= 4; $id++) {
    if ($id <= $countProducts) {
        array_push($productByPage, $id);
    }
}
//for beginning true for prev false for next
$isSetNext = 'false';
$isSetPrev = 'true';

if (isset($_POST['prev'])) {
    if ($_SESSION['numberOfPage'] > 1) {
        $_SESSION['numberOfPage'] = $_SESSION['numberOfPage'] - 1;
    }
    $_POST['pageNumber'] = $_SESSION['numberOfPage'];
}

if (isset($_POST['next'])) {
    if ($_SESSION['numberOfPage'] < $numberOfPages) {
        $_SESSION['numberOfPage'] = $_SESSION['numberOfPage'] + 1;
    }
    $_POST['pageNumber'] = $_SESSION['numberOfPage'];
}

if (isset($_POST['pageNumber'])) {
    $_SESSION['numberOfPage'] = $_POST['pageNumber'];
    $productByPage = [];//reset for page
    for ($id = (($_POST['pageNumber'] - 1) * 4) + 1; $id <= $_POST['pageNumber'] * 4; $id++) {
        if ($id <= $countProducts) {
            array_push($productByPage, $id);
        }
    }
    $isSetPrev = 'true';
    $isSetPrev = 'false';

    //true for disable
    if ($_POST['pageNumber'] == 1) {
        $isSetPrev = 'true';
        $isSetNext = 'false';
    }
    if ($_POST['pageNumber'] == $numberOfPages) {
        $isSetNext = 'true';
        $isSetPrev = 'false';
    }
}
//page refresh ok
//----------------------
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Orders', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <?php if ($isSetPrev == 'false') : ?>
        <form method="POST">
            <input type="submit" value="<Prev" name="prev" id="prev">
        </form>
    <?php endif; ?>
    <?php if ($isSetPrev == 'true') : ?>
        <form method="POST">
            <input type="submit" value="<Prev" name="prev" id="prev" disabled="disabled">
        </form>
    <?php endif; ?>
    <?php for ($number = 1; $number <= $numberOfPages; $number++) : ?>
        <form method="POST">
            <input type="submit" value="<?= $number ?>" name="pageNumber" id="pageNumber">
        </form>
    <?php endfor; ?>

    <?php if ($isSetNext == 'false') : ?>
        <form method="POST">
            <input type="submit" value="Next>" name="next" id="next">
        </form>
    <?php endif; ?>
    <?php if ($isSetNext == 'true') : ?>
        <form method="POST">
            <input type="submit" value="Next>" name="next" id="next" disabled="disabled">
        </form>
    <?php endif; ?>

    <?php foreach ($data as $product): ?>
        <?php if (in_array($product['id'], $productByPage)): ?>
            <div class="orders">
                <p id="datetime"><?= translate('Order date', 'en') ?>: <?= $product['datetime'] ?></p>
                <p><?= translate('Name', 'en') ?>: <?= $product['userName'] ?></p>
                <p><?= translate('Contact details', 'en') ?>: <?= $product['contactDetails'] ?></p>
                <p><?= translate('Comments', 'en') ?>: <?= $product['comments'] ?></p>
                <p><?= translate('Total price', 'en') ?>: <?= $product['total'] ?> $</p>
                <a href="order.php?orderLastInsertId=<?= $product['id'] ?>">View order</a>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</main>
</body>
</html>
