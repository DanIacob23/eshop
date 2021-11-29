<?php
require_once'config.php';
if(isset($_POST["submit"])){
    if($_POST["fname"]==adminUserName && $_POST["pass"]==adminPass){
        header('Location: products.php');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title>Login</title>
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
                <input type="text" id="fname" name="fname" placeholder="Username"><br><br>
                <input type="password" id="pass" name="pass" placeholder="Password"><br><br>
            </div>
            <input type="submit" id="submit">
        </form>
    </main>
</body>
</html>
