<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData,true);

// extract data from user
$user_id = intval($data['user_id']);
$product_id=$data['product_id'];
$size = $data['size'];
$quantity = intval($data['quantity']);
$price = $data['price'];

try {
    require_once('../auth/dbh.inc.php');

    // check is cart exist
    $query = "SELECT id FROM cart WHERE user_id = :user_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam(":user_id",$user_id);
    $stmt->execute();
    $cart = $stmt->fetch(PDO::FETCH_ASSOC); // cart data

    if(!$cart) {
         // if cart not exist create one
        $query = "INSERT INTO cart (user_id) VALUES (:user_id);";
        $stmt=$pdo->prepare($query);
        $stmt->bindParam(":user_id",$user_id);
        $stmt->execute();
        $cart_id = $pdo->lastInsertId();

    } else {
        $cart_id=$cart['id'];
    }

   
    // check if product in 
    $query = "SELECT * FROM cart_item WHERE cart_id=:cart_id AND product_id=:product_id AND size=:size;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam(":cart_id", $cart_id);
    $stmt->bindParam(":product_id",$product_id);
    $stmt->bindParam(":size",$size);
    $stmt->execute();
    $item_in_cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item_in_cart) {
         // if product exist, update cart
        $item_quantity = $item_in_cart['quantity'] + $quantity;
        $query = "UPDATE cart_item SET quantity=:quantity WHERE id=:id;";
        $stmt=$pdo->prepare($query);
        $stmt->bindParam(":id", $item_in_cart['id']);
        $stmt->bindParam(":quantity", $item_quantity);
        $stmt->execute();
    } else {
         // if no product, insert the item
         $query = "INSERT INTO cart_item (cart_id,product_id,quantity,size) VALUES (:cart_id,:product_id,:quantity,:size);";
         $stmt=$pdo->prepare($query);
         $stmt->bindParam(":cart_id", $cart_id);
         $stmt->bindParam(":product_id",$product_id);
         $stmt->bindParam(":quantity", $quantity);
         $stmt->bindParam(":size",$size);
         $stmt->execute();
    }

    echo json_encode(['status' => true, 'message' => 'Cart updated successfully']);


} catch(PDOException $e) {
    echo json_encode(['status' => false, 'message'=> 'Error items '.$e->getMessage()]);
    die();
}

//echo json_encode(["status" => true, "id" => $user_id]);