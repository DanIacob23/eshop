<?php
require_once "./common.php";
$data=getAllProductsInfo();
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
                            <img class="img-product" src="./images/<?= $product['id']?><?= $product['fileType']?>" alt="prod-img">
                        </div>

                        <table>
                            <div class="infos">
                                <h3>Title: <?=$product['title']?></h3>
                                <p>Description: <?=$product['description']?></p>
                                <p>Price: <span style="color:blue;font-weight:bold"><?=$product['price']?> $</span>
                            </div>
                        </table>

                        <form method="POST" action="product.php?editId=<?=$product['id']?>" >
                            <div>
                                <input type="submit" name="editProduct" value="Edit">
                            </div>
                        </form>

                        <form method="POST" action="products.php?deleteId=<?=$product['id']?>" >
                            <div>
                                <input type="submit" name="deleteProduct" value="Delete">
                            </div>
                        </form>
                    </div>
        <?php endforeach;?>

        <div class="optionsAdmin">
            <form method="POST" action="product.php" >
                 <input type="submit" name="adminAdd" value="Add">
            </form>
            <input type="submit" name="adminLogout" value="Logout">
        </div>

    </main>
</body>
</html>