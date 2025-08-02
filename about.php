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
        
    </head>

    <body class="about">
        <div class="navbar">
            <ul>
                <a href="dashboard.html"><img src="Hart to Hart Intro Logo.png" alt="Hart to Hart Logo" id="Logo"></a>
                <li><a href="logout.html">Log Out</a></li>
                <li><a href="contactSupport.html">Contact Us</a></li>
                <li><a class="active" href="about.html">About Us</a></li>
                <li><a href="account.html">Account</a></li>
                <li><a href="dashboard.html">Dashboard</a></li>
            </ul>
        </div>
        <div class="container" id="aboutcontent">
            <img src="audiovisual.png" alt="A music player playing Heart to Heart by Mac Demarco" id="audiovisual">
            <button id="audioButton" aria-label="Play audio"></button>
            <audio id="audioPlayer" src="musicc.mp3"></audio>
            <div class="about" id="abouttext">
                <h1>About Us</h1>
                <p>We are a family-owned, event planning business dedicated to assist you in the planning of your events. <br> Our music application delivers a variety of online, secure and personalised playlist creation services straight to you. <br> Access a wide range of song choices for low prices through us here, at Hart to Hart. </p>
            </div>
        </div>

        <script>
            const button = document.getElementById('audioButton');
            const audio = document.getElementById('audioPlayer');

            button.addEventListener('click', () => {
                if (audio.paused) {
                    audio.play();
                } else {
                    audio.pause();
                }
            });
        </script>

    </body>
</html>
