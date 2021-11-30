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

if (isset($_POST["checkout"])) {
    //conf mail
    $productsId='';
    foreach (array_keys($_SESSION['cart'])as $product){
        $productsId=$productsId.$product.'/';
    }
    if($_POST["name"]!='' && $_POST["contactDetails"]!='' && $_POST["comments"]!='' && $productsId!=''){
        $_SESSION['cart']=array();
        insertNewOrder(strip_tags($_POST["name"]),strip_tags($_POST["contactDetails"]),strip_tags($_POST["comments"]),$productsId);
        header('Location: order.php');
        die();
    }

}

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?=translate("Cart","en")?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
    <body>
        <main>
            <?php foreach ($data as $product): ?>
                <?php if(array_key_exists($product['id'], $_SESSION['cart'])): ?>
                    <form method="POST" action="cart.php?id=<?=$product['id']?>" >
                        <div class="product">
                            <img class="img-product" src="./images/<?= $product['id']?><?= $product['fileType']?>" alt="<?=translate("Product Image","en")?>">
                            <div class="infos">
                                <h3><?=translate("Title","en")?> <?=$product['title']?></h3>
                                <p><?=translate("Description","en")?> <?=$product['description']?></p>
                                <p><?=translate("Price","en")?> <span style="color:blue;font-weight:bold"><?=$product['price']?> $</span>
                            </div>
                            <div>
                                <input type="submit" name="removeToCart" value="<?=translate("Remove","en")?>">
                            </div>
                        </div>
                    </form>
                <?php endif;?>
            <?php endforeach;?>
            <form method="POST" action="" >
                <div class="checkout-details">
                    <input type="text" id="name" name="name" placeholder="<?=translate("Name","en")?>">
                    <input type="text" id="contactDetails" name="contactDetails" size="50" placeholder="<?=translate("Contact details","en")?>">
                    <input type="text" id="comments" name="comments" size="50" placeholder="<?=translate("Comments","en")?>">
                </div>
                    <div class="checkout">
                        <a href="index.php?id=0"><?=translate("Go to index","en")?></a>
                        <input type="submit" name="checkout" value="<?=translate("Checkout","en")?>">
                    </div>
            </form>

        </main>
    </body>
</html>
