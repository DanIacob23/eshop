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

$translate = [
    'Product Image' => [
        'en' => 'Product Image' ,
        'ro' => 'Imagine Produs' ,
        "fr" => 'Image du produit' ,
    ],
    'Title' => [
        'en' => 'Title' ,
        'ro' => 'Titlu' ,
        'fr' => 'Titre' ,
    ],
    'Description' => [
        'en' => 'Description' ,
        'ro' => 'Descriere' ,
        'fr' => 'La description' ,
    ],
    'Price' => [
        'en' => 'Price' ,
        'ro' => 'Pret' ,
        'fr' => 'Prix' ,
    ],
    'Add' => [
        'en' => 'Add' ,
        'ro' => 'Adauga' ,
        'fr' => 'Ajouter' ,
    ],
    'Go to cart' => [
        'en' => 'Go to cart' ,
        'ro' => 'Catre cos' ,
        'fr' => 'Quatre corps' ,
    ],
    'Index' => [
        'en' => 'Index' ,
        'ro' => 'Index' ,
        'fr' => 'Indice' ,
    ],
    'Cart' => [
        'en' => 'Cart' ,
        'ro' => 'Cos cumparaturi' ,
        'fr' => 'Chariot' ,
    ],
    'Remove' => [
        'en' => 'Remove' ,
        'ro' => 'Scoate din cos' ,
        'fr' => 'Supprimer' ,
    ],
    'Go to index' => [
        'en' => 'Go to index' ,
        'ro' => 'Catre index' ,
        'fr' => 'Aller à l\'index' ,
    ],
    'Checkout'=> [
        'en' => 'Checkout' ,
        'ro' => 'Achizitioneaza' ,
        'fr' => 'ACQUIERT' ,
    ],
    'Name' => [
        'en' => 'Name' ,
        'ro' => 'Nume' ,
        'fr' => 'Nom' ,
    ],
    'Contact details' => [
        'en' => 'Contact details' ,
        'ro' => 'Detalii de contact' ,
        'fr' => 'Détails du contact' ,
    ],
    'Comments' => [
        'en' => 'Comments' ,
        'ro' => 'Comentarii' ,
        'fr' => 'Commentaires' ,
    ],
    'Login' => [
        'en' => 'Login' ,
        'ro' => 'Conecteaza-te' ,
        'fr' => 'Connexion' ,
    ],
    'Username' => [
        'en' => 'Username' ,
        'ro' => 'Nume utilizator' ,
        'fr' => 'Nom d\'utilisateur' ,
    ],
    'Password' => [
        'en' => 'Password' ,
        'ro' => 'Parola' ,
        'fr' => 'Mot de passe' ,
    ],
    'Submit' => [
        'en' => 'Submit' ,
        'ro' => 'Trimite' ,
        'fr' => 'Soumettre' ,
    ],
    'Order' => [
        'en' => 'Order' ,
        'ro' => 'Comanda' ,
        'fr' => 'Ordre' ,
    ],
    'Orders' => [
        'en' => 'Orders' ,
        'ro' => 'Comenzi' ,
        'fr' => 'Ordres' ,
    ],
    'Order date' => [
        'en' => "Order date" ,
        'ro' => 'Data comenzii' ,
        'fr' => 'Date de commande' ,
    ],
    'Total price' => [
        'en' => 'Total price' ,
        'ro' => 'Total price for this order' ,
        'fr' => 'Prix total' ,
    ],
    'Product' => [
        'en' => 'Product' ,
        'ro' => 'Produs' ,
        'fr' => 'Produit' ,
    ],
    'Edit' => [
        'en' => 'Edit' ,
        'ro' => 'Editare' ,
        'fr' => 'Edition' ,
    ],
    'Delete' => [
        'en' => 'Delete' ,
        'ro' => 'Sterge' ,
        'fr' => 'Supprimer' ,
    ],
    'Logout' => [
        'en' => 'Logout' ,
        'ro' => 'Deconectare' ,
        'fr' => 'Coupure' ,
    ],
    'Empty field' => [
        'en' => 'One of the field is empty' ,
        'ro' => 'Un camp este gol' ,
        'fr' => 'One of the field is empty' ,
    ],
    'Fill' => [
        'en' => 'Fill in all the fields:' ,
        'ro' => 'Completeaza toate campurile' ,
        'fr' => 'Fill in all the fields:' ,
    ],
    'File is img' => [
        'en' => 'File is an image - ' ,
        'ro' => 'Fisierul este o imiagine' ,
        'fr' => 'File is an image - ' ,
    ],
    'File is not img' => [
        'en' => 'File is not an image - ' ,
        'ro' => 'Fisierul nu este o imiagine' ,
        'fr' => 'File is not an image - ' ,
    ],
    'File too large' => [
        'en' => 'Sorry, your file is too large' ,
        'ro' => 'Imaginea este prea mare' ,
        'fr' => 'Sorry, your file is too large' ,
    ],
    'only jpg png' => [
        'en' => 'Sorry, only JPG, PNG  files are allowed' ,
        'ro' => 'Doar fisiere JPG sau PNG' ,
        'fr' => 'Sorry, only JPG, PNG  files are allowed' ,
    ],
    'not uploaded' => [
        'en' => 'Sorry, your file was not uploaded' ,
        'ro' => 'Fisierul nu a fost incarcat' ,
        'fr' => 'Sorry, your file was not uploaded' ,
    ],
    'uploaded' => [
        'en' => 'has been uploaded' ,
        'ro' => 'a fost incarcat' ,
        'fr' => 'has been uploaded' ,
    ],
    'error uploading' => [
        'en' => 'Sorry, there was an error uploading your file' ,
        'ro' => 'A aparut o eroare la incaracarea fisierului tau' ,
        'fr' => 'Sorry, there was an error uploading your file' ,
    ],

];
function translate($text,$language)
{
    global $translate;
    return $translate[$text][$language];
}


function getInCartProductsInfo($session)
{   $str='(0';
    foreach (array_keys($session) as $item) {
        $str = $str.$item.',';
    }
    $str = rtrim($str,",").')';
    $sql = 'SELECT * FROM products WHERE id  NOT IN '.$str;
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute();
    return $request ->fetchAll();
}

function getAllProductsInfo()
{
    $sql = "SELECT * FROM products ";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute();
    return $request ->fetchAll();
}

function productUpdate($id,$title,$description,$price)
{
    $sql = "UPDATE products SET  title = :title,description=:description,price=:price where id = :id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute([
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'id' => $id
    ]);
}

function productInsert($title,$description,$price,$extension){
    $sql = "INSERT INTO products(title,description,price,fileType)VALUES(:title,:description,:price,:fileType)";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute([
        'title' => $title,//htmlspecialchars OR strip_tags For XSS attacks
        'description' => $description,
        'price' => $price,
        'fileType' => $extension
    ]);
}

function updateProductExtension($id,$ext){
    $sql = "UPDATE products SET  fileType = :fileType where id = :id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute([
        'fileType' => $ext,
        'id' => $id
    ]);
}

function deleteProduct($id){
    $sql = "DELETE FROM  products where id = :id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute([
        'id' => $id
    ]);
}

function insertNewOrder($userName,$details,$comments,$productsId){
    $sql = "INSERT INTO orders(userName,contactDetails,comments,productsId,datetime)VALUES(:userName,:contactDetails,:comments,:productsId,:datetime)";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute([
        'userName' => $userName,//htmlspecialchars OR strip_tags For XSS attacks
        'contactDetails' => $details,
        'comments' => $comments,
        'productsId' => $productsId,
        'datetime' => date("Y/m/d")
    ]);
    $req = BD::obtain_connexion()->lastInsertId();//insert ninto pivit table using lastinsertId::pdo
    $produsctaArray = explode("/",trim($productsId, '/'));
    $str='';
    foreach($produsctaArray as $item){
        $str=$str.',('.$req.','.$item.')';
    }
    $str = ltrim($str, ',');
    $sql = 'INSERT INTO pivot_order(idProd,idOrder) VALUES '.$str;
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute();
    return $req;

}

function getLastId(){
    $sql = " select id from products ORDER BY id DESC LIMIT 1";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute();
    return $request -> fetchAll();
}

function getLastRow($lastInsertId){
    $sql = 'select * from orders where id = '.$lastInsertId.' ORDER BY id DESC LIMIT 1';
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute();
    return $request -> fetchAll();
}

function selectPropertyByID($id,$property){
    $sql = "SELECT $property FROM products where id=:id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute([
        'id' => $id
    ]);
    return $request ->fetchAll();
}

function selectByID($id){
    $sql = "SELECT * FROM products where id=:id";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute([
        'id' => $id
    ]);
    return $request ->fetchAll();
}

function getAllOrders(){
    $sql = "SELECT * FROM orders ";
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute();
    return $request -> fetchAll();
}

function leftJoinProducts($lastInsertId){
    $sql = 'SELECT id,title,description,price,fileType FROM products p LEFT JOIN pivot_order o ON p.id = o.idOrder where o.idProd = '.$lastInsertId;
    $request = BD::obtain_connexion()->prepare($sql);
    $request -> execute();
    return $request -> fetchAll();
}