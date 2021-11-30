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

$translate = array();
$translate['Product Image'] = array(
    "en" => "Product Image",
    "ro" =>"Imagine Produs",
    "fr" => "Image du produit"
);
$translate['Title'] = array(
    "en" => "Title",
    "ro" =>"Titlu",
    "fr" => "Titre"
);
$translate['Description'] = array(
    "en" => "Description",
    "ro" =>"Descriere",
    "fr" => "La description"
);
$translate['Price'] = array(
    "en" => "Price",
    "ro" =>"Pret",
    "fr" => "Prix"
);
$translate['Add'] = array(
    "en" => "Add",
    "ro" =>"Adauga",
    "fr" => "Ajouter"
);
$translate['Go to cart'] = array(
    "en" => "Go to cart",
    "ro" =>"Catre cos",
    "fr" => "Quatre corps"
);
$translate['Index'] = array(
    "en" => "Index",
    "ro" =>"Index",
    "fr" => "Indice"
);
$translate['Cart'] = array(
    "en" => "Cart",
    "ro" =>"Cos cumparaturi",
    "fr" => "Chariot"
);
$translate['Remove'] = array(
    "en" => "Remove",
    "ro" =>"Scoate din cos",
    "fr" => "Supprimer"
);
$translate['Go to index'] = array(
    "en" => "Go to index",
    "ro" =>"Catre index",
    "fr" => "Aller à l'index"
);
$translate['Checkout'] = array(
    "en" => "Checkout",
    "ro" =>"Achizitioneaza",
    "fr" => "ACQUIERT"
);
$translate['Name'] = array(
    "en" => "Name",
    "ro" =>"Nume",
    "fr" => "Nom"
);
$translate['Contact details'] = array(
    "en" => "Contact details",
    "ro" =>"Detalii de contact",
    "fr" => "Détails du contact"
);
$translate['Comments'] = array(
    "en" => "Comments",
    "ro" =>"Comentarii",
    "fr" => "Commentaires"
);
$translate['Login'] = array(
    "en" => "Login",
    "ro" =>"Conecteaza-te",
    "fr" => "Connexion"
);
$translate['Username'] = array(
    "en" => "Username",
    "ro" =>"Nume utilizator",
    "fr" => "Nom d'utilisateur"
);
$translate['Password'] = array(
    "en" => "Password",
    "ro" =>"Parola",
    "fr" => "Mot de passe"
);
$translate['Submit'] = array(
    "en" => "Submit",
    "ro" =>"Trimite",
    "fr" => "Soumettre"
);
$translate['Order'] = array(
    "en" => "Order",
    "ro" =>"Comanda",
    "fr" => "Ordre"
);
$translate['Orders'] = array(
    "en" => "Orders",
    "ro" =>"Comenzi",
    "fr" => "Ordres"
);
$translate['Order date'] = array(
    "en" => "Order date",
    "ro" =>"Data comenzii",
    "fr" => "Date de commande"
);
$translate['Total price'] = array(
    "en" => "Total price",
    "ro" =>"Total price for this order",
    "fr" => "Prix total"
);
$translate['Product'] = array(
    "en" => "Product",
    "ro" =>"Produs",
    "fr" => "Produit"
);
$translate['Edit'] = array(
    "en" => "Edit",
    "ro" =>"Editare",
    "fr" => "Edition"
);
$translate['Delete'] = array(
    "en" => "Delete",
    "ro" =>"Sterge",
    "fr" => "Supprimer"
);
$translate['Logout'] = array(
    "en" => "Logout",
    "ro" =>"Deconectare",
    "fr" => "Coupure"
);
function translate($text,$language){
    global $translate;
    return $translate[$text][$language];
}

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