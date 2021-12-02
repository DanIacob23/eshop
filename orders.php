<?php
require_once "./common.php";
$data = getAllOrders();
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?=translate("Orders","en")?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <?php foreach ($data as $product): ?>
        <div class="orders">
            <p id="datetime"><?=translate("Order date","en")?>: <?=$product['datetime']?></p>
            <p><?=translate("Name","en")?>: <?=$product['userName']?></p>
            <p><?=translate("Contact details","en")?>: <?=$product['contactDetails']?></p>
            <p><?=translate("Comments","en")?>: <?=$product['comments']?></p>
            <?php $totalPrice=0;?>
            <?php foreach ($productsId = ( explode('/', trim($product['productsId'], '/'))) as $item): ?>
                <?php $totalPrice = $totalPrice + intval(selectPropertyByID($item,'price')[0]['price']) ?>
                <div class="productsImage">
                    <img class="img-product" src="./images/<?=$item?><?=selectPropertyByID($item,'fileType')[0]['fileType']?>" alt="<?=translate("Product Image","en")?>">
                </div>
                <div class="infos">
                    <h3><?=translate("Title","en")?>: <?=selectPropertyByID($item,'title')[0]['title']?></h3>
                    <p><?=translate("Description","en")?>: <?=selectPropertyByID($item,'description')[0]['description']?></p>
                    <p><?=translate("Price","en")?> <span style="color:blue;font-weight:bold"><?=selectPropertyByID($item,'price')[0]['price']?> $</span></p>
                </div>
            <?php endforeach;?>
            <p><?=translate("Total price","en")?>:  <span style="color:red;font-weight:bold"><?=$totalPrice?>  $</span></p>
        </div>
    <?php endforeach;?>
</main>
</body>
</html>
