<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP_Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-index">


    <nav>
        <div class="navigation-container">
            <ul class="navigation">
                <li><a href="">Home</a></li>
                <li><a href="">About Us</a></li>
                <li><a href="">Find Blogs</a></li>
                <li><a href="./signup.php">Sign Up</a></li>
                <li><a href="./login.php">Log In</a></li>
                <li><a href="./updateDeleteUsers.php">Update/Delete Users</a></li>
            </ul>
        </div>
    </nav>

    <h3>Welcome to website</h3>
    <h3>Comments</h3>
    <form action="./searchComments.php" method="post">
        <input type="text" name="username" id="username" placeholder="username">
        <button type="submit">Search</button>
    </form>
    
</body>
</html>