<?php require_once(__DIR__ . "/DBFunctions.php"); ?>

<?php
    session_start();

    if (empty($_SESSION['username'])) {
	    header("location: login.php");
        die;
    }

    if (isset($_SESSION['background'])) {
        $background = $_SESSION['background'];
    } else {
        $background = "assets/images/desert.jpg";
    }

    $username = $_SESSION['username'];
    $profilePicture = $_SESSION['profilePicture'];

    $numberOfAlbums = count($_SESSION['albums']);
    $numberOfArtists = count($_SESSION['artists']);
    $numberOfPlaylists = count($_SESSION['playlists']);

    function changeBackground() {
        $background = $_SESSION['background'];
        $username = $_SESSION['username'];
        $sql = "UPDATE users
                SET background = :background
                WHERE username = :username";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'background' => $background
        ]);
    }

    function changeProfilePicture() {
        $profilePicture = $_SESSION['profilePicture'];
        $username = $_SESSION['username'];
        $sql = "UPDATE users
                SET profilePicture = :profilePicture
                WHERE username = :username";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'profilePicture' => $profilePicture
        ]);
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="assets/styles/myStyles.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/myAccountStyleSheet.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MUZE# - My Account</title>

    <style>
        <?php
            if (isset($_SESSION["background"])) {
                
                switch ($_SESSION['background']) {
                    case "assets/images/mountain.jpg":
                        echo "button[name='mountain'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;
                
                    case "assets/images/desert.jpg":
                        echo "button[name='desert'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;
                    
                    case "assets/images/forest.jpg":
                        echo "button[name='forest'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/ocean.jpg":
                        echo "button[name='ocean'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/aurora.jpg":
                        echo "button[name='aurora'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/city.jpg":
                        echo "button[name='city'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/stars.jpg":
                        echo "button[name='stars'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/sunset.jpg":
                        echo "button[name='sunset'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;
                }
            } 
            
            if (isset($_SESSION["profilePicture"])) {
                
                switch ($_SESSION['profilePicture']) {
                    case "assets/images/blackwhite.jpg":
                        echo "button[name='mountain1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;
                
                    case "assets/images/redblue.jpg":
                        echo "button[name='desert1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;
                    
                    case "assets/images/purplecyan.jpg":
                        echo "button[name='forest1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/pinkred.jpg":
                        echo "button[name='ocean1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/redyellow.jpg":
                        echo "button[name='aurora1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/bluewhite.jpg":
                        echo "button[name='city1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/greenwhite.jpg":
                        echo "button[name='stars1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;

                    case "assets/images/redblack.jpg":
                        echo "button[name='sunset1'] {background-color: rgba(10, 10, 10, 0.7);}";
                        break;
                }
            } 
        ?>
        
    </style>

    <script>

        document.addEventListener("DOMContentLoaded", function (event) {
            var scrollPosition = localStorage.getItem("scrollPosition");
            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
            }
        });

        window.onscroll = function (e) {
            localStorage.setItem("scrollPosition", window.scrollY);
        };

    </script>

</head>
<body style="background-image: url(<?php echo $background ?>);">
    <div class="topnav">
        <a href="home.php">HOME</a>
        <a href="discover.php">DISCOVER</a>
        <a href="chat.php">CHAT</a>
        <a href="games.php">GAMES</a>
        <a class = "active" style="float: right;" href="myAccount.php">MY ACCOUNT</a>
        <a style="float: right;" href="myMusic.php">MY MUSIC</a>
    </div>

    <?php

        if (isset($_POST['logout'])) {

            session_destroy();
            header("location: login.php");
        }        
    ?>

    <div class="accountHeading">
        <h1>Account Information</h1>
    </div>

    <div class="informationContainer">
        <div class="profileBox">

            <div class="profileContainer">
    
                <img src="<?=$profilePicture?>" alt="">
    
            </div>

            <div class="profileName"><?=$username?></div>
            
        </div>

        <div class="statsBox">

            <div class="statsContainer">
                <div class="stat">Albums Saved : <?=$numberOfAlbums?></div>
                <div class="stat">Artists Saved : <?=$numberOfArtists?></div>
                <div class="stat">Playlists Created : <?=$numberOfPlaylists?></div>

            </div>

        </div>
    </div>

    <div class="accountHeading">
        <h1>Friends</h1>
    </div>

    <div style="text-align: center;">
        <button style="width: 10em;" name="Add Friend" type="button" onclick="location.href = 'friend.php';">Add Friend</button>
    </div>

    <form method="post">

        <div class="accountHeading">
            <h1>Profile Picture</h1>
        </div>

        <div class="profileButtonContainer">


            <button type="submit" name="desert1" class="profileButton">
                <img class="profilePreview" src="assets/images/redblue.jpg"> <br>
                <div class="profileImageLabel">Image 1</div>
            </button>
    
            <button type="submit" name="ocean1" class="profileButton">
                <img class="profilePreview" src="assets/images/pinkred.jpg"> <br>
                <div class="profileImageLabel">Image 2</div>
            </button>
    
            <button type="submit" name="mountain1" class="profileButton">
                <img class="profilePreview" src="assets/images/blackwhite.jpg"> <br>
                <div class="profileImageLabel">Image 3</div>
            </button>
            
            <button type="submit" name="forest1" class="profileButton">
                <img class="profilePreview" src="assets/images/purplecyan.jpg"> <br>
                <div class="profileImageLabel">Image 4</div>
            </button>

            <button type="submit" name="aurora1" class="profileButton">
                <img class="profilePreview" src="assets/images/redyellow.jpg"> <br>
                <div class="profileImageLabel">Image 5</div>
            </button>
    
            <button type="submit" name="city1" class="profileButton">
                <img class="profilePreview" src="assets/images/bluewhite.jpg"> <br>
                <div class="profileImageLabel">Image 6</div>
            </button>
    
            <button type="submit" name="stars1" class="profileButton">
                <img class="profilePreview" src="assets/images/greenwhite.jpg"> <br>
                <div class="profileImageLabel">Image 7</div>
            </button>

            <button type="submit" name="sunset1" class="profileButton">
                <img class="profilePreview" src="assets/images/redblack.jpg"> <br>
                <div class="profileImageLabel">Image 8</div>
            </button>

        </div>
    </form>

    <?php
        if (isset($_POST['mountain1'])) {
            $_SESSION['profilePicture'] = "assets/images/blackwhite.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();
            
        } else if (isset($_POST['desert1'])) {
            $_SESSION['profilePicture'] = "assets/images/redblue.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();

        } else if (isset($_POST['ocean1'])) {
            $_SESSION['profilePicture'] = "assets/images/pinkred.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();

        } else if (isset($_POST['city1'])) {
            $_SESSION['profilePicture'] = "assets/images/bluewhite.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();

        } else if (isset($_POST['forest1'])) {
            $_SESSION['profilePicture'] = "assets/images/purplecyan.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();

        } else if (isset($_POST['aurora1'])) {
            $_SESSION['profilePicture'] = "assets/images/redyellow.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();

        } else if (isset($_POST['stars1'])) {
            $_SESSION['profilePicture'] = "assets/images/greenwhite.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();

        } else if (isset($_POST['sunset1'])) {
            $_SESSION['profilePicture'] = "assets/images/redblack.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeProfilePicture();
        }
        
    ?>


    <form method="post">

        <div class="accountHeading">
            <h1>Background Image</h1>      
        </div> <br>

        <div class="buttonContainer">

            <button type="submit" name="desert" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/desert.jpg"> <br>
                <div class="imageLabel">Desert</div>
            </button>
    
            <button type="submit" name="ocean" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/ocean.jpg"> <br>
                <div class="imageLabel">Ocean</div>
            </button>
    
            <button type="submit" name="mountain" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/mountain.jpg"> <br>
                <div class="imageLabel">Mountains</div>
            </button>
            
            <button type="submit" name="forest" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/forest.jpg"> <br>
                <div class="imageLabel">Forest</div>
            </button>

            <button type="submit" name="aurora" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/aurora.jpg"> <br>
                <div class="imageLabel">Northern Lights</div>
            </button>
    
            <button type="submit" name="city" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/city.jpg"> <br>
                <div class="imageLabel">City</div>
            </button>
    
            <button type="submit" name="stars" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/stars.jpg"> <br>
                <div class="imageLabel">Starry Sky</div>
            </button>

            <button type="submit" name="sunset" class="backgroundButton">
                <img class="backgroundPreview" src="assets/images/sunset.jpg"> <br>
                <div class="imageLabel">Sunset</div>
            </button>

        </div>
    </form>

    <?php
        if (isset($_POST['mountain'])) {
            $_SESSION['background'] = "assets/images/mountain.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();
            
        } else if (isset($_POST['desert'])) {
            $_SESSION['background'] = "assets/images/desert.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();

        } else if (isset($_POST['ocean'])) {
            $_SESSION['background'] = "assets/images/ocean.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();

        } else if (isset($_POST['city'])) {
            $_SESSION['background'] = "assets/images/city.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();

        } else if (isset($_POST['forest'])) {
            $_SESSION['background'] = "assets/images/forest.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();

        } else if (isset($_POST['aurora'])) {
            $_SESSION['background'] = "assets/images/aurora.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();

        } else if (isset($_POST['stars'])) {
            $_SESSION['background'] = "assets/images/stars.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();

        } else if (isset($_POST['sunset'])) {
            $_SESSION['background'] = "assets/images/sunset.jpg";
            echo "<meta http-equiv='refresh' content='0'>";
            changeBackground();
        }

    ?>

    <div class="accountHeading">
        <h1>Account Settings</h1>
    </div>

    <div class="settingsWrapper">


    
    </div>

    <form method="post" class="logoutForm">
        <input type="submit" name="logout" value="Log Out" class="logout">
    </form>

    <button id="changePasswordToggle">Change Password</button>

    <form method="post" id="changePassword" class="hide">
        <input type="password" name="oldPassword" placeholder="Old Password" required> <br>
        <input type="password" name="newPassword" placeholder="New Password" required> <br>
        <input type="submit" value="Confirm">
    </form>

    <?php
        if (isset($_POST['oldPassword'])) {
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];
            changePassword($username, $oldPassword, $newPassword);
        }

        if (isset($_POST['oldPassword'])) {

            if (isset($_SESSION['changePasswordError'])) {
                echo "<div class='passwordError'>Incorrect Password</div>";
                unset($_SESSION['changePasswordError']);
                unset($_POST['oldPassword']);
            } else {
                echo "<div class='passwordChange'>Password Changed Successfully</div>";
                unset($_POST['oldPassword']);
            }
        }

        
    ?>

    <script>
  
          const btn = document.getElementById("changePasswordToggle");
          btn.onclick = function () {
              var x = document.getElementById("changePassword");
              if (x.classList.contains("hide")) {
                  x.classList.remove("hide");
              } else {
                  x.classList.add("hide");
              }
          }
    </script>









    <script src="assets/scripts/global.js"></script>
</body>
</html>