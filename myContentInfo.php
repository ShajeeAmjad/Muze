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
    <link rel="stylesheet" type="text/css" href="assets/styles/contentInfoStyleSheet.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MUZE# - Discover</title>

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
                echo'<a class = "active" style="float: right;" href="myMusic.php">MY MUSIC</a>';
            }
        ?>
    </div>

    <?php
        if (isset($_SESSION['id'])) {

            $itemId = $_SESSION['id'];
        }

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
        <br>

        <?php 
        $albumIndex = 1;
        foreach ($tracklist as $track) { ?>
            <form method="post">

                    <div type="submit" name="expand" class="albumTrackWrapper">
    
                        <div class="trackTextWrapper">
    
                            <div class="albumTrackTitle">
                                <?=$albumIndex . ". " . $track?>
                            </div> <br>
                        
                        </div>
    
    
            </div>
            </form>
            <br>
            <?php
            $albumIndex += 1;
            } 
            ?>

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

        <br><br><br><br>

        <?php

            foreach(array_slice($tracklist, 1) as $t) {
                ?>
                <form method="post">

                    <input type="hidden" name="contentType" value="TRACK">
                    <input type="hidden" name="title" value="<?=$t[0]?>">
                    <input type="hidden" name="image" value="<?=$t[2]?>">
                    <input type="hidden" name="artist" value="<?=$t[1]?>">
                    <input type="hidden" name="url" value="<?=$t[3]?>">

                    <button type="submit" name="expand" class="trackWrapper">
    
                        <img src="<?=$t[2]?>" alt="" class="trackImage">
    
                        <div class="trackTextWrapper">
    
                            <div class="trackTitle">
                                <?=$t[0]?>
                            </div> <br>
        
                            <div class="trackArtist">
                                <?=$t[1]?>
                            </div>
                            
    
                        </div>
    
    
                    </button>

                </form>

                <br><br>

                <?php
            }
        }

        if (isset($_POST['expand'])) {
            $_SESSION['title'] = $_POST['title'];
            $_SESSION['image'] = $_POST['image'];
            $_SESSION['type'] = $_POST['contentType'];
            $_SESSION['artist'] = $_POST['artist'];
            $_SESSION['url'] = $_POST['url'];
                
            echo "<meta http-equiv='refresh' content='0;URL=myContentInfo.php'>";
        }

        ?>






    <script src="assets/scripts/global.js"></script>
</body>
</html>