<?php

// Starts the session
session_start();


$servername = "localhost";
$username = "17227";
$password = "BlueHorse98";
$dbname = "17227_HartToHart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['Email'];
    $PlainPassword = $_POST['Password'];
    
    $sql = "SELECT Email, HashedPassword, UserID FROM Users WHERE Email='$Email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
    // If there are any rows in the database that match the conditions (Email = Email input and HashedPassword = Password input) then log the user in.
        if (password_verify($PlainPassword, $row['HashedPassword'])) {
            $_SESSION['UserID']=$row['UserID'];
            $_SESSION['Email']=$row['Email'];
            header("Location: dashboard.php");
            exit();
        } else {
            header("Location: login.php?error=password_incorrect");
            exit();
        }
    } else {
        header("Location: signUp.php?error=user_does_not_exist");
        exit();
    }
    
}
?>

<!DOCTYPE HTML>
<html>
    <head>  
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="stylesheet2.css">

        <style>

        </style>
    
    </head>
    <body class="login">
            <!--Container for Logo Image-->
            <div>
                <img id="hartLogo" src="Hart to Hart Intro Logo.png" alt="Hart to Hart Logo">
            </div>

            <div class="container" id="page">
                <!-- Container for Log In -->
                <div class="container" id="popUpContainer">
                    <!-- Container for heading and subheading -->
                    <div class="container" id="headerContainer">
                        <div class="container" id="logoClose">
                            <img id="heartLogo" src="heartlogo.png" alt="Heart logo">
                            <a href="index.php" id="closeButton">
                                <img id="closeButton" src="CloseButton.png" alt="Close Menu Button">
                            </a>
                        </div>
                        <h1 class="text-center" style="padding: 0; margin: 0; font-size: 3vh;">Log In With Email And Password</h1>
                        <p class="text-center" style="font-size: 2vh;">Don't have an account? Sign up <a href="signUp.php">here</a> instead</p>
                    </div>
                    
                    <!-- Form for Log In-->
                    <div class="formskib" id="container-form">
                        <form action="login.php" method="POST">
                                <input type="text" name="Email" required placeholder="Email" style="font-size: 1.5vh;">
                                <input type="password" name="Password" required placeholder="Password" style="font-size: 1.5vh;">
                                <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
                                    <a id="forgotPassword" href="#">Forgot Password?</a>
                                </div>
                                <button type="submit" id="submitButton">Login</button>
                        </form>
                    </div>
                </div>
            </div>
    </body>
    
</html>
