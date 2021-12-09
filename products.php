<?php
require_once "./common.php";
$data = getAllProductsInfo();
if (isset($_POST["deleteId"])) {
    if (unlink('images/'.$_POST["deleteId"].'.jpg')) {
        deleteProduct($_POST["deleteId"]);
        unset($_SESSION['cart'][$_POST["deleteId"]]);//remove from cart
        header('Location: products.php');
        die();
    } else {
        unlink('images/'.$_POST["deleteId"].'.png');
        deleteProduct($_POST["deleteId"]);
        unset($_SESSION['cart'][$_POST["deleteId"]]);//remove from cart
        header('Location: products.php');
        die();
    }
}
if (isset($_POST["adminLogout"])) {
    header('Location: index.php');
    die();
}
if (isset($_POST['Add']) && isset($_SESSION['editId']) ) {
    unset($_SESSION['editId']);
    header('Location: product.php');
    die();
} else if (isset($_POST['Add'])) {
    header('Location: product.php');
    die();
}

if (isset($_POST['editProduct'])) {
    $_SESSION['editId'] = $_POST["editId"];
    header('Location: product.php');
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
                            <img class="img-product" src="./images/<?= $product['id'] ?><?= $product['fileType'] ?>" alt="<?= translate("Product Image","en") ?>">
                        </div>

                        <table>
                            <div class="infos">
                                <h3><?= translate("Title","en") ?>: <?= $product['title'] ?></h3>
                                <p><?= translate("Description","en") ?>: <?= $product['description'] ?></p>
                                <p><?= translate("Price","en") ?>: <span style="color:blue;font-weight:bold"><?= $product['price'] ?> $</span>
                            </div>
                        </table>

                        <form method="POST"  >
                            <div>
                                <input type="submit" name="editProduct" value="<?= translate("Edit","en") ?>">
                                <input type="hidden" id="editId" name="editId" value="<?= $product['id'] ?>">
                            </div>
                        </form>

                        <form method="POST" action="" >
                            <div>
                                <input type="submit" name="deleteProduct" value="<?= translate("Delete","en") ?>">
                                <input type="hidden" id="deleteId" name="deleteId" value="<?= $product['id'] ?>">
                            </div>
                        </form>
                    </div>
        <?php endforeach;?>

        <div class="optionsAdmin">
            <form method="POST"  >
                <input type="submit" name="<?= translate("Add","en") ?>" value="<?= translate("Add","en") ?>">
            </form>
            <form method="POST" action="" >
                <input type="submit" name="adminLogout" value="<?= translate("Logout","en") ?>">
            </form>
        </div>
    </main>
</body>
</html>