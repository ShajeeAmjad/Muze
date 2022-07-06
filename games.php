<?php
    session_start();
    if (isset($_SESSION['background'])) {
        $background = $_SESSION['background'];
    } else {
        $background = "assets/images/desert.jpg";
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="assets/styles/myStyles.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/gamesStyleSheet.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MUZE# - Games</title>
</head>
<body style="background-image: url(<?php echo $background ?>);">
    <div class="topnav">
        <a href="home.php">HOME</a>
        <a href="discover.php">DISCOVER</a>
        
        <?php
            if (isset($_SESSION['username'])) {
                echo '<a href="chat.php">CHAT</a>';
            }
        ?>
        
        <a class = "active" href="games.php">GAMES</a>

        <?php

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo'<a style="float: right;" href="myAccount.php">MY ACCOUNT</a>';
            } else {
                echo'<a style="float: right;" href="login.php">LOGIN</a>';
            }

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo'<a style="float: right;" href="myMusic.php">MY MUSIC</a>';
            }
        ?>
    </div>

    <div class="gamesWrapper">
            
    <form method="post">

            <button class="individualGame" type="submit" name="startGame" value="game1">
                <img src="assets/images/songHangman.png" alt="" style="background-color: rgba(100, 0, 100, 0.8);">
                <div class="gameTitle">
                    Song Hangman
                </div>
            </button>

    </form>

    <form method="post">

            <button class="individualGame" type="submit" name="startGame" value="game2">
                <img src="assets/images/guessTheSong.png" alt="" style="background-color: rgba(0, 160, 160, 0.8);">
                <div class="gameTitle">
                    Guess The Song
                </div>
            </button>

    </form>


            

    </div>


    <?php

        if (isset($_POST['startGame'])) {
            unset($_SESSION['selectedAlbums']);
            unset($_SESSION['selectedPlaylists']);
            $_SESSION['game'] = $_POST['startGame'];
            switch ($_POST['startGame']) {
                case "game1":
                    echo "<meta http-equiv='refresh' content='0;URL=selectTracks.php'>";
                    break;

                case "game2":
                    echo "<meta http-equiv='refresh' content='0;URL=selectTracks.php'>";
                    break;

                case "game3":
                    echo "<meta http-equiv='refresh' content='0;URL=discover.php'>";
                    break;

                case "game4":
                    echo "<meta http-equiv='refresh' content='0;URL=home.php'>";
                    break;

                case "game5":
                    echo "<meta http-equiv='refresh' content='0;URL=home.php'>";
                    break;

                case "game6":
                    echo "<meta http-equiv='refresh' content='0;URL=home.php'>";
                    break;

            }
        }



    ?>







    <script src="assets/scripts/global.js"></script>
</body>
</html>