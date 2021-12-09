<?php
require_once "./common.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$data = getInCartProductsInfo($_SESSION['cart']);

if (isset( $_POST["addCart"] )) {
    $id = $_POST["id"];
    if (array_key_exists($id, $_SESSION['cart'])) {
        //if already exists
        $_SESSION['cart'][$id] = intval($_SESSION['cart'][$id]) + 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header('Location: index.php');
    die();
}
?>
<!DOCTYPE html>
<html lang="eng">
    <head>
        <title><?= translate("Index","en") ?></title>
        <link href="public/css/utils.css" rel="stylesheet">
    </head>
    <body>
        <main>
            <?php foreach ($data as $product): ?>
                    <form method="POST" action="#" >
                        <div class="product">
                            <div>
                                <img class="img-product" src="./images/<?= $product['id'] ?><?= $product['fileType'] ?>" alt="<?= translate("Product Image","en") ?>">
                            </div>
                            <div class="infos">
                                <h3><?= translate("Title","en") ?> <?= $product['title'] ?></h3>
                                <p><?= translate("Description","en") ?> <?= $product['description'] ?></p>
                                <p id="price"><?= translate("Price","en") ?> <?= $product['price'] ?> $</p>
                            </div>
                            <div>
                                <input type="submit" name="addCart" value="<?= translate("Add","en") ?>">
                                <input type="hidden" id="id" name="id" value="<?= $product['id'] ?>">
                            </div>
                        </div>
                    </form>
            <?php endforeach;?>
        </main>
        <a href="cart.php" id="cart"><?= translate("Go to cart","en") ?></a>
    </body>
</html>