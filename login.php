<?php
require_once'config.php';
require_once "./common.php";
if (isset($_POST["submit"])) {
    if($_POST["fname"]==adminUserName && $_POST["pass"]==adminPass){
        header('Location: products.php');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?=translate("Login","en")?></title>
    <style>
        #submit{margin-left: 10rem;
                width: 20rem;}
        input{width: 30rem;
            height: 2rem;}
    </style>
</head>
<body>
    <main>
        <form method="POST" action="products.php">
            <div class="login">
                <input type="text" id="fname" name="fname" placeholder="<?=translate("Username","en")?>"><br><br>
                <input type="password" id="pass" name="pass" placeholder="<?=translate("Password","en")?>"><br><br>
            </div>
            <input type="submit" id="submit" value="<?=translate("Submit","en")?>">
        </form>
    </main>
</body>
</html>
