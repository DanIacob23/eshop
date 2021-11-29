<?php
require_once "./common.php";
$data=getAllOrders();
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
        <div class="orders">
            <p id="datetime">Order date: <?=$product['datetime']?></p>
            <p>Client details: <?=$product['userName']?></p>
            <p>Client contact: <?=$product['contactDetails']?></p>
            <p>Comments: <?=$product['comments']?></p>
            <?php $totalPrice=0;?>
            <?php foreach ($productsId = ( explode('/', trim($product['productsId'], '/'))) as $item): ?>
                <?php $totalPrice = $totalPrice + intval(selectPropertyByID($item,'price')[0]['price']) ?>
                <div class="productsImage">
                    <img class="img-product" src="./images/<?=$item?><?=selectPropertyByID($item,'fileType')[0]['fileType']?>" alt="prod-img">
                </div>
                <div class="infos">
                    <h3>Title: <?=selectPropertyByID($item,'title')[0]['title']?></h3>
                    <p>Description: <?=selectPropertyByID($item,'description')[0]['description']?></p>
                    <p>Price <span style="color:blue;font-weight:bold"><?=selectPropertyByID($item,'price')[0]['price']?> $</span></p>
                </div>
            <?php endforeach;?>
            <p>Total price for this order:  <span style="color:red;font-weight:bold"><?=$totalPrice?>  $</span></p>
        </div>
    <?php endforeach;?>
</main>
</body>
</html>
