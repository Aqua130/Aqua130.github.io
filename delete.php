<?php
// Starts the session
session_start();

// Checks if the user is logged in and redirects them to the login page if false
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit(); // Prevents rest of script from running
}

$servername = "localhost";
$username = "17227";
$password = "BlueHorse98";
$dbname = "17227_HartToHart";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save EventID from URL into session if provided
if (isset($_GET['EventID'])) {
    $_SESSION['EventID'] = $_GET['EventID'];
}

$UserID = $_SESSION['UserID'];
$EventID = $_SESSION['EventID'];

// Check if the form is submitted 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $EventID = $_POST['EventID'];
$sql = "DELETE FROM Events WHERE EventID = $EventID AND UserID = $UserID";
if ($conn->query($sql) === TRUE) {
    echo "Deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}

} 
 
$conn->close(); 
?> 
