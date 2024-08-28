<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

// start session

session_start();
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
    //using fetchAll cause error cause we only need one user
    $user = $stmt->fetch((PDO::FETCH_ASSOC));

    if ($user) {
        
       $_SESSION['user_id'] = $user['id'];

        echo json_encode(['status'=>true, 'message'=>'Login succesfull' , 'user'=>$user, 'session_id'=>session_id()]);
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