<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Account</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="stylesheet2.css">
        
    </head>

    <body class="account">
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

        <div class="container" id="pageNav">

            <div class="container" id="accountContainer"> <!-- Column -->
                <img src="accountLogo.png" alt="Account Logo" id="accountLogo">
                <div class="container" id="accountContent"> <!-- Row -->
                    <div class="container" id="column1"> <!-- Column --> <!-- First Name, Email, Edit Button -->
                        <span>First Name:</span>
                        <div class="container" id="columnOptions">First Name</div>
                        <span>Email:</span>
                        <div class="container" id="columnOptions">Email</div>
                        <button class="button" id="editDetailsButton">Edit</button>
                    </div>
                    <div class="container" id="column2"> <!-- Column --> <!-- Last Name, Mobile -->
                        <span>Last Name:</span>
                        <div class="container" id="columnOptions">Last Name</div>
                        <span>Mobile:</span>
                        <div class="container" id="columnOptions">Mobile</div>
                    </div>
                    <div class="container" id="column3"> <!-- Column --> <!-- Delete Account, Reset Account, Log Out -->
                        <a href="deleteAccountPage"> <!-- Change This After Creating Functions -->
                            <button class="button" id="deleteAccountButton">Delete Account</button>
                        </a>
                        <a href="resetAccountPage"> <!-- Change This After Creating Functions -->
                            <button class="button" id="resetAccountButton">Reset Account</button>
                        </a>
                        <a href="logOutPage"> <!-- Change This After Creating Functions -->
                            <button class="button" id="logOutButton">Log Out</button>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
