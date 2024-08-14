<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

// get POST data from request
$rawData = file_get_contents("php://input");
$data = json_decode($rawData,true);

// assign data to variable
$email = $data['email'];
$pwd = $data['pwd'];

try {
    require_once('dbh.inc.php');

    $query = "SELECT * FROM users where email = :email AND password = :pwd;";
    $stmt=$pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pwd", $pwd);
    $stmt->execute();

    //fetch results
    $user = $stmt->fetchAll((PDO::FETCH_ASSOC));

    if ($user) {
        echo json_encode(['status'=>true, 'message'=>'Login succesfull' , 'user'=>$user]);
    } else {
        echo json_encode(['status' =>false, 'message'=> 'Invalid email or password']);
    }

    $pdo = null;
    $stmt=null;
 
}  catch (PDOException $e) {
    echo json_encode(['status' => false, 'message'=> 'Error '.$e->getMessage()]);
    die();
    //die("Query  failed: ". $e->getMessage());

}