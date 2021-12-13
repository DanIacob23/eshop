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

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title>Admin products</title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>

    <?php foreach ($data as $product): ?>
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

            <form method="POST">
                <div>
                    <input type="hidden" id="editId" name="editId" value="<?= $product['id'] ?>">
                    <a href="product.php?editId=<?= $product['id'] ?>"><?= translate('Edit', 'en') ?></a>
                </div>
            </form>

            <form method="POST" >
                <div>
                    <input type="submit" name="deleteProduct" value="<?= translate('Delete', 'en') ?>">
                    <input type="hidden" id="deleteId" name="deleteId" value="<?= $product['id'] ?>">
                </div>
            </form>
        </div>
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