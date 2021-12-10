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

//BD::obtain_connexion();
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

function translate($text, $language)
{
    global $translate;
    return $translate[$text][$language];
}


function getInCartProductsInfo($session)
{
    $arr = array_fill(0, count(array_keys($session)), "?");
    $sql = 'SELECT * FROM products WHERE id  IN ' . '(' . implode(",", $arr) . ')';
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute(array_keys($session));
    return $request->fetchAll();
}

function getNotInCartProductsInfo($session)
{
    if (!empty(array_keys($session))) {
        $arr = array_fill(0, count(array_keys($session)), "?");
        $sql = 'SELECT * FROM products WHERE id  NOT IN ' . '(' . implode(",", $arr) . ')';
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute(array_keys($session));
        return $request->fetchAll();
    } else {
        $sql = 'SELECT * FROM products';
        $request = BD::obtain_connexion()->prepare($sql);
        $request->execute(array_keys($session));
        return $request->fetchAll();
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

function insertNewOrder($userName, $details, $comments, $productsId)
{
    $sql = "INSERT INTO orders(userName,contactDetails,comments,productsId,datetime)VALUES(:userName,:contactDetails,:comments,:productsId,:datetime)";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'userName' => $userName,//htmlspecialchars OR strip_tags For XSS attacks
        'contactDetails' => $details,
        'comments' => $comments,
        'productsId' => $productsId,
        'datetime' => date("Y/m/d")
    ]);
    $req = BD::obtain_connexion()->lastInsertId();//insert ninto pivit table using lastinsertId::pdo
    $produsctaArray = explode("/", trim($productsId, '/'));
    $str = '';
    foreach ($produsctaArray as $item) {
        $str = $str . ',(' . $item . ',' . $req . ')';
    }
    $str = ltrim($str, ',');
    $sql = 'INSERT INTO pivot_order(idProd,idOrder) VALUES ' . $str;
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute();
    return $req;

}

function getLastRow($lastInsertId)
{
    $sql = 'select * from orders where id = ' . $lastInsertId;
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute();
    return $request->fetchAll();
}

function selectPropertyByID($id, $property)
{
    $sql = "SELECT $property FROM products where id=:id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute([
        'id' => $id
    ]);
    return $request->fetchAll();
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

function getAllOrders()
{
    $sql = "SELECT * FROM orders ";
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute();
    return $request->fetchAll();
}

function leftJoinProducts($lastInsertId)
{
    $sql = 'SELECT id,title,description,price,fileType FROM products p LEFT JOIN pivot_order o ON p.id = o.idOrder where o.idProd = ' . $lastInsertId;
    $request = BD::obtain_connexion()->prepare($sql);
    $request->execute();
    return $request->fetchAll();
}