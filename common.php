<?php
session_start();
require_once 'config.php';
require_once 'translations.php';

class BD
{
    private static $connexion_bd = NULL;

    public static function obtain_connexion()
    {
        $host = host;
        $dbname = db_name;
        $username = username;
        $password = password;
        if (is_null(self::$connexion_bd)) {
            self:: $connexion_bd = new PDO("mysql:host={$host};dbname={$dbname}", "{$username}", "{$password}");
        }
        return self::$connexion_bd;
    }

}

function getAllProdSort($item, $option, $offset, $limit)
{
    $sql = 'SELECT * FROM products  ORDER BY ' . $item . ' ' . $option . ' LIMIT ' . $offset . ', ' . $limit;
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute();
    return $request->fetchAll();
}

//BD::obtain_connexion();
function paginationIndexProducts($cart, $offset, $limit)
{
    $productIds = array_keys($cart);
    if (!empty($productIds)) {
        $arr = array_fill(0, count($productIds), "?");
        $sql = 'SELECT * FROM products WHERE id  NOT IN ' . '(' . implode(",", $arr) . ') LIMIT ' . $offset . ', ' . $limit;
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute($productIds);
        return $request->fetchAll();
    } else {
        $sql = 'SELECT * FROM products LIMIT ' . $offset . ', ' . $limit;
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute($productIds);
        return $request->fetchAll();
    }
}

function sortProductsByItemIndex($cart, $item, $option, $offset, $limit)
{
    $productIds = array_keys($cart);

    if (!empty($productIds)) {
        $arr = array_fill(0, count($productIds), "?");
        $sql = 'SELECT * FROM products WHERE id  NOT IN ' . '(' . implode(",", $arr) . ')' . ' ORDER BY ' . $item . ' ' . $option . ' LIMIT ' . $offset . ', ' . $limit;
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute($productIds);
        return $request;
    } else {
        $sql = 'SELECT * FROM products ORDER BY ' . $item . ' ' . $option . ' LIMIT ' . $offset . ', ' . $limit;
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute();
        return $request;
    }
}

function joinOrders()
{
    $sql = 'SELECT
	m.*,
	SUM(p.price) as total
FROM
	products p
	LEFT JOIN pivot_order o ON p.id = o.idProd
	LEFT JOIN orders m ON m.id = o.idOrder
	GROUP BY m.id';
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute();
    return $request->fetchAll();
}

function leftJoinProducts($lastInsertId)
{
    $sql = 'SELECT p.id,p.title,p.description,p.price,p.fileType,ord.userName,ord.contactDetails,ord.comments FROM products p LEFT JOIN pivot_order o ON p.id = o.idProd LEFT JOIN orders ord ON ord.id=o.idOrder where o.idOrder = ?';
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([$lastInsertId]);
    return $request->fetchAll();
}

function deleteProductFromOrders($idProduct)
{
    $sql = " DELETE FROM pivot_order WHERE idProd = :idProd";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'idProd' => $idProduct
    ]);
    return $request->fetchAll();
}

function translate($text, $language)
{
    global $translate;
    return $translate[$text][$language];
}


function getInCartProductsInfo($cart)
{
    $productIds = array_keys($cart);
    $arr = array_fill(0, count($productIds), "?");
    $sql = 'SELECT * FROM products WHERE id  IN (' . implode(",", $arr) . ')';
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute($productIds);
    return $request->fetchAll();
}

function getNotInCartProductsInfo($cart)
{
    $productIds = array_keys($cart);
    if (!empty($productIds)) {
        $arr = array_fill(0, count($productIds), "?");
        $sql = 'SELECT * FROM products WHERE id  NOT IN ' . '(' . implode(",", $arr) . ')';
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute($productIds);
        return $request->fetchAll();
    } else {
        $sql = 'SELECT * FROM products';
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute($productIds);
        return $request->fetchAll();
    }
}

function checkAdminLogin()
{
    if (!isset($_SESSION['adminLogin'])) {
        die('Admin logout');
    }

}


function getAllProductsInfo()
{
    $sql = "SELECT * FROM products ";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute();
    return $request->fetchAll();
}

function productUpdate($id, $title, $description, $price)
{
    $sql = "UPDATE products SET  title = :title,description=:description,price=:price where id = :id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'id' => $id
    ]);
}

function productInsert($title, $description, $price, $extension)
{
    $sql = "INSERT INTO products(title,description,price,fileType)VALUES(:title,:description,:price,:fileType)";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'title' => $title,//htmlspecialchars OR strip_tags For XSS attacks
        'description' => $description,
        'price' => $price,
        'fileType' => $extension
    ]);
    $req = BD::obtain_connexion()->lastInsertId();
    return $req;
}

function updateProductExtension($id, $ext)
{
    $sql = "UPDATE products SET  fileType = :fileType where id = :id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'fileType' => $ext,
        'id' => $id
    ]);
}

function deleteProduct($id)
{
    $sql = "DELETE FROM  products where id = :id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'id' => $id
    ]);
}

function insertNewOrder($userName, $details, $comments, $productsIds)
{
    $sql = "INSERT INTO orders(userName,contactDetails,comments,datetime) VALUES (:userName,:contactDetails,:comments,:datetime)";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'userName' => $userName,//htmlspecialchars OR strip_tags For XSS attacks
        'contactDetails' => $details,
        'comments' => $comments,
        'datetime' => date("Y/m/d")
    ]);
    $req = BD::obtain_connexion()->lastInsertId();//insert into pivot table using lastInsertId::pdo

    $arr = array_fill(0, count($productsIds), "(?,?)");

    $sql = 'INSERT INTO pivot_order(idProd,idOrder) VALUES ' . implode(",", $arr);
    $request = BD::obtain_connexion()->prepare($sql);
    $valueForExec = [];
    foreach ($productsIds as $item) {
        array_push($valueForExec, $item);
        array_push($valueForExec, $req);
    }
    $request->execute($valueForExec);
    return $req;

}


function selectByID($id)
{
    $sql = "SELECT * FROM products where id=:id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'id' => $id
    ]);
    return $request->fetchAll();
}

function updateImage($idd, $target_file, $oldPath)
{
    $checkImg = '';
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;
    $extension = '.' . pathinfo(basename($target_file), PATHINFO_EXTENSION);

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
    if ($check !== false) {
        $checkImg = translate('File is img', 'en') . $check['mime'];
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
    if ($_FILES['fileToUpload']['size'] > 5000000) {
        $checkImg = translate('File too large', 'en');
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        $checkImg = translate('only jpg png', 'en');
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $checkImg = translate('not uploaded', 'en');
        // if everything is ok, try to upload file
    } else {

        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            $checkImg = htmlspecialchars(basename($_FILES['fileToUpload']['name'])) . translate('uploaded', 'en');
            //update new extension
            updateProductExtension($idd, $extension);
        } else {
            $checkImg = translate('error uploading', 'en');
        }
    }
    return $checkImg;
}

function insertNewImage($lastId)
{
    $checkImg = '';
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $target_file = $target_dir . strval($lastId) . '.' . $imageFileType;
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
    if ($check !== false) {
        $checkImg = translate('File is img', 'en') . $check['mime'];
        $uploadOk = 1;
    } else {
        $checkImg = translate('File is not img', 'en');
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['fileToUpload']['size'] > 5000000) {
        $checkImg = translate('File too large', 'en');
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        $checkImg = translate('only jpg png', 'en');
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $checkImg = translate('not uploaded', 'en');
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            $checkImg = htmlspecialchars(basename($_FILES['fileToUpload']['name'])) . translate('uploaded', 'en');
        } else {
            $checkImg = translate('error uploading', 'en');
        }
    }
    return $checkImg;

}
