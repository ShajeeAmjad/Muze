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
    <link rel="stylesheet" type="text/css" href="../assets/styles/myStyles.css">
    <link rel="stylesheet" type="text/css" href="../assets/styles/myMusicAllStyleSheet.css">
    <!---<link rel="stylesheet" type="text/css" href="assets/styles/discoverStyleSheet.css"--->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <title>MUZE# - My Music</title>
</head>
<body style="background-image: url(../<?php echo $background ?>);">

    <div class="topnav">
        <a href="../home.php">HOME</a>
        <a href="../discover.php">DISCOVER</a>
        <a href="../chat.php">CHAT</a>
        <a href="../games.php">GAMES</a>

        
        <?php

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo'<a style="float: right;" href="../myAccount.php">MY ACCOUNT</a>';
            } else {
                echo'<a style="float: right;" href="../login.php">LOGIN</a>';
            }
        ?>
        <a style="float: right;" class = "active" href="../myMusic.php">MY MUSIC</a>
        
    </div>

            
    <div class="musicHeading">
        <div class="back"><a href="../myMusic.php">BACK</a></div>
        <div class="heading">
            <b>My Albums</b>
        </div>
        <div class="emptySpace"></div>
    </div>

            <div class="content">
                <?php
                    if (isset($_SESSION['albums'])) {

                        $albums = $_SESSION['albums'];
                        // print_r($tracks); 
                        // echo "<br> <br>";

                        if (count($albums) > 0) {

                            foreach($albums as $row) {

                                $type = "ALBUM";
                                ?>

                                <form method="post">

                                <input type="hidden" name="artist" value="<?=$row[2]?>">
                                <input type="hidden" name="contentType" value="<?=$type?>">
                                <input type="hidden" name="title" value="<?=$row[0]?>">
                                <input type="hidden" name="image" value="<?=$row[3]?>">
                                <input type="hidden" name="url" value="<?=$row[4]?>">

                                <button type="submit" class="contentItem" name="expand">

                                <!-- <div class="contentItem"> -->
                                    <div class="contentItem-image">
                                        <img src="<?php echo $row[3]; ?>" alt="">
                                        
                                    </div>
                                    <div class="contentItem-mainText">
                                        <div class="contentLabel">ALBUM</div>
                                        <div class="title"><b><?php echo $row[0]; ?></b></div>
                                        <?php echo $row[2]; ?>
                                    </div>

                                <!-- </div> -->

                                </form>
                                </button>

                                <?php
                            }

                        } else {
                            echo "<h2>You Currently Have No Albums Saved</h2>";
                        }
                    } else {
                        echo "<h2>You Currently Have No Albums Saved</h2>";
                    }
                ?>
            </div>

            <?php

            if (isset($_POST['expand'])) {
                $_SESSION['title'] = $_POST['title'];
                $_SESSION['image'] = $_POST['image'];
                $_SESSION['type'] = $_POST['contentType'];
                $_SESSION['artist'] = $_POST['artist'];
                $_SESSION['url'] = $_POST['url'];

                for($a=0; $a<count($albums); $a++) {
                    if ($albums[$a][4] == $_POST['url']) {
                        $_SESSION['tracklist'] = $albums[$a][1];
                    }
                }

                echo "<meta http-equiv='refresh' content='0;URL=../myContentInfo.php'>";
            }

            ?>

            <br><br><br><br>

    <script src="../assets/scripts/global.js"></script>
</body>
</html>