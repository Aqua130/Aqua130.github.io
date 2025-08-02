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

$sql = "SELECT EventName, Description, SongNumber, Price, Date, EventID FROM Events WHERE UserID = $UserID";
$stmt = $conn->prepare($sql);


if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->execute();

$result = $stmt->get_result();
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>


<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Hart to Hart</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="stylesheet2.css">

        <style>

        </style>
    </head>

    <body class="dashboard">
        <div class="navbar">
            <ul>
                <a href="dashboard.php"><img src="Hart to Hart Intro Logo.png" alt="Hart to Hart Logo" id="Logo"></a>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="contactSupport.php">Contact Us</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a class="active" href="dashboard.php">Dashboard</a></li>
            </ul>
        </div>
        <div class="container" id="dashboardContainer">
            <img src="HartHubLogo.png" id="HartHubLogo">
            <p>Everything you need right at your fingertips...</p>
            <div class="container" id="dashboardContent">
                <div class="container" id="currentEvents">
                    <h1>Current Events</h1>
                    <!-- PHP to run and display each row that meets the conditions (UserID = ?) -->
                        <div class="container" id="events">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <a id="eventDetailsLink" class="list-group-item list-group-item-action" href="eventDetails.php?EventID=<?php echo urlencode($row['EventID']); ?>">
                                <p style="font-size: 3vw; color: black; font-weight: bold; margin: 3vh 0 0 0;"><?php echo htmlspecialchars($row['EventName']); ?></p>
                                <p style="font-size: 1.5vw; color: black; margin: 1vh 0 0 0;"><?php echo htmlspecialchars($row['Description']); ?></p>
                                <p style="font-size: 1.5vw; color: black; margin: 1vh 0 0 0;">Song Number: <?php echo htmlspecialchars($row['SongNumber']); ?></p>
                                <p style="font-size: 1.5vw; color: black; margin: 1vh 0 0 0;">Price: $<?php echo htmlspecialchars($row['Price']); ?></p>
                                <p style="font-size: 1.5vw; color: black; margin: 1vh 0 0 0;">Date: <?php echo htmlspecialchars($row['Date']); ?></p>
                            </a>
                        <?php endwhile; ?>
                        </div>
                </div>
                <div class="container" id="restOfThePageType">
                    <a href="createEvent.php"><button>Create a New Event</button></a>
                    <a href="eventDetails.php"><button>Edit Event Details</button></a>
                    <div class="container" id="skib">
                        <button>Change Payment Method</button>
                        <a href="account.php"><button>Account Settings</button></a>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
