<?php
session_start();
require_once "./common.php";
$data=getAllProductsInfo();

function removeToCart($id){
    unset( $_SESSION['cart'][$id]);
}
if (isset($_POST["removeToCart"])) {
    removeToCart($_GET["id"]);
}
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title>Cart</title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
    <body>
        <main>
            <?php foreach ($data as $product): ?>
                <?php if(array_key_exists($product['id'], $_SESSION['cart'])): ?>
                    <form method="POST" action="cart.php?id=<?=$product['id']?>" >
                        <div class="product">
                            <img class="img-product" src="./images/<?= $product['id']?><?= $product['fileType']?>" alt="prod-img">
                            <div class="infos">
                                <h3>Title<?=$product['title']?></h3>
                                <p>Description<?=$product['description']?></p>
                                <p>Price <span style="color:blue;font-weight:bold"><?=$product['price']?> $</span>
                            </div>
                            <div>
                                <input type="submit" name="removeToCart" value="Remove">
                            </div>
                        </div>
                    </form>
                <?php endif;?>
            <?php endforeach;?>
            <form method="POST" action="" >
                <div class="checkout-details">
                    <input type="text" id="name" name="name" placeholder="Name">
                    <input type="text" id="contactDetails" name="contactDetails" size="50" placeholder="Contact details">
                    <input type="text" id="comments" name="comments" size="50" placeholder="Comments">
                </div>
                <div class="checkout">
                    <a href="index.php">Go to index</a>
                    <input type="submit" value="Checkout">
                </div>
            </form>

        </main>
    </body>
</html>
