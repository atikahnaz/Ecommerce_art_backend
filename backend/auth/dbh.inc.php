<?php

// $dsn = "mysql:host=localhost;dbname=login_system";
// $dbusername = "root";
// $dbpassword = "";

$dsn = "mysql:host=sql12.freesqldatabase.com;dbname=sql12727880";
$dbusername = "sql12727880";
$dbpassword = "nUp9stpY6S";

try {
    $pdo = new PDO($dsn,$dbusername,$dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection Failed: ".$e->getMessage();

}