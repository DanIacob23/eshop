<?php
require_once "./common.php";
$data=getAllProductsInfo();

if (isset($_GET["editId"])){
    $title=$data[intval($_GET["editId"])-1]['title'];
    $description=$data[intval($_GET["editId"])-1]['description'];
    $price=$data[intval($_GET["editId"])-1]['price'];
}else{
    $title = 'title';
    $description= 'description';
    $price= 'price';
}
$validationPrice=true;
if (isset($_POST['price'])) {
    if($_POST['price'] != '' && !is_numeric($_POST['price'])){
        $validationPrice = false;
    }
}
$checkImg='';
function updateImage($idd,$target_file,$oldPath)
{
    global $checkImg;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;
    $extension = '.'.pathinfo(basename($target_file), PATHINFO_EXTENSION);

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $checkImg = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $checkImg =  "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        //remove old image
        unlink($oldPath);
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $checkImg =  "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $checkImg =  "Sorry, only JPG, PNG  files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $checkImg =  "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $checkImg =  "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            //update new extension
            updateProductExtension($idd,$extension);
        } else {
            $checkImg = "Sorry, there was an error uploading your file.";
        }
    }
}

function insertNewImage(){
    global $data;
    global $checkImg;
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $target_file = $target_dir .strval(count($data)+1).'.'.$imageFileType ;
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $checkImg = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $checkImg = "File is not an image.";
        $uploadOk = 0;
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        $checkImg = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $checkImg = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $checkImg = "Sorry, only JPG, PNG  files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $checkImg = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $checkImg =  "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
                $checkImg = "Sorry, there was an error uploading your file.";
               }
      }

}

if (
    isset($_POST["save"])
    && $_POST["title"]!='title'
    && $_POST["description"]!='description'
    && $_POST["price"]!='price'
    && $validationPrice)
{//after data validation
    if (isset($_GET["editId"]) ) {// update
        $title=$_POST["title"];
        $description=$_POST["description"];
        $price=$_POST["price"];
        productUpdate($_GET["editId"],$title,$description,$price);
        if ($_FILES["fileToUpload"]["name"]!='') {
            $oldPath = 'images/' . $_GET["editId"] . $data[intval($_GET["editId"]) - 1]['fileType'];// remove old image
            $extension = '.' . pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);//new extension

            updateImage($_GET["editId"], "images/" . $_GET["editId"] . $extension, $oldPath);//keep old name and update image
        }//otherwise, it keeps the old image
    } else {
        if ($_FILES["fileToUpload"]["name"]!='') {
            // insert new product USING user data
            $extension = '.' . pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
            insertNewImage();
            productInsert(htmlspecialchars($_POST["title"]),htmlspecialchars($_POST["description"]),htmlspecialchars($_POST["price"]),$extension);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title><?=translate("Product","en")?></title>
    <link href="public/css/utils.css" rel="stylesheet">
    <style>#priceErr{
            color: red;
        }</style>
</head>
<body>
<main>
    <form method="POST" action="#" enctype="multipart/form-data">
        <div class="infos">
            <input type="text" id="title" name="title"  value="<?=$title?>">
            <input type="text" id="description" name="description" value="<?=$description?>">
            <input type="text" id="price" name="price"  value="<?=$price?>">
            <?php if(!$validationPrice){echo '<p id="priceErr">Price must be numeric</p>';}?>
            <?php if($checkImg!=''){
                echo '<p>'.$checkImg.'</p>';
            }?>
        </div>
        <div class="upload">
            <label for="fileToUpload">Select image to upload</label>
            <input type="file" name="fileToUpload" id="fileToUpload"   accept="image/png, image/jpeg">
        </div>
        <div class="save">
            <a href="products.php">Products</a>
            <input type="submit" name="save" value="Save">
        </div>
    </form>
</main>
</body>
</html>
