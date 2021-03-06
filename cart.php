<?php
require_once "./common.php";
$data = getInCartProductsInfo($_SESSION['cart']);

$errors = [];
if (empty($_SESSION['cart'])) {
    $errors['session'] = translate('Cart is empty', 'en');
}
if (empty($_POST["name"])) {
    $errors['name'] = translate('Name not set', 'en');
}
if (empty($_POST['contactDetails'])) {
    $errors['contactDetails'] = translate('Price not set', 'en');
}
if (empty($_POST['comments'])) {
    $errors['comments'] = translate('Comments not set', 'en');
}


if (isset($_POST['removeToCart'])) {
    unset($_SESSION['cart'][$_POST['id']]);
    header('Location: cart.php');
    die();
}

if (isset($_POST['checkout']) && empty($errors)) {
    $sender = 'noreply@example.com';
    $recipient = managerMail;
    $to = $recipient;
    $subject = translate('Website Change Requst', 'en');
    $headers = translate('From', 'en') . ': ' . strip_tags($sender) . "\r\n";
    $headers .= translate('Reply-To', 'en') . ': ' . strip_tags($sender) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    ob_start();
    ?>
    <?php foreach ($data as $product): ?>
        <form method="POST">
            <div class="product">
                <?php
                $path = './images/' . $product['id'] . $product['fileType'];
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <img class="img-product" src="<?= $base64 ?>" alt="<?= translate("Product Image", "en") ?>"
                     style="height:100px;width:100px;">
                <div class="infos">
                    <h3><?= translate('Title', 'en') ?> <?= $product['title'] ?></h3>
                    <p><?= translate('Description', 'en') ?> <?= $product['description'] ?></p>
                    <p id="price"><?= translate('Price', 'en') ?> <?= $product['price'] ?> $</p>
                </div>
            </div>
        </form>
    <?php endforeach; ?>
    <p><?= translate('Name', 'en') ?>: <?= $_POST['name'] ?></p>
    <p><?= translate('Contact details', 'en') ?>: <?= $_POST['contactDetails'] ?></p>
    <?php
    $message = ob_get_contents();
    $checkMail = '';
    ob_end_clean();
    try {
        mail($to, $subject, $message, $headers) . "\n";
    } catch (Exception $e) {
        $checkMail = translate('Caught exception', 'en') . ': ' . $e->getMessage();
    }


    $lastInsertId = insertNewOrder(strip_tags($_POST['name']), strip_tags($_POST['contactDetails']), strip_tags($_POST['comments']), array_keys($_SESSION['cart']));
    $_SESSION['cart'] = array();
    header('Location: order.php?orderLastInsertId=' . $lastInsertId);
    die();
}
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Cart', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <?php foreach ($data as $product): ?>
        <form method="POST">
            <div class="product">
                <img class="img-product" src="./images/<?= $product['id'] ?><?= $product['fileType'] ?>"
                     alt="<?= translate("Product Image", "en") ?>">
                <div class="infos">
                    <h3><?= translate('Title', 'en') ?> <?= $product['title'] ?></h3>
                    <p><?= translate('Description', 'en') ?> <?= $product['description'] ?></p>
                    <p id="price"><?= translate('Price', 'en') ?> <?= $product['price'] ?> $</p>
                </div>
                <div>
                    <input type="submit" name="removeToCart" value="<?= translate('Remove', 'en') ?>">
                    <input type="hidden" id="id" name="id" value="<?= $product['id'] ?>">
                </div>
            </div>
        </form>

    <?php endforeach; ?>
    <form method="POST">
        <div class="checkout-details">
            <input type="text" id="name" name="name" placeholder="<?= translate('Name', 'en') ?>"
                   value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
            <input type="text" id="contactDetails" name="contactDetails" size="50"
                   placeholder="<?= translate('Contact details', 'en') ?>"
                   value="<?= isset($_POST['contactDetails']) ? $_POST['contactDetails'] : '' ?>">
            <input type="text" id="comments" name="comments" size="50" placeholder="<?= translate('Comments', 'en') ?>"
                   value="<?= isset($_POST['comments']) ? $_POST['comments'] : '' ?>">
            <p><?php if (!empty($errors)) {
                    foreach ($errors as $err) {
                        echo $err;
                        echo '</br>';
                    }
                } ?></p>
        </div>
        <div class="checkout">
            <a href="index.php"><?= translate('Go to index', 'en') ?></a>
            <input type="submit" name="checkout" value="<?= translate('Checkout', 'en') ?>">
        </div>
        <?php if (!empty($checkMail)): ?>
            <p><?= $checkMail ?></p>
        <?php endif; ?>
    </form>
</main>
</body>
</html>
