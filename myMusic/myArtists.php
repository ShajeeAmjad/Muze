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
        <a href="g../ames.php">GAMES</a>

        
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
            <b>My Artists</b>
        </div>
        <div class="emptySpace"></div>
    </div>

            <div class="content">
                <?php
                    if (isset($_SESSION['artists'])) {

                        $artists = $_SESSION['artists'];
                        // print_r($tracks); 
                        // echo "<br> <br>";

                        if (count($artists) > 0) {

                            foreach($artists as $row) {
                                $type = "ARTIST";
                                ?>

                                <form method="post">

                                                                                                            
                                <input type="hidden" name="contentType" value="<?=$type?>">
                                <input type="hidden" name="title" value="<?=$row[0]?>">
                                <input type="hidden" name="image" value="<?=$row[1]?>">

                                <button type="submit" class="contentItem" name="expand">

                                <!-- <div class="contentItem"> -->
                                    <div class="contentItem-image">
                                        <img src="<?php echo $row[1]; ?>" alt="">
                                        
                                    </div>
                                    <div class="contentItem-mainText">
                                        <div class="contentLabel">ARTIST</div>
                                        <div class="title"><b><?php echo $row[0]; ?></b></div>
                                    </div>

                                <!-- </div> -->

                                </button>
                                </form>

                                <?php
                            }

                        } else {
                            echo "<h2>You Currently Have No Artists Saved</h2>";
                        }
                    } else {
                        echo "<h2>You Currently Have No Artists Saved</h2>";
                    }
                ?>
            </div>

            <?php

            if (isset($_POST['expand'])) {
                $_SESSION['title'] = $_POST['title'];
                $_SESSION['image'] = $_POST['image'];
                $_SESSION['type'] = $_POST['contentType'];

                echo "<meta http-equiv='refresh' content='0;URL=../myContentInfo.php'>";
            }

            ?>

            <br><br><br><br>

    <script src="../assets/scripts/global.js"></script>
</body>
</html>