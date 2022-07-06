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
    <link rel="stylesheet" type="text/css" href="assets/styles/guessSongStyleSheet.css">
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

        <?php

            if (isset($_SESSION['gameTrackName'])) {

                $trackName = $_SESSION['gameTrackName'];
            } else {

                $selectedAlbums = $_SESSION['selectedAlbums'];
                $selectedPlaylists = $_SESSION['selectedPlaylists'];

                $wholeTrackList = [];
                foreach($selectedAlbums as $sa) {
                    foreach($sa[1] as $trackName) {
                        array_push($wholeTrackList, $trackName);
                    }
                }
                foreach($selectedPlaylists as $sp) {
                    foreach($sp[1] as $track) {
                        array_push($wholeTrackList, $track[0]);
                    }
                }

                $randTrackIndex = array_rand($wholeTrackList);
                $randTrack = $wholeTrackList[$randTrackIndex];


                // $randPlaylist = ["",[]];

                // while (count($randPlaylist[1]) < 2) {
                //     $randPlaylistIndex = array_rand($selectedPlaylists, 1);
                //     $randPlaylist = $selectedPlaylists[$randPlaylistIndex];
                // }
    
    
                // $randPlaylist[1] = array_slice($randPlaylist[1], 1);
                // $randTrackIndex = array_rand($randPlaylist[1]);
                // $randTrack = $randPlaylist[1][$randTrackIndex];


                $randTempTrackName = str_split($randTrack);
    
                $trackName = "";
                foreach($randTempTrackName as $letter) {
                    if ($letter == "(") {
                        break;
                    } else {
                        $trackName = $trackName . $letter;
                        $_SESSION['gameTrackName'] = $trackName;
                    }
                }

                
            }

            $gameText = "";
            for ($a=0; $a<strlen($trackName); $a++) {
                if ($a==0) {
                    $gameText = $gameText . $trackName[$a];
                } else if ($trackName[$a] == " ") {
                    $gameText = $gameText . " ";

                } else if ($trackName[$a - 1] == " ") {
                    $gameText = $gameText . $trackName[$a];

                } else if ($a % 3 == 0) {
                    $gameText = $gameText . $trackName[$a];
                } else {
                    $gameText = $gameText . "-";
                }
            }
            
            if (isset($_SESSION['currentStreak'])) {
                $currentStreak = $_SESSION['currentStreak'];
            } else {
                $currentStreak = 0;
            }

        ?>

    <form method="post" id="endGame">
        <input type="hidden" name="gameEnd">
    </form>

    <form method="post" id="gameWon">
        <input type="hidden" name="gameWon">
    </form>

    <div class="content">

        <div class="gameObjects">
            <div class="gameWord">
                <?php
                    if (isset($_SESSION['gameEnd'])) {
                        echo $trackName;
                    } else {
                        echo $gameText;
                    }

                ?>

            </div>

            
            <?php
                if (isset($_SESSION['gameEnd'])) {
                    
                } else {
                    ?>
                    <input class="guessBox" type="text" id="guess" onkeyup="checkInput()" autofocus="autofocus" onfocus="this.select()">
                    <button class  onclick="checkInput()" style="display: none;">
                        Check
                    </button>

                    <?php
                }
                
                ?>


            <?php

                if (isset($_SESSION['gameEnd'])) {
                    ?>
                        <h2>Unlucky!</h2>
                        <form method="post">
                            <input type="submit" name="newGame" value="New Game" class="new">
                        </form>
                        <h2>Your Streak Was : <?=$_SESSION['streakWas']?></h2>

                    <?php
                }




            ?>



        </div>
            
        <div class="gameInfo">
            <div class="timer" id="demo">10</div>
            <br><br><br>
            <div class="streakText">
                Current Streak : <?=$currentStreak?>
            </div>
            
        </div>
    </div>

    <script>

        function checkInput() {
            var guess = document.getElementById("guess").value;
            guess = guess.toLowerCase();
            if (guess=="<?php echo strtolower($trackName);?>") {
                document.getElementById("gameWon").submit();
            }
            if ("<?=substr($trackName, -1)?>" == " ") {
                if (guess=="<?php echo strtolower(substr($trackName, 0, -1));?>") {
                    document.getElementById("gameWon").submit();
                }
            }
        }

    </script>


            

    <?php

        if (isset($_POST['gameWon'])) {
            $_SESSION['currentStreak'] = $currentStreak + 1;
            unset($_POST['gameWon']);
            unset($_SESSION['gameTrackName']);

            echo "<meta http-equiv='refresh' content='0'>";
        }




        if (isset($_POST['gameEnd'])) {
            $_SESSION['gameEnd'] = true;
            $_SESSION['streakWas'] = $_SESSION['currentStreak'];
            $_SESSION['currentStreak'] = 0;
            unset($_POST['gameEnd']);
            echo "<meta http-equiv='refresh' content='0'>";
        }

        if (isset($_SESSION['gameEnd'])) {
            

        } else {
            ?>
            <script>
                var seconds = 10;
                gameTime = setInterval(myTimer, 1000);
                endGame = setTimeout(endGame, 10000);

                function myTimer() {
                seconds -= 1;
                document.getElementById("demo").innerHTML = seconds;
                }

                function endGame() {
                    clearInterval(gameTime);
                    document.getElementById("endGame").submit();
                }

            </script>
            <?php
        }


        if (isset($_POST['newGame'])) {
            unset($_POST['newGame']);
            unset($_SESSION['gameTrackName']);
            unset($_SESSION['gameEnd']);
            

            echo "<meta http-equiv='refresh' content='0'>";
        }
    ?>





    <script src="assets/scripts/global.js"></script>
</body>
</html>