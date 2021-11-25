<?php
require_once "./common.php";
$data=getAllProductsInfo();

if (isset($_GET["editId"])){
    $title=$data[intval($_GET["editId"])-1]['title'];
    $description=$data[intval($_GET["editId"])-1]['description'];
    $price=$data[intval($_GET["editId"])-1]['price'];
}else{
    $title ='title';
    $description='description';
    $price='price';
}
function updateImage($idd,$target_file,$oldPath){
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;
    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    $extension = pathinfo(basename($target_file), PATHINFO_EXTENSION);
    echo $extension.'hellll';
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    ) {
        echo "Sorry, only JPG, PNG  files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        //remove old image
        unlink($oldPath);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo  "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            //update new extension
            updateProductExtension($idd,$extension);
        }else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if(isset($_POST["save"]) && $_POST["title"]!='title' && $_POST["description"]!='description' &&$_POST["price"]!='price')
{//after data validation
    if(isset($_GET["editId"])){// update
        /*productUpdate($_GET["editId"],$title,$description,$price);*/
        $oldPath='images/'.$_GET["editId"].'.'.$data[intval($_GET["editId"])-1]['fileType'];// remove old image
        $extension = '.'.pathinfo(basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);//new extension
        echo $extension.'11111';
        updateImage($_GET["editId"],"images/".$_GET["editId"].$extension,$oldPath);//keep old name and update image
    }else{// insert new product USING user data
        /*productInsert(htmlspecialchars($_POST["title"]),htmlspecialchars($_POST["description"]),htmlspecialchars($_POST["price"]));*/
    }
}

?>
<!DOCTYPE html>
<html lang="eng">
<head>
    <title>Product</title>
    <link href="public/css/utils.css" rel="stylesheet">
</head>
<body>
<main>
    <form method="POST" action="#" enctype="multipart/form-data">
        <div class="infos">
            <input type="text" id="title" name="title"  value="<?=$title?>">
            <input type="text" id="description" name="description" value="<?=$description?>">
            <input type="text" id="price" name="price"  value="<?=$price?>">
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
