<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData,true);

// convert to integer
$user_id = intval($data['user_id']);

// get items from database
try {
    require_once('../auth/dbh.inc.php');

    $query = "SELECT cart_item.* FROM cart_item INNER JOIN 
    cart ON cart.id = cart_item.cart_id WHERE cart.user_id = :user_id;";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status'=>true, 'message'=>"item retrieve", 'items'=>$items]);

} catch(PDOException $e) {
    echo json_encode(['status' => false, 'message'=> 'Error items '.$e->getMessage()]);
    die();
}

//echo json_encode(["status" => true, "id" => $user_id]);