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
} else {
    
}

// Checks if the user is logged in and redirects them to the log in page if false
if (!isset($_SESSION['UserID'])){
    header("Location: login.php");
    exit;
}

// Save EventID from URL into session if provided
if (isset($_GET['EventID'])) {
    $_SESSION['EventID'] = $_GET['EventID'];
}


// Inserts the details from the Event Details Form into the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eventDetailsButton'])) {
    $EventName = $_POST['EventName'];
    $Description = $_POST['Description'];
    $Date = $_POST['Date'];
    $ExplicitFilter = isset($_POST['ExplicitFilter']) ? 1 : 0;
    $UserID = $_SESSION['UserID'];
    
    $sql = "INSERT INTO Events (EventName, Description, Date, ExplicitFilter, UserID) 
            VALUES ('$EventName','$Description','$Date', $ExplicitFilter, $UserID)";
    
    if ($conn->query($sql) === TRUE) {
        $EventID = $conn->insert_id;
        // Store the explicit filter value in session for song filtering
        $_SESSION['ExplicitFilter'] = $ExplicitFilter;

        // Redirect to createEvent.php with the event ID (optional)
        header("Location: createEvent.php?EventID=" . $EventID);
        exit;
    } else {
    }
}


$searchString = isset($_GET['search']) ? $_GET['search'] : '';

$tracks = [];

if (!empty($searchString)) {
    // Use iTunes Search API instead of Spotify
    $search_url = "https://itunes.apple.com/search?term=" . urlencode($searchString) . "&entity=song&limit=5";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $search_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $search_response = json_decode(curl_exec($ch), true);
    curl_close($ch);

if (isset($search_response['results'])) {
// Use the explicit filter from the session, default to 0 (off)
$explicit_toggle_on = isset($_SESSION['ExplicitFilter']) && $_SESSION['ExplicitFilter'] == 1;

foreach ($search_response['results'] as $track) {
    $explicitness = $track['trackExplicitness']; // 'explicit', 'cleaned', or 'notExplicit'

    if (!$explicit_toggle_on && $explicitness === 'explicit') {
        continue; // Skip explicit songs if the toggle is OFF
    }

    $tracks[] = $track;
}

}
// Adds a song to the Songs table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addSong'])) {
    $trackName = $conn->real_escape_string($_POST['trackName']);
    $artistName = $conn->real_escape_string($_POST['artistName']);
    $explicitness = $conn->real_escape_string($_POST['explicitness']);
    $artworkUrl100 = $conn->real_escape_string($_POST['artworkUrl100']);
    $previewUrl = $conn->real_escape_string($_POST['previewUrl']);
    $EventID = $_SESSION['EventID'];

    // Insert song into Songs table
    $sql = "INSERT INTO Songs (SongName, Artist, Explicit, ArtworkURL, PreviewURL, EventID) 
            VALUES ('$trackName','$artistName','$explicitness', '$artworkUrl100', '$previewUrl', '$EventID')";

    if ($conn->query($sql) === TRUE) {
        // Count number of songs for the event
        $countSql = "SELECT COUNT(*) as songCount FROM Songs WHERE EventID = $EventID";
        $result = $conn->query($countSql);
        $row = $result->fetch_assoc();
        $songCount = $row['songCount'];

        // Calculate Price from Song Count
        $Price = $songCount * 1; // $1 per song
        // Update the Events table with new song count
        $updateSql = "UPDATE Events SET SongNumber = $songCount, Price = $Price WHERE EventID = $EventID";
        $conn->query($updateSql);

    } else {
    }
}



}
?>


<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hart to Hart</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Students/17227/IA3/stylesheet2.css">

</head>

<body class="createEvent">
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
        <div class="container" id="createEventContainer">
            <img src="createEventLogo.png" id="CreateEventLogo">
            <div class="container" id="createEventForm">
                
                <form action="createEvent.php" method="POST">
                    <input type="text" name="EventName" required placeholder="Name of Event">
                    <input type="text" name="Description" placeholder="Description">
                    <div class="container" id="dateExplicit">
                        <input type="date" name="Date" required placeholder="DD/MM/YYYY">
                        <label for="toggleSwitch" id="explicit">Explicit?</label>
                        <label class="switch"><input type="checkbox" name="ExplicitFilter" required><span class="slider round"></span></label>
                    </div>
                    <div class="container" id="songSearchLinkContainer">
                        <a href="#songSearch" style="text-decoration: none; width: max-content; display: grid;">
                            <button type="submit" name="eventDetailsButton" id="songSearchLink"><span>Step 2: Select Songs</span></button>
                        </a>
                    </div>
                </form>
            </div>
            <div class="container" id="songSearch">
                <form class="container" id="searchrow" method="GET" action="">
                    <h1>Search Songs</h1>
                    <input type="search" name="search" id="search" placeholder="Search for a song, artist, or album" value="<?php echo htmlspecialchars($searchString); ?>">
                    <button id="hiddenButton" type="submit" style="visibility:hidden;"></button>
                </form>



                <div class="container" id="searchResultsFull">

                    <?php foreach ($tracks as $track): 
                        $trackName = $track['trackName'];
                        $artistName = $track['artistName'];
                        $explicitness = $track['explicitness'];
                        $collectionName = $track['collectionName'];
                        $artworkUrl100 = $track['artworkUrl100'];
                        $previewUrl = $track['previewUrl'];
                    ?>
                    <div class="container" id="searchResultsSeparate">
                        <div style="margin-right: 0.5vw; width: 5vw; height: 10vh; margin-bottom: 0; border-right: solid black;">
                            <img src="<?php echo $artworkUrl100; ?>" alt="Album Art" style="width: inherit; height: inherit;">
                        </div>
                        <div class="container" id="resultr">
                            <h1><?php echo htmlspecialchars($trackName); ?> - </h1>
                            <p><?php echo htmlspecialchars($artistName); ?> | </p>
                            <p><?php echo htmlspecialchars($collectionName); ?></p>
                        </div>
                        <div class="container" id="preview">
                            <?php if ($previewUrl): ?>
                            <audio controls id="audioPreview">
                                <source src="<?php echo htmlspecialchars($previewUrl); ?>" type="audio/mp3">
                                Your browser does not support the audio element.
                            </audio>
                            <?php else: ?>
                            <p>No preview available</p>
                            <?php endif; ?>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="trackName" value="<?php echo htmlspecialchars($trackName); ?>">
                            <input type="hidden" name="artistName" value="<?php echo htmlspecialchars($artistName); ?>">
                            <input type="hidden" name="explicitness" value="<?php echo htmlspecialchars($explicitness); ?>">
                            <input type="hidden" name="artworkUrl100" value="<?php echo htmlspecialchars($artworkUrl100); ?>">
                            <input type="hidden" name="previewUrl" value="<?php echo htmlspecialchars($previewUrl); ?>">
                            <div class="container" id="addButtonCont">
                                <button type="submit" name="addSong" id="addButton"><img src="add.png" id="addImage"></button>
                            </div>
                        </form>
                    </div>
                    <?php endforeach; ?>
                    <div class="container" id="finalButtonContainer"><a href="dashboard.php" id="finalLink"><button type="submit" name="finalButton" id="finalButton">Finalise Event</button></a></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
