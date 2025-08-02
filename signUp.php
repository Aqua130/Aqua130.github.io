<?php
session_start();

$servername = "localhost";
$username = "17227";
$password = "BlueHorse98";
$dbname = "17227_HartToHart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection Successful";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $Email = $_POST['Email'];
    $Mobile = $_POST['Mobile'];
    $HashedPassword = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO Users (FirstName, LastName, Email, Mobile, HashedPassword) VALUES ('$FirstName','$LastName','$Email','$Mobile','$HashedPassword')";    
    if ($conn->query($sql) === TRUE) {
        $UserID = $conn->insert_id;
        header("Location: login.php?UserID=" . $UserID);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    
    </head>
    <body class="signUp">
             
            <!--Container for Logo Image-->
            <div>
                <img id="hartLogo" src="Hart to Hart Intro Logo.png" alt="Hart to Hart Logo">
            </div>

            <div class="container" id="page">
                <!-- Container for Sign Up -->
                <div class="container" id="popUpContainer">
                    <!-- Container for heading and subheading -->
                    <div class="container" id="headerContainer">
                        <div class="container" id="logoClose">
                            <img id="heartLogo" src="heartlogo.png" alt="Heart logo">
                            <a href="index.html" id="closeButton">
                                <img id="closeButton" src="CloseButton.png" alt="Close Menu Button">
                            </a>
                        </div>
                            <h1 class="text-center" style="padding: 0; margin: 0; font-size: 3vh;">Sign Up</h1>
                            <p class="text-center" style="font-size: 2vh;">Already have an account? Log In <a href="login.html">here</a> instead</p>
                    </div>

                    <!-- Form for Log In-->
                    <div class="formskib" id="container-form">
                        <form action="signUp.php" method="POST">
                            <div class="flex-container">
                                <input type="text" name="FirstName" required placeholder="First Name" style="font-size: 1.5vh;">
                                <input type="text" name="LastName" required placeholder="Last Name" style="font-size: 1.5vh;">
                            </div>
                            <div class="flex-container">
                                <input type="text" name="Email" required placeholder="Email" style="font-size: 1.5vh;">
                                <input type="tel" name="Mobile" required placeholder="Mobile" style="font-size: 1.5vh;" pattern="[0-9]{4} [0-9]{3} [0-9]{3}">
                            </div>
                            <input type="password" name="Password" required placeholder="Password" style="font-size: 1.5vh;">
                            <button type="submit" id="submitButton" style="font-size: 1.5vh;">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
    </body>
    
</html>
