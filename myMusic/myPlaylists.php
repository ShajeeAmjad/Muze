
<?php require_once(__DIR__ . "/../DBFunctions.php"); ?>

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
    <link rel="stylesheet" type="text/css" href="../assets/styles/myPlaylistsStyleSheet.css">
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
            <b>My Playlists</b>
        </div>
        <div class="emptySpace"></div>
    </div>

            <div class="content">
                <?php
                    if (isset($_SESSION['playlists'])) {

                        $playlists = $_SESSION['playlists'];
                        // print_r($tracks); 
                        // echo "<br> <br>";

                        if (count($playlists) > 0) {

                            foreach($playlists as $row) {

                                $type = "PLAYLIST";
                                ?>

                                <form method="post">

                                                                                                            
                                <input type="hidden" name="artist" value="<?=$row[2]?>">
                                <input type="hidden" name="contentType" value="<?=$type?>">
                                <input type="hidden" name="title" value="<?=$row[0]?>">
                                <input type="hidden" name="image" value="<?=$row[3]?>">

                                <button type="submit" class="contentItem" name="expand">

                                <!-- <div class="contentItem"> -->
                                    <div class="contentItem-image">
                                        <img src="<?php echo $row[3]; ?>" alt="">
                                        
                                    </div>
                                    <div class="contentItem-mainText">
                                        <div class="contentLabel">PLAYLIST</div>
                                        <div class="title"><b><?php echo $row[0]; ?></b></div>
                                        <?php //echo $row[2]; ?>
                                    </div>

                                <!-- </div> -->

                                </button>
                                </form>

                                <?php
                            }

                        } else {
                            echo "<h2>You Currently Have No Playlists Saved</h2>";
                        }
                    } else {
                        echo "<h2>You Currently Have No Playlists Saved</h2>";
                    }
                ?>
            </div>

            <br><br>

    <div class="newPlaylistForm">

        <button class="newPlaylist" id="createPlaylistToggle">Create New</button> <br> <br>

        <form method="post" id="createPlaylist" class="hide">
            <input type="text" name="playlistName" placeholder="Playlist Name" required> <br>
            <input type="submit" value="Confirm">
        </form>

        <script>
  
          const btn = document.getElementById("createPlaylistToggle");
          btn.onclick = function () {
              var x = document.getElementById("createPlaylist");
              if (x.classList.contains("hide")) {
                  x.classList.remove("hide");
              } else {
                  x.classList.add("hide");
              }
          }
        </script>

    </div>


    <?php
        if (isset($_POST['playlistName'])) {
            $title = $_POST['playlistName'];
            addPlaylist($username, $title, $username, [""], "assets/images/playlist.webp", [""]);
            getPlaylists($username);
            unset($_POST['playlistName']);
            echo "<meta http-equiv='refresh' content='0'>";
        }

    ?>

    <?php

    if (isset($_POST['expand'])) {
        $_SESSION['title'] = $_POST['title'];
        $_SESSION['image'] = $_POST['image'];
        $_SESSION['type'] = $_POST['contentType'];

        $_SESSION['artist'] = $_POST['artist'];

        foreach($_SESSION['playlists'] as $p) {
            if ($p[0] == $_POST['title']) {
                $_SESSION['tracklist'] = $p[1];
            }
        }

        echo "<meta http-equiv='refresh' content='0;URL=../myContentInfo.php'>";
    }

    ?>

    <br><br><br><br>

    <script src="../assets/scripts/global.js"></script>
</body>
</html>