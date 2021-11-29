<?php
session_start();
require_once "./common.php";
$data=getAllProductsInfo();

if (!isset($_GET['id'])) {
    $_SESSION['cart']=array();
}

function addToCart($id) {
    if (array_key_exists($id, $_SESSION['cart'])) {//if already exists
        $_SESSION['cart'][$id]= intval($_SESSION['cart'][$id])+1;
        print_r($_SESSION['cart']);
    }else{
        $_SESSION['cart'][$id]=1;
        print_r($_SESSION['cart']);
    }
}
if (isset($_POST["addCart"])) {
    addToCart($_GET["id"]);
}
?>
<!DOCTYPE html>
<html lang="eng">
    <head>
        <title>Index</title>
        <link href="public/css/utils.css" rel="stylesheet">
    </head>
    <body>
        <main>
            <?php foreach ($data as $product): ?>
                <?php if(!array_key_exists($product['id'], $_SESSION['cart'])): ?>
                    <form method="POST" action="index.php?id=<?=$product['id']?>" >
                        <div class="product">
                            <div>
                                <img class="img-product" src="./images/<?= $product['id']?><?= $product['fileType']?>" alt="prod-img">
                            </div>
                            <div class="infos">
                                <h3>Title<?=$product['title']?></h3>
                                <p>Description<?=$product['description']?></p>
                                <p>Price <span style="color:blue;font-weight:bold"><?=$product['price']?> $</span></p>
                            </div>
                            <div>
                            <input type="submit" name="addCart" value="Add">
                            </div>
                        </div>
                    </form>
                 <?php endif;?>
            <?php endforeach;?>
        </main>
        <a href="cart.php" id="cart">Go to cart</a>
    </body>
</html>