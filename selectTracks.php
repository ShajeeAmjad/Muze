<?php
    session_start();
    if (isset($_SESSION['background'])) {
        $background = $_SESSION['background'];
    } else {
        $background = "assets/images/desert.jpg";
    }

    if (isset($_SESSION['selectedAlbums'])) {
        // $_SESSION['selectedAlbums'] = [];
    } else {
        $_SESSION['selectedAlbums'] = [];
    }

    if (isset($_SESSION['selectedPlaylists'])) {

    } else {
        $_SESSION['selectedPlaylists'] = [];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="assets/styles/myStyles.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/myMusicStyleSheet.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/selectStyleSheet.css">
    <!---<link rel="stylesheet" type="text/css" href="assets/styles/discoverStyleSheet.css"--->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MUZE# - My Music</title>

    <script>

        document.addEventListener("DOMContentLoaded", function (event) {
            var scrollPosition = localStorage.getItem("scrollPosition");
            var albumItems = document.getElementById("albums");
            var albumScroll = localStorage.getItem("albumScroll");
            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
            }
            if (albumScroll) {
                albumItems.scrollTo(albumScroll, 0);
            }
        });

        window.onscroll = function (e) {
            localStorage.setItem("scrollPosition", window.scrollY);
        };

        albumItems.onscroll = function (e) {
            localStorage.setItem("albumScroll", albumItems.scrollX);
        };

    </script>

</head>
<body style="background-image: url(<?php echo $background ?>);">

    <div class="topnav">
        <a href="home.php">HOME</a>
        <a href="discover.php">DISCOVER</a>
        <a href="chat.php">CHAT</a>
        <a class = "active" href="games.php">GAMES</a>

        
        <?php

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo'<a style="float: right;" href="myAccount.php">MY ACCOUNT</a>';
            } else {
                echo'<a style="float: right;" href="login.php">LOGIN</a>';
            }
        ?>
        <a style="float: right;" href="myMusic.php">MY MUSIC</a>
        
    </div>

    <div class="musicHeading">
        <div class="emptySpace"></div>

        <div class="heading">
            <b>SELECT TRACKS</b>
        </div>
        <div class="emptySpace"></div>
    </div>
            
    <div class="musicHeading">
        <div class="emptySelectSpace"></div>

        <div class="heading">
            <b>My Albums</b>
        </div>
        <?php
            if ($_SESSION['selectedAlbums'] == $_SESSION['albums']) {
        ?>
        <form method="post">

            <button name="allAlbumsUnselect" class="selectAll">UNSELECT ALL</button>
        </form>

        <?php
            } else {
        ?>
            <form method="post">

            <button name="allAlbums" class="selectAll">SELECT ALL</button>
            </form>

        <?php
            }
        ?>
    </div>

            <div class="content" id="albums">
                <?php
                    if (isset($_SESSION['albums'])) {

                        $albums = $_SESSION['albums'];
                        // print_r($tracks); 
                        // echo "<br> <br>";

                        if (count($albums) > 0) {

                            

                                $itemIndex = 0;
                                foreach($albums as $row) {

                                    $type = "ALBUM";
                                    $selected = false;


                                    foreach($_SESSION['selectedAlbums'] as $sa) {
                                        if ($row==$sa) {
                                            $selected = true;
                                        }
                                    }
                                    
                                    ?>

                                    <form method="post">

                                    <input type="hidden" name="artist" value="<?=$row[2]?>">
                                    <input type="hidden" name="contentType" value="<?=$type?>">
                                    <input type="hidden" name="title" value="<?=$row[0]?>">
                                    <input type="hidden" name="image" value="<?=$row[3]?>">
                                    <!-- <input type="hidden" name="tracklist" value="<?=$row[1]?>"> -->
                                    <input type="hidden" name="index" value="<?=$itemIndex?>">

                                    

                                    <!-- <button id="<?=$itemIndex?>" class="contentItem"> -->
                                    <?php
                                        if ($selected) {
                                            echo '<button type="submit" class="contentItem" name="unselect" style="background-color: rgba(10, 10, 10, 0.6)">';
                                        } else {
                                            echo '<button type="submit" class="contentItem" name="select">';
                                        }
                                    ?>

                                        <div class='secondWrapper'>
                                        <div class="contentItem-image">
                                            <img src="<?php echo $row[3]; ?>" alt="">
                                            
                                        </div>
                                        <div class="contentItem-mainText">
                                            <div class="contentLabel">ALBUM</div>
                                            <div class="title"><b><?php echo $row[0]; ?></b></div>
                                            <?php echo $row[2]; ?>
                                        </div>


                                    </div>
                                    </button>
                                    </form>

                                    <?php

                                    

                                    $itemIndex += 1;
                                }

                        } else {
                            echo "<h2>You Currently Have No Albums Saved</h2>";
                        }
                    } else {
                        echo "<h2>You Currently Have No Albums Saved</h2>";
                    }
                ?>
            </div>


    <div class="musicHeading">
        <div class="emptySelectSpace"></div>
        <div class="heading">
            <b>My Playlists</b>
        </div>
        <?php
            if ($_SESSION['selectedPlaylists'] == $_SESSION['playlists']) {
        ?>
        <form method="post">

            <button name="allPlaylistsUnselect" class="selectAll">UNSELECT ALL</button>
        </form>

        <?php
            } else {
        ?>
            <form method="post">

            <button name="allPlaylists" class="selectAll">SELECT ALL</button>
            </form>

        <?php
            }
        ?>
    </div>

            <div class="content" id="playlists">
                <?php
                    if (isset($_SESSION['playlists'])) {

                        $playlists = $_SESSION['playlists'];
                        // print_r($tracks); 
                        // echo "<br> <br>";

                        if (count($playlists) > 0) {

                                $itemIndex = 0;
                                foreach($playlists as $row) {

                                    $type = "PLAYLIST";
                                    $selected = false;


                                    foreach($_SESSION['selectedPlaylists'] as $sa) {
                                        if ($row==$sa) {
                                            $selected = true;
                                        }
                                    }

                                    ?>

                                    <form method="post">

                                    <input type="hidden" name="artist" value="<?=$row[2]?>">
                                    <input type="hidden" name="contentType" value="<?=$type?>">
                                    <input type="hidden" name="title" value="<?=$row[0]?>">
                                    <input type="hidden" name="image" value="<?=$row[3]?>">
                                    <input type="hidden" name="index" value="<?=$itemIndex?>">

                                    <!-- <button type="submit" class="contentItem" name="select">  -->
                                    <?php
                                        if ($selected) {
                                            echo '<button type="submit" class="contentItem" name="unselect" style="background-color: rgba(10, 10, 10, 0.6)">';
                                        } else {
                                            echo '<button type="submit" class="contentItem" name="select">';
                                        }
                                    ?>

                                    <!-- <div class="contentItem"> -->
                                        <div class="contentItem-image">
                                            <img src="<?php echo $row[3]; ?>" alt="">
                                            
                                        </div>
                                        <div class="contentItem-mainText">
                                            <div class="contentLabel">PLAYLIST</div>
                                            <div class="title"><b><?php echo $row[0]; ?></b></div>
                                        </div>

                                    <!-- </div> -->
                                    </button>
                                    </form>


                                    <?php

                                    $itemIndex += 1;
                                }

                        } else {
                            echo "<h2>You Currently Have No Playlists Saved</h2>";
                        }
                    } else {
                        echo "<h2>You Currently Have No Playlists Saved</h2>";
                    }
                ?>
            </div>

            <?php

                if (isset($_POST['allAlbums'])) {
                    $_SESSION['selectedAlbums'] = $albums;
                    echo "<meta http-equiv='refresh' content='0'>";
                }

                if (isset($_POST['allPlaylists'])) {
                    $_SESSION['selectedPlaylists'] = $playlists;
                    echo "<meta http-equiv='refresh' content='0'>";
                    
                }

                if (isset($_POST['allAlbumsUnselect'])) {
                    $_SESSION['selectedAlbums'] = [];
                    echo "<meta http-equiv='refresh' content='0'>";
                }

                if (isset($_POST['allPlaylistsUnselect'])) {
                    $_SESSION['selectedPlaylists'] = [];
                    echo "<meta http-equiv='refresh' content='0'>";
                    
                }
                
                $notEmpty = false;
                if (count($_SESSION['selectedPlaylists']) > 0) {

                    foreach($_SESSION['selectedPlaylists'] as $sp) {
                        if (count($sp[1]) > 1) {
                            $notEmpty = true;
                        }
                    }
                }
                
                if (count($_SESSION['selectedAlbums']) > 0 OR $notEmpty) {
                    ?>

                    <form method="post" class="startForm">
                        <input type="submit" name="startGame" value="Start Game" class="startGame">
                    </form>

                    <?php
                }

            ?>

            <?php

                if (isset($_POST['startGame'])) {

                    unset($_SESSION['currentGameState']);
                    unset($_SESSION['gameTrackName']);
                    unset($_SESSION['newGame']);
                    unset($_SESSION['incorrectGuesses']);
                    unset($_SESSION['guesses']);
                    unset($_SESSION['currentStreak']);
                    unset($_SESSION['gameEnd']);
                    unset($_SESSION['gameWon']);
                    unset($_SESSION['newGame']);
                    
                    $game = $_SESSION['game'];

                    if ($game == "game1") {
                        echo "<meta http-equiv='refresh' content='0;URL=songHangman.php'>";
                    } else if ($game="game2") {
                        echo "<meta http-equiv='refresh' content='0;URL=guessTheSong.php'>";
                    }
                    
                }

                if (isset($_POST['select'])) {
                    
                    $contentType = $_POST['contentType'];
                    $index = $_POST['index'];

                    if ($contentType == "ALBUM") {
                        $albumInfo = $albums[$index];
                        $selectedAlbums = $_SESSION['selectedAlbums'];
                        array_push($selectedAlbums, $albumInfo);
                        $_SESSION['selectedAlbums'] = $selectedAlbums;
                        
                    }
                    if ($contentType == "PLAYLIST") {
                        $playlistInfo = $playlists[$index];
                        $selectedPlaylists = $_SESSION['selectedPlaylists'];
                        array_push($selectedPlaylists, $playlistInfo);
                        $_SESSION['selectedPlaylists'] = $selectedPlaylists;
                    }
                    unset($_POST['select']);
                    echo "<meta http-equiv='refresh' content='0'>";
                }

                if (isset($_POST['unselect'])) {


                    $contentType = $_POST['contentType'];
                    $index = $_POST['index'];

                    if ($contentType == "ALBUM") {
                        $albumInfo = $albums[$index];
                        $selectedAlbums = $_SESSION['selectedAlbums'];
                        
                        for ($a=0; $a<count($selectedAlbums); $a++) {
                            if ($albumInfo == $selectedAlbums[$a]) {
                                array_splice($selectedAlbums, $a, 1);
                                $_SESSION['selectedAlbums'] = $selectedAlbums;
                            }
                        }
                        
                    }
                    if ($contentType == "PLAYLIST") {
                        $playlistInfo = $playlists[$index];
                        $selectedPlaylists = $_SESSION['selectedPlaylists'];
                        
                        for ($a=0; $a<count($selectedPlaylists); $a++) {
                            if ($playlistInfo == $selectedPlaylists[$a]) {
                                array_splice($selectedPlaylists, $a, 1);
                                $_SESSION['selectedPlaylists'] = $selectedPlaylists;
                            }
                        }
                    }
                    echo "<meta http-equiv='refresh' content='0'>";
                }

            ?>

    <script src="assets/scripts/global.js"></script>
</body>
</html>