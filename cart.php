<?php
session_start();
require_once "./common.php";
require_once "./config.php";
$data = getAllProductsInfo();

function removeToCart($id)
{
    unset( $_SESSION['cart'][$id] );
}

if (isset( $_POST["removeToCart"] )) {
    removeToCart($_GET["id"]);
}

if (isset( $_POST["checkout"] )) {
    //conf mail
    $sender = managerMail;
    $recipient = 'daniacob587@gmail.com';
    $to = $recipient;
    $subject = 'Website Change Requst';
    $headers = "From: " . strip_tags($sender) . "\r\n";
    $headers .= "Reply-To: ". strip_tags($sender) . "\r\n";
    $headers .= "CC: susan@example.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    ob_start();
    ?>
    <?php foreach ($data as $product): ?>
        <?php if(array_key_exists($product['id'], $_SESSION['cart'])): ?>
            <form method="POST" action="cart.php?id=<?=$product['id']?>" >
                <div class="product">
                    <?php
                    $path = './images/'. $product['id'].$product['fileType'];
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    ?>
                <img class="img-product"  src="<?=$base64?>" alt="<?=translate("Product Image","en")?>"style="height:100px;width:100px;">
                <div class="infos">
                    <h3><?=translate("Title","en")?> <?=$product['title']?></h3>
                    <p><?=translate("Description","en")?> <?=$product['description']?></p>
                    <p><?=translate("Price","en")?> <span style="color:blue;font-weight:bold"><?=$product['price']?> $</span>
                </div>
                </div>
            </form>
        <?php endif;?>
    <?php endforeach;?><p><?=translate("Name","en")?>: <?=$_POST["name"]?></p>
    <p><?=translate("Contact details","en")?>: <?=$_POST["contactDetails"]?></p>
    <?php
    $message = ob_get_contents();
    ob_end_clean();
    if ( mail($to, $subject, $message, $headers) ) {
    }
    $productsId = '';
    foreach ( array_keys( $_SESSION['cart'] ) as $product ) {
        $productsId = $productsId.$product.'/';
    }
    if ( $_POST["name"] != ''
        && $_POST["contactDetails"] != ''
        && $_POST["comments"] != ''
        && $productsId != ''
    ) {
        $_SESSION['cart'] = array();
        insertNewOrder( strip_tags($_POST["name"]), strip_tags($_POST["contactDetails"]), strip_tags( $_POST["comments"] ), $productsId );
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
