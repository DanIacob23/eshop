<?php
require_once "./common.php";
require_once "./config.php";
$data = getAllProductsInfo();
function validation()
{
    if (empty($_POST["name"])
        or empty($_POST["contactDetails"])
        or empty($_POST["comments"])
    ) {
        throw new Exception(translate("on of field empty", "en"));
    }
    return true;
}

$empty = '';
try {
    validation(). "\n";
} catch (Exception $e) {
    $empty= translate("fill all fields", "en").  $e->getMessage();
}

if (isset($_POST["removeToCart"])) {
    unset($_SESSION['cart'][$_POST["id"]]);

}

if (isset($_POST["checkout"])) {
    $sender = managerMail;
    $recipient = 'daniacob587@gmail.com';
    $to = $recipient;
    $subject = translate("Website Change Requst", "en");
    $headers = translate("From", "en").': '.strip_tags($sender)."\r\n";
    $headers .= translate("Reply-To", "en").': '. strip_tags($sender) . "\r\n";
    $headers .= "CC: susan@example.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    ob_start();
    ?>
    <?php foreach ($data as $product): ?>
        <?php if(array_key_exists($product['id'], $_SESSION['cart'])): ?>
            <form method="POST" action="">
                <div class="product">
                    <?php
                    $path = './images/'. $product['id'].$product['fileType'];
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    ?>
                <img class="img-product"  src="<?= $base64 ?>" alt="<?= translate("Product Image","en") ?>"style="height:100px;width:100px;">
                <div class="infos">
                    <h3><?= translate("Title","en") ?> <?= $product['title'] ?></h3>
                    <p><?= translate("Description","en") ?> <?= $product['description'] ?></p>
                    <p id="price"><?= translate("Price","en") ?> <?= $product['price'] ?> $</p>
                </div>
                </div>
            </form>
        <?php endif;?>
    <?php endforeach;?>
    <p><?= translate("Name","en") ?>: <?= $_POST["name"] ?></p>
    <p><?= translate("Contact details","en") ?>: <?= $_POST["contactDetails"] ?></p>
    <?php
    $message = ob_get_contents();
    ob_end_clean();
    try {
        mail($to, $subject, $message, $headers). "\n";
    } catch (Exception $e) {
        echo 'Caught exception: '.  $e->getMessage();
    }
    $productsId = '';
    foreach (array_keys( $_SESSION['cart'] ) as $product) {
        $productsId = $productsId.$product.'/';
    }
    if ( !empty($_POST["name"])
        && !empty($_POST["contactDetails"])
        && !empty($_POST["comments"])
        && $productsId != ''
    ) {
        $_SESSION['cart'] = array();
        $_SESSION['lastInsertId'] = insertNewOrder(strip_tags($_POST["name"]), strip_tags($_POST["contactDetails"]), strip_tags( $_POST["comments"] ), $productsId );
        header('Location: order.php');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="eng">
    <head>
        <title><?= translate("Cart","en") ?></title>
        <link href="public/css/utils.css" rel="stylesheet">
    </head>
    <body>
        <main>
            <?php foreach ($data as $product): ?>
                <?php if(array_key_exists($product['id'], $_SESSION['cart'])): ?>
                    <form method="POST" action="" >
                        <div class="product">
                            <img class="img-product" src="./images/<?= $product['id'] ?><?= $product['fileType'] ?>" alt="<?= translate("Product Image","en") ?>">
                            <div class="infos">
                                <h3><?= translate("Title","en") ?> <?= $product['title'] ?></h3>
                                <p><?= translate("Description","en") ?> <?= $product['description'] ?></p>
                                <p id="price"><?= translate("Price","en") ?> <?= $product['price'] ?> $</p>
                            </div>
                            <div>
                                <input type="submit" name="removeToCart" value="<?= translate("Remove","en") ?>">
                                <input type="hidden" id="id" name="id" value="<?= $product['id'] ?>">
                            </div>
                        </div>
                    </form>
                <?php endif;?>
            <?php endforeach;?>
            <form method="POST" action="" >
                <div class="checkout-details">
                    <input type="text" id="name" name="name" placeholder="<?= translate("Name","en") ?>">
                    <input type="text" id="contactDetails" name="contactDetails" size="50" placeholder="<?= translate("Contact details","en") ?>">
                    <input type="text" id="comments" name="comments" size="50" placeholder="<?= translate("Comments","en") ?>">
                    <p><?= $empty ?></p>
                </div>
                <div class="checkout">
                    <a href="index.php"><?= translate("Go to index","en") ?></a>
                    <input type="submit" name="checkout" value="<?= translate("Checkout","en") ?>">
                </div>
            </form>
        </main>
    </body>
</html>
