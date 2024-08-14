<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

// get POST data from request
$rawData = file_get_contents("php://input");
$data = json_decode($rawData,true);

// assign data to variable
$name = $data['name'];
$lastname = $data['lastname'];
$username= $data['username'];
$email = $data['email'];
$pwd = $data['pwd'];

// validate data



try { 
    //connect to database
    require_once('dbh.inc.php');

    // insert data into database
    $query = "INSERT INTO users ( name,lastname,username,password,email) VALUES (:name,:lastname,:username,:pwd,:email);";

    //prepare statement 
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":lastname", $lastname);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":pwd", $pwd);

    $stmt->execute();
     // close the connection
     $pdo = null;
     $stmt = null;
     //header("Location:../index.php");
     echo json_encode(['status' => true, 'message'=> 'User registered succesfully']);
     die();

     


} catch (PDOException $e) {
    echo json_encode(['status' => false, 'message'=> 'Error '.$e->getMessage()]);
    die();
    //die("Query  failed: ". $e->getMessage());

}
//exit();


//echo json_encode(['message' => 'this is sign up']);
//echo json_encode($data);


?>