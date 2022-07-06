<?php
session_start();
include_once(__DIR__ . "/spotify-api/doSearch.php");
include_once(__DIR__ . "/spotify-api/getAlbumTracks.php");
include_once(__DIR__ . "/spotify-api/getCharts.php");
require_once(__DIR__ . "/DBFunctions.php");
@$searchTerm = $_GET["searchInput"];
if (!empty($searchTerm)) {
	// get all types selected with the checkboxes
	$types = array_filter(SPOTIFY_CONTENT_TYPE::$ALL, function($type) { return array_key_exists($type, $_GET); });
	// use all types if none are specified
	if (empty($types)) $types = SPOTIFY_CONTENT_TYPE::$ALL;

    // search with spotify api
	$results = doSearch($searchTerm, $types, "5em");
}
?>

<?php
    if (isset($_SESSION['background'])) {
        $background = $_SESSION['background'];
    } else {
        $background = "assets/images/desert.jpg";
    }


    if (isset($_SESSION['albums'])) {
        $albums = $_SESSION['albums'];
    } else {
        $albums = [];
    }

    if (isset($_SESSION['artists'])) {
        $artists = $_SESSION['artists'];
    } else {
        $artists = [];
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="assets/styles/myStyles.css">
        <link rel="stylesheet" type="text/css" href="assets/styles/discoverStyleSheet.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <title>MUZE# - Discover</title>

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
            <a class = "active" href="discover.php">DISCOVER</a>
            
            <?php
                if (isset($_SESSION['username'])) {
                    echo '<a href="chat.php">CHAT</a>';
                    echo '<a href="games.php">GAMES</a>';
                }
            ?>
            
            
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

        <div class="searchbar">
            <form class="searchForm" method="get" action="discover.php">
                <input id="searchInput" name="searchInput"
                       placeholder="Find Music..." title="Type in a category"
                       value="<?=$searchTerm?>">
            </form>
        </div>



        <div class="content">
        <?php

        if (!empty($searchTerm)) {
	        foreach (SPOTIFY_CONTENT_TYPE::$ALL as $type) {
                // if the search results don't have any of this type, skip this type
		        if (!array_key_exists($type, $results)) continue;

		        $_SESSION['searchResults'] = $results[$type];
                
                $resultIndex = 0;
		        foreach ($results[$type] as $result) {

                    ?>


                    <form method="post">
                            <?php

                                if ($type == SPOTIFY_CONTENT_TYPE::TRACK) {
                                    echo '<input type="hidden" name="artist" value="' . $result["artist"] . '">';
                                    echo '<input type="hidden" name="url" value="' . $result["url"] . '"?>';
                                    
                                }
                                
                                if ($type == SPOTIFY_CONTENT_TYPE::ALBUM) {
                                    echo '<input type="hidden" name="artist" value="' . $result["artist"] . '">';
                                    echo '<input type="hidden" name="url" value="' . $result["url"] . '"?>';

                                }
                                
                                if ($type == SPOTIFY_CONTENT_TYPE::ARTIST) {
                                    echo '<input type="hidden" name="url" value="' . $result["url"] . '"?>';
                                }
                                ?>

                            <input type="hidden" name="contentType" value="<?=strtoupper($type)?>">
                            <input type="hidden" name="title" value="<?=$result["name"]?>">
                            <input type="hidden" name="image" value="<?=$result["biggest_image_url"]?>">
                            <input type="hidden" name="id" value="<?=$result["id"]?>">

                            <button type="submit" class="contentItem" name="expand">
                            <!-- <div class="contentItem"> -->
                                <div class="contentItem-image">
                                    <?=$result["image_tag"]?>
                                </div>
                                <div class="contentItem-mainText">
                                    <div class="contentLabel"><?=strtoupper($type)?></div>
                                    <div class="title"><b><?=$result["name"]?></b></div>
                                    <div class="artist">
                                        <?php if ($type != SPOTIFY_CONTENT_TYPE::ARTIST) { ?>
                                            <?=$result["artist"]?>
                                        <?php } ?>

                                    </div>
                                </div>
                                
        
                                <div class="contentIcons">
        
                                    <?php
                                        if (isset($username) AND $type==SPOTIFY_CONTENT_TYPE::TRACK) {

                                    ?>

                                    <form method="post" id="form" class="addForm">

                                        <a type="submit" class="addButton" href="javascript:void(0)" onclick="document.getElementsByClassName('light')[<?=$resultIndex?>].style.display='flex';document.getElementById('fade').style.display='block'">
                                            +
                                        </a>

                                    

                                    <?php
                                        } else if (isset($username)) {
                                    ?>

                                        <input type="hidden" name="index" value="<?=strval($resultIndex)?>">
                                        <input type="hidden" name="type" value="<?=strtoupper($type)?>">
                                        <input type="hidden" name="submitted">
                                        <input type="submit" class="inputSubmit" value="+">


                                    <?php
                                        }
                                    ?>

                                    </form>
        
                                </div>
                            <!-- </div> -->
        
                        </button>
                    </form>


                    <div class="light">
                        <div class="emptySpace"></div>

                        <form class="playlistMenu" method="post">
                        <input type="hidden" name="index" value="<?=strval($resultIndex)?>">
                        <?php
                            if (isset($_SESSION['playlists'])) {
                                $playlists = $_SESSION['playlists'];
                                foreach($playlists as $p) {
                                    ?>
                                        <button type="submit" name="playlistSubmitted" value="<?=$p[0]?>">
                                            <?=$p[0]?>
                                        </button>
                                        <br><br>
                                    <?php
                                }
                            }
                        ?>
                        </form>
                        <br>
                        <a class="closePopup" href="javascript:void(0)" onclick="document.getElementsByClassName('light')[<?=$resultIndex?>].style.display='none';document.getElementById('fade').style.display='none'">✕</a>
                    </div>
                        


                    <?php
                    $resultIndex += 1;
                }
        

            }
        } else {
	        $charts = getCharts();

            foreach ($charts as $result) {
                ?>
                <!-- <ul>
                    <li><?=$result["id"]?></li>
                    <li><?=$result["name"]?></li>
                    <li><?=$result["artist"]?></li>
                    <li><?=$result["biggest_image_url"]?></li>
                    <li><?=$result["image_tag"]?></li>
                    <li><?=$result["url"]?></li>
                </ul> -->
                <form method="post">

                        <input type="hidden" name="artist" value="<?=$result["artist"]?>">
                        <input type="hidden" name="url" value="<?=$result["url"]?>"?>

                        <input type="hidden" name="contentType" value="TRACK">
                        <input type="hidden" name="title" value="<?=$result["name"]?>">
                        <input type="hidden" name="image" value="<?=$result["biggest_image_url"]?>">
                        <input type="hidden" name="id" value="<?=$result["id"]?>">

                        <button type="submit" class="contentItem" name="expand">
                            <!-- <div class="contentItem"> -->
                                <div class="contentItem-image">
                                    <?=$result["image_tag"]?>
                                </div>
                                <div class="contentItem-mainText">
                                    <div class="contentLabel">TRACK</div>
                                    <div class="title"><b><?=$result["name"]?></b></div>
                                    <div class="artist">
                                            <?=$result["artist"]?>

                                    </div>
                                </div>
                                
        
                                <div class="contentIcons">
        
                                    <?php
                                        if (isset($username)) {

                                    ?>

                                    <form method="post" id="form" class="addForm">

                                        <a type="submit" class="addButton" href="javascript:void(0)" onclick="document.getElementsByClassName('light')[<?=$resultIndex?>].style.display='flex';document.getElementById('fade').style.display='block'">
                                            +
                                        </a>

                                    <?php
                                        }
                                    ?>

                                    </form>
        
                                </div>
                            <!-- </div> -->
        
                        </button>
                    </form>
                <?php
            }
        }
            
            if (isset($_POST["playlistSubmitted"])) {
                
                $playlistName = $_POST['playlistSubmitted'];
                $postIndex = intval($_POST["index"]);
                
                $postType = "TRACK";
                $geniusType = SPOTIFY_CONTENT_TYPE::TRACK;

                echo $_POST['index'];
                
                // $resultToSave = ($results[$postType]->items)[$postIndex];

                $resultToSave = ($results[$geniusType])[$postIndex];
                
    
                addToPlaylist($username, $playlistName, $resultToSave["name"], $resultToSave["artist"], $resultToSave["biggest_image_url"], $resultToSave['url']);
                getPlaylists($username);

                unset($_POST["playlistSubmitted"]);
                unset($_POST["index"]);
                unset($_POST["type"]);



                $_SESSION['reloadThePage'] = true;
                echo "<meta http-equiv='refresh' content='0'>";
            }




            if (isset($_POST['submitted'])) {
                $postIndex = intval($_POST["index"]);
                $postType = $_POST['type'];
                $id = $_POST['id'];

                if ($postType == "ALBUM") {
                    $geniusType = SPOTIFY_CONTENT_TYPE::ALBUM;

                } else if ($postType == "ARTIST") {
                    $geniusType = SPOTIFY_CONTENT_TYPE::ARTIST;
                }
                
                
                // $resultToSave = ($results[$postType]->items)[$postIndex];

                $resultToSave = ($results[$geniusType])[$postIndex];
                

                if ($postType == "ALBUM") {
                    $albumTrackList = [];
                    foreach(getAlbumTracks($id) as $track) {
                        array_push($albumTrackList, $track['name']);
                    }

                    addAlbum($username, $resultToSave["name"], $resultToSave["artist"], $albumTrackList, $resultToSave["biggest_image_url"], $resultToSave['url']);
                    getAlbums($username);
                }

                if ($postType == "ARTIST") {
                    addArtist($username, $resultToSave["name"], $resultToSave["biggest_image_url"], $resultToSave['url']);
                    getArtists($username);
                }

                unset($_POST["index"]);
                unset($_POST["type"]);



                $_SESSION['reloadThePage'] = true;
                echo "<meta http-equiv='refresh' content='0'>";
            }




            if (isset($_POST['expand'])) {
                $_SESSION['id'] = $_POST['id'];
                $_SESSION['title'] = $_POST['title'];
                $_SESSION['image'] = $_POST['image'];
                $_SESSION['type'] = $_POST['contentType'];
                // $_SESSION['type'] = "TRACK";
                if (isset($_POST['artist'])) {
                    $_SESSION['artist'] = $_POST['artist'];
                }
                if (isset($_POST['url'])) {
                    $_SESSION['url'] = $_POST['url'];
                }
                if ($_POST['contentType'] == "PLAYLIST") {
                    foreach($_SESSION['playlists'] as $p) {
                        if ($p[0] == $_POST['title']) {
                            $_SESSION['tracklist'] = $p[1];
                        }
                    }
                }

                if ($_POST['contentType'] == "ALBUM") {
                    foreach($_SESSION['albums'] as $p) {
                        if ($p[0] == $_POST['title']) {

                            $albumTrackList = [];
                            foreach(getAlbumTracks($id) as $track) {
                                array_push($albumTrackList, $track['name']);
                            }

                            $_SESSION['tracklist'] = $albumTrackList;
                        }
                    }
                }

                echo "<meta http-equiv='refresh' content='0;URL=contentInfo.php'>";
            }

        ?>
        </div>

        <div id="fade" class="black_overlay"></div>

        <script src="assets/scripts/global.js"></script>
    </body>
</html>
