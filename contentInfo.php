<?php
    session_start();
    include_once(__DIR__ . "/spotify-api/getAlbumTracks.php");
    include_once(__DIR__ . "/spotify-api/getRecommendations.php");
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
    <link rel="stylesheet" type="text/css" href="assets/styles/contentInfoStyleSheet.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/discoverStyleSheet.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MUZE# - Discover</title>

</head>
<body style="background-image: url(<?php echo $background ?>);">
    <div class="topnav">
        <a href="home.php">HOME</a>
        <a class = "active" href="discover.php">DISCOVER</a>
        
        <?php
            if (isset($_SESSION['username'])) {
                echo '<a href="chat.php">CHAT</a>';
            }
        ?>
        
        <a href="games.php">GAMES</a>

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
        $itemId = $_SESSION['id'];
        $title = $_SESSION['title'];
        $image = $_SESSION['image'];
        $type = $_SESSION['type'];

        if (isset($_SESSION['artist'])) {
            $artist = $_SESSION['artist'];
        }
        if (isset($_SESSION['url'])) {
            $url = $_SESSION['url'];
        }
        if (isset($_SESSION['tracklist'])) {
            $tracklist = $_SESSION['tracklist'];
        }
    ?>

        <?php
        if ($type=="TRACK") {   
        ?>

        <div class="contentWrapper">
            <div class="imageWrapper">
                <img src="<?=$image?>" alt="">
            </div>
            <div class="textWrapper">
                <div class="type"><?=$type?></div>
                <div class="title"><b><?=$title?></b></div>
                <div class="artist"><?=$artist?></div>
                <br>
                <a target="_blank"  class="link" href="<?=$url?>">Listen on Spotify</a>
            </div>
        </div>

        <div class="content">
        <?php
        $recommendations = getRecommendations([], [], [$itemId]);
        foreach (SPOTIFY_CONTENT_TYPE::$ALL as $type) {
            foreach ($recommendations[$type] as $result) {
        ?>
            <!-- <ul>
                <li><?=$result["id"]?></li>
                <li><?=$result["name"]?></li>
                <li><?=$result["artist"]?></li>
                <li><?=$result["biggest_image_url"]?></li>
                <li><?=$result["image_tag"]?></li>
                <li><?=$result["url"]?></li>
            </ul> -->
            <form action="">

                <input type="hidden" name="artist" value="<?=$result["artist"]?>">
                <input type="hidden" name="url" value="<?=$result["url"]?>"?>

                <input type="hidden" name="contentType" value="TRACK">
                <input type="hidden" name="title" value="<?=$result["name"]?>">
                <input type="hidden" name="image" value="<?=$result["biggest_image_url"]?>">
                <input type="hidden" name="id" value="<?=$result["id"]?>">

                <div class="recContentItem" name="expand" style="scale: 0.9;">
                    <!-- <div class="contentItem"> -->
                        <div class="contentItem-image">
                            <?=$result["image_tag"]?>
                        </div>
                        <div class="contentItem-mainText">
                            <div class="contentLabel">RELATED TRACK</div>
                            <div class="title"><b><?=$result["name"]?></b></div>
                            <div class="artist">
                                    <?=$result["artist"]?>

                            </div>
                        </div>
                        
                    <!-- </div> -->

            </div>
            </form>
        <?php
            }
        }
        ?>
        </div>

        <?php
        }
        if ($type=="ALBUM") {
        ?>
            
        <div class="contentWrapper">
            <div class="imageWrapper">
                <img src="<?=$image?>" alt="">
            </div>
            <div class="textWrapper">
                <div class="type"><?=$type?></div>
                <div class="title"><b><?=$title?></b></div>
                <div class="artist"><?=$artist?></div>
                <br>
                <a target="_blank"  class="link" href="<?=$url?>">Listen on Spotify</a>
            </div>
        </div>

        <div class="content">

        <br>

            

            <?php 

            $albumIndex = 1;

            foreach ($tracklist as $track) { ?>

                    <div class="albumTrackWrapper" style="margin-bottom: 1em;">
    
                        <div class="trackTextWrapper">
    
                            <div class="albumTrackTitle">
                                <?=$albumIndex . ". " . $track?>
                            </div> <br>                            
    
                        </div>
                        
                        
                    </div> 

            <?php
            $albumIndex += 1;
            } 
            ?>
        </div>

        <?php
        }
        if ($type=="ARTIST") {
        ?>
        

        <div class="contentWrapper">
            <div class="imageWrapper">
                <img src="<?=$image?>" alt="">
            </div>
            <div class="textWrapper">
                <div class="type"><?=$type?></div>
                <div class="title"><b><?=$title?></b></div>
                <br>
                <a target="_blank"  class="link" href="<?=$url?>">Listen on Spotify</a>
            </div>
        </div>

        <?php
        }

        if ($type=="PLAYLIST") {
            ?>
                
            <div class="contentWrapper">
                <div class="imageWrapper">
                    <img src="<?=$image?>" alt="">
                </div>
                <div class="textWrapper">
                    <div class="type"><?=$type?></div>
                    <div class="title"><b><?=$title?></b></div>
                </div>
            </div>
    
        <?php
        }
        ?>



    <script src="assets/scripts/global.js"></script>
</body>
</html>