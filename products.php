<?php
require_once "./common.php";
$data=getAllProductsInfo();
if (isset($_GET["deleteId"])) {
    if(unlink('images/'.$_GET["deleteId"].'.jpg')){
        deleteProduct($_GET["deleteId"]);
    }else{
        unlink('images/'.$_GET["deleteId"].'.png');
        deleteProduct($_GET["deleteId"]);
    }
}
if (isset($_POST["adminLogout"])) {
    header('Location: index.php');
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
                            <img class="img-product" src="./images/<?= $product['id']?><?= $product['fileType']?>" alt="<?=translate("Product Image","en")?>">
                        </div>

                        <table>
                            <div class="infos">
                                <h3><?=translate("Title","en")?>: <?=$product['title']?></h3>
                                <p><?=translate("Description","en")?>: <?=$product['description']?></p>
                                <p><?=translate("Price","en")?>: <span style="color:blue;font-weight:bold"><?=$product['price']?> $</span>
                            </div>
                        </table>

                        <form method="POST" action="product.php?editId=<?=$product['id']?>" >
                            <div>
                                <input type="submit" name="editProduct" value="<?=translate("Edit","en")?>">
                            </div>
                        </form>

                        <form method="POST" action="products.php?deleteId=<?=$product['id']?>" >
                            <div>
                                <input type="submit" name="deleteProduct" value="<?=translate("Delete","en")?>">
                            </div>
                        </form>
                    </div>
        <?php endforeach;?>

        <div class="optionsAdmin">
            <form method="POST" action="product.php" >
                <a href="product.php"><?=translate("Add","en")?></a>
            </form>
            <form method="POST" action="" >
                <input type="submit" name="adminLogout" value="<?=translate("Logout","en")?>">
            </form>
        </div>
    </main>
</body>
</html>