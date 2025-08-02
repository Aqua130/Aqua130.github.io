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

$sqlEvent = "SELECT EventName, Description, SongNumber, Price, Date, ExplicitFilter FROM Events WHERE UserID = $UserID AND EventID = $EventID";
$stmtEvent = $conn->prepare($sqlEvent);

if (!$stmtEvent) {
    die("Prepare failed: " . $conn->error);
}

$stmtEvent->execute();

$resultEvent = $stmtEvent->get_result();
if (!$resultEvent) {
    die("Query failed: " . $conn->error);
}

$eventData = $resultEvent->fetch_assoc();

$sqlSong = "SELECT SongID, EventID, SongName, Artist, Explicit, ArtworkURL, PreviewURL FROM Songs WHERE EventID = $EventID";
$stmtSong = $conn->prepare($sqlSong);

if (!$stmtSong) {
    die("Prepare failed: " . $conn->error);
}

$stmtSong->execute();

$resultSong = $stmtSong->get_result();
if (!$resultSong) {
    die("Query failed: " . $conn->error);
}

if ($resultSong->num_rows === 0) {
    $songsEmpty = true;
} else {
    $songsEmpty = false;
    $songs = $resultSong->fetch_all(MYSQLI_ASSOC); // Fetch all songs into array
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteSong'])) {
    $EventID = $_POST['EventID'];
    $SongID = $_POST['SongID'];
    $sql = "DELETE FROM Songs WHERE EventID = $EventID AND SongID = $SongID";
    if ($conn->query($sql) === TRUE) {
        echo "Deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    
    } 
     
    $conn->close();

?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Event Details</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="stylesheet2.css">

    </head>

    <body class="eventDetails">
        <div class="navbar">
            <ul>
                <a href="dashboard.php"><img src="Hart to Hart Intro Logo.png" alt="Hart to Hart Logo" id="Logo"></a>
                <li><a href="logout.php">Log Out</a></li>
                <li><a href="contactSupport.php">Contact Us</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
            </ul>
        </div>
        <div class="container" id="pageNav">
            <div class="container" id="EventDetailsContainer">
                <h1><?php echo htmlspecialchars($eventData['EventName']); ?></h1>
                <h2><?php echo htmlspecialchars($eventData['Description']); ?></h2>
                <div class="container" id="eventDetails">
                    <p>Song Number: <?php echo htmlspecialchars($eventData['SongNumber']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($eventData['Price']); ?></p>
                    <p>Date: <?php echo htmlspecialchars($eventData['Date']); ?></p>
                    <p>Explicit Filter: <?php echo htmlspecialchars($eventData['ExplicitFilter']); ?></p>
                </div>
            </div>
                <div class="container" id="detailsButtons">
                        <form action="delete.php" method="POST"> 
                            <input type="hidden" name="EventID" value="<?php echo htmlspecialchars($EventID); ?>">
                            <button type="submit" id="deleteEventButton" onclick="return confirm('Are you sure you want to delete this item?');">Delete Event</button> 
                        </form> 
                    <a href="createEvent.php?EventID=<?php echo urlencode($EventID); ?>" style="text-decoration: none;">
                        <button type="button" id="addSongsButton">Add Songs</button>
                    </a>
                    <a href="[Link for deleting songs]" style="text-decoration: none;">
                        <button type="button" id="deleteSongsButton">Delete Songs</button>
                    </a>
                </div>
                <div class="container" id="selectedSongs">
                    <h1>Selected Songs</h1>
                    <div class="container" id="selectedSongsContent">
                        <?php if ($songsEmpty): ?>
                            <p style="font-size: 1.2em; text-align: center; margin-top: 2vh;">
                                You currently haven't added any songs, use the buttons above to add them!
                            </p>
                        <?php else: ?>
                            <?php foreach ($songs as $songData): ?>
                                <div class="container" id="selectedSongsSeparate">
                                    <form method="POST" class="container" style="border: none">
                                        <div style="margin-right: 0.5vw; width: 5vw; height: 10vh; margin-bottom: 0; border-right: solid black;">
                                            <img src="<?php echo htmlspecialchars($songData['ArtworkURL'] ?? 'default_art.png'); ?>" alt="Album Art" style="width: inherit; height: inherit;">
                                        </div>
                                        <div class="container" id="resultDetails">
                                            <h1><?php echo htmlspecialchars($songData['SongName']); ?> - </h1>
                                            <p><?php echo htmlspecialchars($songData['Artist']); ?> | </p>
                                            <p><?php echo htmlspecialchars($songData['Explicit']); ?></p>
                                            <div class="container" id="previewDetails">
                                                <?php if (!empty($songData['PreviewURL'])): ?>
                                                <audio controls id="audioPreview">
                                                    <source src="<?php echo htmlspecialchars($songData['PreviewURL']); ?>" type="audio/mp3">
                                                    Your browser does not support the audio element.
                                                </audio>
                                                <?php else: ?>
                                                <p>No preview available</p>
                                                <?php endif; ?>
                                            </div>
                                            <input type="hidden" name="EventID" value="<?php echo htmlspecialchars($songData['EventID']); ?>">
                                            <input type="hidden" name="SongID" value="<?php echo htmlspecialchars($songData['SongID']); ?>">
                                            <button type="submit" name="deleteSong" id="deleteButton" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <img src="delete.png" id="deleteImage">
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
