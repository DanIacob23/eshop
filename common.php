<?php
require_once'config.php';
class BD{
    private static $connexion_bd = NULL;

    public static function obtain_connexion(){
        $host=host;
        $dbname=db_name;
        $username=username;
        $password=password;
        if(is_null(self::$connexion_bd))
        {
            self:: $connexion_bd = new PDO("mysql:host={$host};dbname={$dbname}","{$username}","{$password}");
        }
        return self::$connexion_bd;
    }

}
//BD::obtain_connexion();

function getAllProductsInfo(){
    $sql = "SELECT * FROM products ";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute();
    return $cerere->fetchAll();
}

function productUpdate($id,$title,$description,$price){
    $sql = "UPDATE products SET  title = :title,description=:description,price=:price where id = :id";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute([
        'title' =>$title,
        'description' =>$description,
        'price'=>$price,
        'id'=>$id
    ]);
}

function productInsert($title,$description,$price,$extension){
    $sql = "INSERT INTO products(title,description,price,fileType)VALUES(:title,:description,:price,:fileType)";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute([
        'title'=>$title,//htmlspecialchars OR strip_tags For XSS attacks
        'description'=>$description,
        'price'=>$price,
        'fileType'=>$extension
    ]);
}

function updateProductExtension($id,$ext){
    $sql = "UPDATE products SET  fileType = :fileType where id = :id";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute([
        'fileType'=>$ext,
        'id'=>$id
    ]);
}

function deleteProduct($id){
    $sql = "DELETE FROM  products where id = :id";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute([
        'id'=>$id
    ]);
}

function insertNewOrder($userName,$details,$comments,$productsId){
    $sql = "INSERT INTO orders(userName,contactDetails,comments,productsId,datetime)VALUES(:userName,:contactDetails,:comments,:productsId,:datetime)";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute([
        'userName'=>$userName,//htmlspecialchars OR strip_tags For XSS attacks
        'contactDetails'=>$details,
        'comments'=>$comments,
        'productsId'=>$productsId,
        'datetime'=>date("Y/m/d")
    ]);
}

function getLastRow(){
    $sql = " select * from orders ORDER BY id DESC LIMIT 1";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute();
    return $cerere->fetchAll();
}

function selectPropertyByID($id,$property){
    $sql = "SELECT $property FROM products where id=:id";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute([
        'id'=>$id
    ]);
    return $cerere->fetchAll();
}

function getAllOrders(){
    $sql = "SELECT * FROM orders ";
    $cerere = BD::obtain_connexion()->prepare($sql);
    $cerere -> execute();
    return $cerere->fetchAll();
}