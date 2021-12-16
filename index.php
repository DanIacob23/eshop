<?php
require_once "./common.php";
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$data = getNotInCartProductsInfo($_SESSION['cart']);
if (isset($_POST['addCart'])) {
    $id = $_POST['id'];
    if (array_key_exists($id, $_SESSION['cart'])) {
        //if already exists
        $_SESSION['cart'][$id] = intval($_SESSION['cart'][$id]) + 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header('Location: index.php');
    die();
}
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
        $data = paginationIndexProducts($_SESSION['cart'], ($_GET['numberOfPage'] - 1) * 4, 4);
    }
}

if (isset($_POST['prev'])) {
    if (isset($_GET['numberOfPage'])) {
        if ($_GET['numberOfPage'] > 1) {
            header('Location: index.php?' . $sort . 'numberOfPage=' . strval(intval($_GET['numberOfPage']) - 1));
            die();
        }
    }
}

if (isset($_POST['next'])) {
    if (!isset($_GET['numberOfPage'])) {

        header('Location: index.php?' . $sort . 'numberOfPage=2');
        die();
    } else {
        if ($_GET['numberOfPage'] < $numberOfPages) {
            header('Location: index.php?' . $sort . 'numberOfPage=' . strval(intval($_GET['numberOfPage']) + 1));
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
$sortOption = [
    'numeasc' => [
        'item' => 'title',
        'option' => 'ASC',
    ],
    'numedesc' => [
        'item' => 'title',
        'option' => 'DESC',
    ],
    'pretasc' => [
        'item' => 'price',
        'option' => 'ASC',
    ],
    'pretdesc' => [
        'item' => 'price',
        'option' => 'DESC',
    ],
];

if (isset($_POST['sort'])) {
    header('Location: index.php?numberOfPage=1&sortOption=' . $_POST['sortOption']);
    die();
}
if (isset($_GET['sortOption'])) {
    $data = sortProductsByItemIndex($_SESSION['cart'], $sortOption[$_GET['sortOption']]['item'], $sortOption[$_GET['sortOption']]['option'], ($_GET['numberOfPage'] - 1) * 4, 4);
}
//----------------------

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Index', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <div class="test">
        <div class="pagination">

            <form method="POST">
                <input type="submit" value="<Prev" name="prev" id="prev" <?= $prevDisable ?>>
                <input type="submit" value="Next>" name="next" id="next" <?= $nextDisable ?>>
            </form>
        </div>

        <div class="sort">
            <form method="POST">
                <label for="sortOption">Choose an option for sort products:</label>
                <select name="sortOption" id="sortOption">
                    <option value="numeasc"><?= translate('Nume ASC', 'en') ?></option>
                    <option value="numedesc"><?= translate('NUME DESC', 'en') ?></option>
                    <option value="pretasc"><?= translate('PRET ASC', 'en') ?></option>
                    <option value="pretdesc"><?= translate('PRET DESC', 'en') ?></option>
                </select>
                <br><br>
                <input type="submit" value="<?= translate('Submit', 'en') ?>" name="sort">
            </form>
        </div>
    </div>
    <?php foreach ($data as $product): ?>

        <form method="POST">
            <div class="product">
                <div>
                    <img class="img-product" src="./images/<?= $product['id'] ?><?= $product['fileType'] ?>"
                         alt="<?= translate('Product Image', 'en') ?>">
                </div>
                <div class="infos">
                    <h3><?= translate('Title', 'en') ?> <?= $product['title'] ?></h3>
                    <p><?= translate('Description', 'en') ?> <?= $product['description'] ?></p>
                    <p id="price"><?= translate('Price', 'en') ?> <?= $product['price'] ?> $</p>
                </div>
                <div>
                    <input type="submit" name="addCart" value="<?= translate('Add', 'en') ?>">
                    <input type="hidden" id="id" name="id" value="<?= $product['id'] ?>">
                </div>
            </div>
        </form>

    <?php endforeach; ?>
</main>
<a href="cart.php" id="cart"><?= translate('Go to cart', 'en') ?></a>
</body>
</html>