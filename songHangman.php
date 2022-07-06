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
    <link rel="stylesheet" type="text/css" href="assets/styles/hangmanStyleSheet.css">
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

            if (isset($_SESSION['currentGameState'])) {
                $currentGameState = $_SESSION['currentGameState'];
            } else {
                $currentGameState = "";
                foreach(str_split($trackName) as $letter) {
                    if ($letter == " ") {
                        $currentGameState = $currentGameState . " ";
                    } else {
                        $currentGameState = $currentGameState . "-";
                    }
                }

                // $currentGameState = substr($currentGameState, 0, -1);
            }

            if (isset($_SESSION['incorrectGuesses'])) {
                $incorrectGuesses = $_SESSION['incorrectGuesses'];
            } else {
                $incorrectGuesses = 0;
            }

            if (isset($_SESSION['guesses'])) {
                $guesses = $_SESSION['guesses'];
            } else {
                $guesses = [];
            }

        ?>
    <div class="content">

        <div class="gameObjects">
            
            <div class="gameState">
                <?php
                    if ($incorrectGuesses < 9 ) {
                        if ($currentGameState == $trackName) {
                            echo "<p style='color: lime;'>" . $currentGameState . "</p>"; 
                        } else {
                            echo "<p>" . $currentGameState . "</p>"; 
                        }
                    } else {
                        echo "<p style='color: red;'>" . $trackName . "</p>";
                    }
                ?>
            </div>
        
            <div class="hangmanStages">
                <?php
                    if ($incorrectGuesses == 0) {
                        echo '<img id="stage1" src="assets/images/hangman/hangman_stage1.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 1) {
                        echo '<img id="stage2" src="assets/images/hangman/hangman_stage2.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 2) {
                        echo '<img id="stage3" src="assets/images/hangman/hangman_stage3.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 3) {
                        echo '<img id="stage4" src="assets/images/hangman/hangman_stage4.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 4) {
                        echo '<img id="stage5" src="assets/images/hangman/hangman_stage5.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 5) {
                        echo '<img id="stage6" src="assets/images/hangman/hangman_stage6.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 6) {
                        echo '<img id="stage7" src="assets/images/hangman/hangman_stage7.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 7) {
                        echo '<img id="stage8" src="assets/images/hangman/hangman_stage8.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 8) {
                        echo '<img id="stage9" src="assets/images/hangman/hangman_stage9.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    } else if ($incorrectGuesses == 9) {
                        echo '<img id="stage10" src="assets/images/hangman/hangman_stage10.png" style="width: 100%; max-height: 20em; object-fit: contain;">';
        
                    }
        
                ?>
            </div>

            <?php
                if ($incorrectGuesses < 9 AND $currentGameState != $trackName) {
                    
            ?>
                <div class="submitLetter">
                
                    <form method="post">
                        <input type="text" name="guess" maxlength="1" autofocus="autofocus" onfocus="this.select()" required>
                    </form>
                </div>

            <?php
                }
            ?>
    
    
            <?php
    
            if ($currentGameState == $trackName) {
                echo "<h2>You Win!</h2>";
    
                ?>
                    <form method="post">
                        <input type="submit" name="newGame" value="New Game" class="new">
                    </form>
    
    
                <?php
    
            }

            if ($incorrectGuesses > 8) {
                echo "<h2>Unlucky!</h2>";
                ?>
                    <form method="post">
                        <input type="submit" name="newGame" value="New Game" class="new">
                    </form>

                <?php
            }
    
            ?>
        </div>
        
        <div class="guesses">
            <div class="guessText">

                Incorrect guesses: <?=$incorrectGuesses?>
                <br> <br>
                <?php
                    foreach($guesses as $g) {
                        echo $g . "<br>";
                    }
                ?>
            </div>
        </div>
    </div>




            

    <?php



        if (isset($_POST['guess']) && !in_array($_POST['guess'], $guesses)) {
            $guess = $_POST['guess'];
            $correct = false;

            for ($a=0; $a<strlen($trackName); $a++) {
                if (strtoupper($guess) == strtoupper($trackName[$a])) {
                    $correct = true;
                    $currentGameStateList = str_split($currentGameState);
                    $currentGameState = "";

                    for ($i=0; $i<count($currentGameStateList); $i++) {
                        if ($a==$i) {
                            $currentGameState = $currentGameState . str_split($trackName)[$i];
                        } else {
                            $currentGameState = $currentGameState . $currentGameStateList[$i];
                        }

                        $_SESSION['currentGameState'] = $currentGameState;
                        echo "<meta http-equiv='refresh' content='0'>";

                    }
                }
            }

            if ($correct) {

            } else {
                $incorrectGuesses += 1;
                $_SESSION['incorrectGuesses'] = $incorrectGuesses;

                ?>

                <script>
                    var x = document.getElementById("stage<?=$incorrectGuesses?>");
                    x.classList.add("hide");

                    var y = document.getElementById("stage<?=$incorrectGuesses+1?>")
                    y.classList.remove("hide");
                
                </script>

                <?php

                echo "<meta http-equiv='refresh' content='0'>";
            }

            array_push($guesses, $guess);
            $_SESSION['guesses'] = $guesses;
            

        }
        // unset($_SESSION['currentGameState']);
        // unset($_SESSION['gameTrackName']);


        if (isset($_POST['newGame'])) {
            unset($_SESSION['currentGameState']);
            unset($_SESSION['gameTrackName']);
            unset($_SESSION['newGame']);
            unset($_SESSION['incorrectGuesses']);
            unset($_SESSION['guesses']);

            echo "<meta http-equiv='refresh' content='0'>";
        }
    ?>





    <script src="assets/scripts/global.js"></script>
</body>
</html>
