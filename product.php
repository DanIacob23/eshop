<?php
require_once "./common.php";
$data = getAllProductsInfo();

if (isset($_SESSION['editId'])) {
    $productAbout = selectByID($_SESSION['editId']);
}

function validation()
{
    if (empty($_POST["title"])
        or empty($_POST["description"])
        or empty($_POST["price"])
    ) {
        throw new Exception(translate('Empty field', 'en'));
    }
    return true;
}

$validationPrice = true;
if (isset($_POST['price'])) {
    if ($_POST['price'] != '' && !is_numeric($_POST['price'])) {
        $validationPrice = false;
    }
}

$empty = '';
try {
    validation() . "\n";
} catch (Exception $e) {
    $empty = translate('Fill', 'en') . $e->getMessage();
    $validationPrice = false;
}

$checkImg = '';
function updateImage($idd, $target_file, $oldPath)
{
    global $checkImg;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;
    $extension = '.' . pathinfo(basename($target_file), PATHINFO_EXTENSION);

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $checkImg = translate('File is img', 'en') . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $checkImg = translate('File is not img', 'en');
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        //remove old image
        unlink($oldPath);
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $checkImg = translate('File too large', 'en');
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $checkImg = translate('only jpg png', 'en');
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $checkImg = translate('not uploaded', 'en');
        // if everything is ok, try to upload file
    } else {

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $checkImg = htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . translate('uploaded', 'en');
            //update new extension
            updateProductExtension($idd, $extension);
        } else {
            $checkImg = translate('error uploading', 'en');
        }
    }
}

function insertNewImage()
{
    global $data;
    global $checkImg;
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $target_file = $target_dir . strval(getLastId()[0]['id']) . '.' . $imageFileType;
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $checkImg = translate('File is img', 'en') . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $checkImg = translate('File is not img', 'en');
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $checkImg = translate('File too large', 'en');
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $checkImg = translate('only jpg png', 'en');
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $checkImg = translate('not uploaded', 'en');
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $checkImg = htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . translate('uploaded', 'en');
        } else {
            $checkImg = translate('error uploading', 'en');
        }
    }

}

if (
    isset($_POST["save"])
    && isset($_POST["title"]) && !empty($_POST["title"])
    && isset($_POST["description"]) && !empty($_POST["description"])
    && isset($_POST["price"]) && !empty($_POST["price"])
    && $validationPrice
) {
    if (isset($_SESSION['editId'])) {// update
        $title = $_POST["title"];
        $description = $_POST["description"];
        $price = $_POST["price"];
        productUpdate($_SESSION['editId'], $title, $description, $price);
        if ($_FILES["fileToUpload"]["name"] != '') {
            $oldPath = 'images/' . $_POST["editId"] . $data[intval($_POST["editId"]) - 1]['fileType'];// remove old image
            $extension = '.' . pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);//new extension

            updateImage($_SESSION['editId'], "images/" . $_SESSION['editId'] . $extension, $oldPath);//keep old name and update image
        }//otherwise, it keeps the old image
    } else {
        if ($_FILES["fileToUpload"]["name"] != '') {
            // insert new product USING user data
            $extension = '.' . pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
            productInsert(htmlspecialchars($_POST["title"]), htmlspecialchars($_POST["description"]), htmlspecialchars($_POST["price"]), $extension);
            insertNewImage();
        }
    }

    header('Location: products.php');
    die();
}

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?= translate("Product", "en") ?></title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <form method="POST" enctype="multipart/form-data">
        <div class="infos">
            <?php if (isset($_SESSION['editId'])) : ?>
                <input type="text" id="title" name="title" value="<?= $productAbout[0]['title'] ?>">
                <input type="text" id="description" name="description" value="<?= $productAbout[0]['description'] ?>">
                <input type="text" id="price" name="price" value="<?= $productAbout[0]['price'] ?>">
            <?php endif; ?>
            <?php if (!isset($_SESSION['editId'])) : ?>
                <input type="text" id="title" name="title"
                       placeholder="<?= isset($_POST['title']) ? $_POST['title'] : 'title' ?>">
                <input type="text" id="description" name="description"
                       placeholder="<?= isset($_POST['description']) ? $_POST['description'] : 'description' ?>">
                <input type="text" id="price" name="price"
                       placeholder="<?= isset($_POST['price']) ? $_POST['price'] : 'price' ?>">
                <p><?= $empty ?></p>
            <?php endif; ?>
            <?php if (!$validationPrice) {
                echo '<p id="priceErr">Price must be numeric</p>';
            } ?>
            <?php if ($checkImg != '') {
                echo '<p>' . $checkImg . '</p>';
            }
            ?>
        </div>
        <div class="upload">
            <label for="fileToUpload">Select image to upload</label>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/png, image/jpeg">
        </div>
        <div class="save">
            <a href="products.php">Products</a>
            <input type="submit" name="save" value="Save">
        </div>
    </form>
</main>
</body>
</html>
