<?php
require_once "./common.php";
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

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
$item = isset($_GET['sortOption']) && isset($sortOption[$_GET['sortOption']]) ? $sortOption[$_GET['sortOption']]['item'] : 'title';
$option = isset($_GET['sortOption']) && isset($sortOption[$_GET['sortOption']]) ? $sortOption[$_GET['sortOption']]['option'] : 'ASC';
$results = sortProductsByItemIndex(
    $_SESSION['cart'],
    isset($_GET['numberOfPage']) ? $_GET['numberOfPage'] : 1,
    4,
    $item,
    $option
);
$data = $results['results'];
$countProducts = intval($results['total']);
$numberOfPages = intval($results['numberOfPages']);
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

//for beginning true for prev false for next
$nextDisable = isset($_GET['numberOfPage']) && intval($_GET['numberOfPage']) >= intval($numberOfPages) ? 'disabled' : '';
$prevDisable = !isset($_GET['numberOfPage']) || intval($_GET['numberOfPage']) <= 1 ? 'disabled' : '';

if (isset($_POST['sort'])) {
    header('Location: index.php?numberOfPage=1&sortOption=' . $_POST['sortOption']);
    die();
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

            <form method="GET" >
                <?php if (isset($_GET['sortOption'])): ?>
                    <input type="hidden" name="sortOption" value="<?= $_GET['sortOption'] ?>">
                <?php endif; ?>
                <input type="hidden" name="numberOfPage" value="<?= isset($_GET['numberOfPage']) && intval($_GET['numberOfPage']) > 1 ? --$_GET['numberOfPage'] : 1 ?>">
                <button type="submit" id="prev" <?= $prevDisable ?>>&lt;Prev</button>
            </form>

            <form method="GET">
                <?php if (isset($_GET['sortOption'])): ?>
                    <input type="hidden" name="sortOption" value="<?= $_GET['sortOption'] ?>">
                <?php endif; ?>
                <input type="hidden" name="numberOfPage" value="<?= isset($_GET['numberOfPage']) && intval($_GET['numberOfPage']) < $numberOfPages ? ++$_GET['numberOfPage'] : $numberOfPages ?>">
                <button type="submit" id="next" <?= $nextDisable ?>>Next&gt;</button>
            </form>
        </div>

        <div class="sort">
            <form method="GET">
                <?php if (isset($_GET['numberOfPage'])): ?>
                    <input type="hidden" name="numberOfPage" value="<?= $_GET['numberOfPage'] ?>"
                <?php endif; ?>
                <label for="sortOption">Choose an option for sort products:</label>
                <select name="sortOption" id="sortOption">
                    <option value="numeasc"><?= translate('Nume ASC', 'en') ?></option>
                    <option value="numedesc"><?= translate('NUME DESC', 'en') ?></option>
                    <option value="pretasc"><?= translate('PRET ASC', 'en') ?></option>
                    <option value="pretdesc"><?= translate('PRET DESC', 'en') ?></option>
                </select>
                <br><br>
                <input type="submit" value="<?= translate('Submit', 'en') ?>">
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