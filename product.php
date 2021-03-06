<?php
require_once "./common.php";
checkAdminLogin();
$data = getAllProductsInfo();

if (isset($_GET['editId'])) {
    $productAbout = selectByID($_GET['editId']);
}

$validationPrice = true;
if (isset($_POST['price'])) {
    if ($_POST['price'] != '' && !is_numeric($_POST['price'])) {
        $validationPrice = false;
    }
}

$errors = [];
if (empty($_POST['title'])) {
    $errors['name'] = translate('Name not set', 'en');
}
if (empty($_POST['description'])) {
    $errors['description'] = translate('Description not set', 'en');
}
if (empty($_POST['price'])) {
    $errors['price'] = translate('Price not set', 'en');
}
$checkImg = '';


if (
    isset($_POST['save'])
    && empty($errors)
    && $validationPrice
) {
    if (isset($_GET['editId'])) {// update
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        productUpdate($_GET['editId'], $title, $description, $price);
        if ($_FILES['fileToUpload']['name'] != '') {
            $oldPath = 'images/' . $_POST['editId'] . $data[intval($_POST['editId']) - 1]['fileType'];// remove old image
            $extension = '.' . pathinfo(basename($_FILES['fileToUpload']['name']), PATHINFO_EXTENSION);//new extension
            $checkImg = updateImage($_GET['editId'], 'images/' . $_GET['editId'] . $extension, $oldPath);//keep old name and update image
        }
        header('Location: products.php');
        die();
        //otherwise, it keeps the old image
    } else {
        if ($_FILES['fileToUpload']['name'] != '') {
            // insert new product USING user data
            $extension = '.' . pathinfo(basename($_FILES['fileToUpload']['name']), PATHINFO_EXTENSION);
            $lastId = productInsert(htmlspecialchars($_POST['title']), htmlspecialchars($_POST['description']), htmlspecialchars($_POST['price']), $extension);
            $checkImg = insertNewImage($lastId);
            header('Location: products.php');
            die();
        } else {
            $errors['image'] = translate('not img upload', 'en');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate('Product', 'en') ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <form method="POST" enctype="multipart/form-data">
        <div class="infos">
            <input type="text" id="title" name="title" placeholder="title"
                   value="<?= isset($_POST['title']) ? $_POST['title'] : (isset($_GET['editId']) ? $productAbout[0]['title'] : '') ?>">
            <input type="text" id="description" placeholder="description" name="description"
                   value="<?= isset($_POST['description']) ? $_POST['description'] : (isset($_GET['editId']) ? $productAbout[0]['description'] : '') ?>">
            <input type="text" id="price" name="price" placeholder="price"
                   value="<?= isset($_POST['price']) ? $_POST['price'] : (isset($_GET['editId']) ? $productAbout[0]['price'] : '') ?>">
            <?php if ((!empty($errors))):
                foreach ($errors as $err) {
                    echo '<p id=errColor>'.$err.'</p>';
                    echo '</br>';
                } ?>
            <?php endif; ?>


            <?php if (!$validationPrice): ?>
                <p id="priceErr"><?= translate('Price must be numeric','en') ?></p>
            <?php endif; ?>

            <?php if ($checkImg != '') : ?>
                <p><?= $checkImg ?></p>
            <?php endif; ?>
        </div>
        <div class="upload">
            <label for="fileToUpload"><?= translate('Select image to upload','en') ?></label>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/png, image/jpeg">
        </div>
        <div class="save">
            <a href="products.php">Products</a>
            <input type="submit" name="save" value="<?= translate('Save','en') ?>">
        </div>
    </form>
</main>
</body>
</html>
