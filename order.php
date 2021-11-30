<?php
require_once "./common.php";
$lastRow = getLastRow();
$productsId=( explode('/', trim($lastRow[0]['productsId'], '/')));
//-------------------------------------------
$sender = 'daniacob587@gmail.com';
$recipient = 'daniacob587@gmail.com';
$to = $sender;

$subject = 'Website Change Reqest';

$headers = "From: " . strip_tags($sender) . "\r\n";
$headers .= "Reply-To: ". strip_tags($sender) . "\r\n";
$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = '<p><strong>This is strong text</strong> while this is not.</p>';



if (mail($to, $subject, $message, $headers))
{
    echo "Message accepted!!!";
    mail($to, $subject, $message, $headers);
}
else
{
    echo "Error: Message not accepted";
}
?>

<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?=translate("Order","en")?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <div class="order">
        <ul>
            <li><?=translate("Name","en")?>: <?= $lastRow[0]['userName']?></li>
            <li><?=translate("Contact details","en")?>: <?= $lastRow[0]['contactDetails']?></li>
            <li><?=translate("Comments","en")?>: <?= $lastRow[0]['comments']?></li>
        </ul>
        <h3><?=translate("Cart","en")?>: </h3>
        <div class="cart">
            <?php foreach (array_values($productsId )as $id): ?>
                <div class="product">
                    <div>
                        <img class="img-product" src="./images/<?=$id?><?=selectPropertyByID($id,'fileType')[0]['fileType']?>" alt="<?=translate("Product Image","en")?>">
                    </div>
                    <div class="infos">
                        <h3><?=translate("Title","en")?> <?=selectPropertyByID($id,'title')[0]['title']?></h3>
                        <p><?=translate("Description","en")?> <?=selectPropertyByID($id,'description')[0]['description']?></p>
                        <p><?=translate("Price","en")?> <span style="color:blue;font-weight:bold"><?=selectPropertyByID($id,'price')[0]['price']?> $</span></p>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</main>
</body>
