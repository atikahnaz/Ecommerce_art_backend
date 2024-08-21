<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData,true);

$user_id = intval($data['user_id']);
$product_id=$data['product_id'];
$size = $data['size'];
$quantity = intval($data['quantity']);

try {
    require_once("../auth/dbh.inc.php");

    $query = "SELECT id FROM cart WHERE user_id=:user_id;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam(":user_id",$user_id);
    $stmt->execute();
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    // assign cart_id
    $cart_id = $cart['id'];

    // check if item exist
    $query = "SELECT * FROM cart_item WHERE cart_id=:cart_id AND product_id=:product_id; AND size=:size;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam(":cart_id", $cart_id);
    $stmt->bindParam(":product_id",$product_id);
    $stmt->bindParam(":size",$size);
    $stmt->execute();
    $item_in_cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item_in_cart) {
        $item_quantity = $item_in_cart['quantity'] - $quantity;
        if ($item_quantity > 0 && $quantity) {
            $query = "UPDATE cart_item SET quantity=:quantity WHERE id=:id;";
            $stmt=$pdo->prepare($query);
            $stmt->bindParam(":id", $item_in_cart['id']);
            $stmt->bindParam(":quantity", $item_quantity);
            $stmt->execute();

            echo json_encode(['status' => true, 'message' => 'Item removed successfully']);

        } else {
            // if item quantity = 0 remove the row
            $query = "DELETE FROM cart_item WHERE id=:id;";
            $stmt =$pdo->prepare($query);
            $stmt->bindParam(":id", $item_in_cart['id']);
            $stmt->execute();
        }
           
    } else {
        echo json_encode(['status' => false, 'message' => 'Cart not found']);
    }

    if (!$product_id && !$size && !$quantity) {
        $query ="DELETE FROM cart_item WHERE cart_id=:cart_id;";
        $stmt=$pdo->prepare($query);
        $stmt->bindParam(":cart_id",$cart_id);
        $stmt->execute();
    }


} catch(PDOException $e) {
    echo json_encode(['status' => false, 'message'=> 'Error items '.$e->getMessage()]);
    die();
}

