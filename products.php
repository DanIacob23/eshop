<?php
require_once "./common.php";
checkAdminLogin();
$data = getAllProductsInfo();
if (isset($_POST['deleteId'])) {
    if (unlink('images/' . $_POST['deleteId'] . '.jpg')) {
        deleteProduct($_POST['deleteId']);
        deleteProductFromOrders($_POST['deleteId']);
        unset($_SESSION['cart'][$_POST['deleteId']]);//remove from cart
        header('Location: products.php');
        die();
    } else {
        unlink('images/' . $_POST['deleteId'] . '.png');
        deleteProduct($_POST['deleteId']);
        deleteProductFromOrders($_POST['deleteId']);
        unset($_SESSION['cart'][$_POST['deleteId']]);//remove from cart
        header('Location: products.php');
        die();
    }
}
if (isset($_POST['adminLogout'])) {
    unset($_SESSION['adminLogin']);
    header('Location: index.php');
    die();
}

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
    //sa pun in $data!!!!!! noul array
    header('Location: index.php?sortOption=' . $_POST['sortOption']);
    die();
}
if (isset($_GET['sortOption'])){
    print_r(getAllProdSort($sortOption[$_GET['sortOption']]['item'], $sortOption[$_GET['sortOption']]['option'], ($_SESSION['numberOfPage'] - 1) * 4, 4));
    $data = getAllProdSort($sortOption[$_GET['sortOption']]['item'], $sortOption[$_GET['sortOption']]['option'], ($_SESSION['numberOfPage'] - 1) * 4, 4);
}

//----------------------

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title>Admin products</title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <div class="test">
        <div class="pagination">
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
        </div>

        <div class="sort">
            <form method="POST">
                <label for="sortOption">Choose an option for sort products:</label>
                <select name="sortOption" id="sortOption">
                    <option value="numeasc">Nume ASC</option>
                    <option value="numedesc">NUME DESC</option>
                    <option value="pretasc">PRET ASC</option>
                    <option value="pretdesc">PRET DESC</option>
                </select>
                <br><br>
                <input type="submit" value="Submit" name="sort">
            </form>
        </div>
    </div>

    <?php foreach ($data as $product): ?>
        <?php if (in_array($product['id'], $productByPage)): ?>
            <div class="product">
                <div>
                    <img class="img-product" src="./images/<?= $product['id'] ?><?= $product['fileType'] ?>"
                         alt="<?= translate('Product Image', 'en') ?>">
                </div>

                <table>
                    <div class="infos">
                        <h3><?= translate('Title', 'en') ?>: <?= $product['title'] ?></h3>
                        <p><?= translate('Description', 'en') ?>: <?= $product['description'] ?></p>
                        <p id="price"><?= translate('Price', 'en') ?>: <?= $product['price'] ?> $
                    </div>
                </table>


                <div>
                    <a href="product.php?editId=<?= $product['id'] ?>"><?= translate('Edit', 'en') ?></a>
                </div>


                <form method="POST">
                    <div>
                        <input type="submit" name="deleteProduct" value="<?= translate('Delete', 'en') ?>">
                        <input type="hidden" id="deleteId" name="deleteId" value="<?= $product['id'] ?>">
                    </div>
                </form>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="optionsAdmin">
        <a href="product.php"><?= translate('Add', 'en') ?></a>
        <form method="POST" action="">
            <input type="submit" name="adminLogout" value="<?= translate('Logout', 'en') ?>">
        </form>
    </div>
</main>
</body>
</html>