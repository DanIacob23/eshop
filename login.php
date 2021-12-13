<?php
require_once "./common.php";
if (isset($_POST['submit'])) {
    if ($_POST['fname'] == adminUserName && $_POST['pass'] == adminPass) {
        $_SESSION['adminLogin'] = 'TRUE';
        header('Location: products.php');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Login', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <form method="POST">
        <div class="login">
            <input type="text" id="fname" name="fname" placeholder="<?= translate('Username', 'en') ?>"><br><br>
            <input type="password" id="pass" name="pass" placeholder="<?= translate('Password', 'en') ?>"><br><br>
        </div>
        <input type="submit" id="submit" name="submit" value="<?= translate('Submit', 'en') ?>">
    </form>
</main>
</body>
</html>
