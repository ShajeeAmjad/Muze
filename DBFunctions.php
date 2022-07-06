<?php
    // function createDatabase(){
    // $sql = "CREATE DATABASE IF NOT EXISTS loginInfo";
    // $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk;', 'u95206ma', 'deeznuts123');
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    // $pdo->query($sql);
    // }

    function createTable(){
        $sql = "CREATE TABLE IF NOT EXISTS users (
            userID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            background VARCHAR(200) NOT NULL,
            profilePicture VARCHAR(200) NOT NULL)";
    
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }
    

    function dropUsersTable(){
        $sql = "DROP TABLE users";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function dropAlbumsTable(){
        $sql = "DROP TABLE albums";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function dropArtistsTable(){
        $sql = "DROP TABLE artists";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function dropPlaylistsTable(){
        $sql = "DROP TABLE playlists";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function addUser($username, $password){
        $sql = "SELECT * FROM users WHERE username=?";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $emailExists = $stmt->fetch();
        if ($emailExists) {

        } else {
            $sql = "INSERT INTO users (username, password, background, profilePicture)
                    VALUES (:username, :password, :background, :profilePicture)";
            $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            
            $stmt = $pdo->prepare($sql);
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([
                'username' => $username,
                'password' => $password,
                'background' => "assets/images/desert.jpg",
                'profilePicture' => "assets/images/redblack.jpg"
            ]);
            // session_start();
            $_SESSION['username'] = $username;
            $_SESSION['background'] = "assets/images/desert.jpg";
            $_SESSION['profilePicture'] = "assets/images/redblack.jpg";

            addPlaylist($username, "My Tracks", $username, [""], "assets/images/playlist.webp", [""]);
            
            getAlbums($username);
            getArtists($username);
            getPlaylists($username);

            header("location: home.php");
        }

    }

    function getUser($id) {
        $sql = "SELECT username, password
                FROM users
                WHERE userID=:id";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
    }

    function authenticateUser($username, $password) {
        $sql = "SELECT password, background, profilePicture
                FROM users
                WHERE username = :username";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();

        echo "<br>";

        if ($stmt->rowCount() > 0) {
            if (password_verify($password, $row['password'])) {
                // session_start();
                $_SESSION['username'] = $username;
                
                $background = $row['background'];
                $_SESSION['background'] = $background;

                $profilePicture = $row['profilePicture'];
                $_SESSION['profilePicture'] = $profilePicture;

                getAlbums($username);
                getArtists($username);
                getPlaylists($username);
                
                header("location: home.php");
            }
        }        
    }


    function changePassword($username, $oldPassword, $newPassword) {
        $sql = "SELECT password
                FROM users
                WHERE username=:username";

        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        $row = $stmt->fetch();
        
        if ($row) {

            if (password_verify($oldPassword, $row[0])) {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql2 = "UPDATE users
                        SET password = :password
                        WHERE username = :username";

                $pdo2 = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
                $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    
                $stmt2 = $pdo2->prepare($sql2);
                $stmt2->execute([
                    'password' => $hashedNewPassword,
                    'username' => $username
                ]);
            } else {
                $changePasswordError = true;
                $_SESSION['changePasswordError'] = $changePasswordError;
            }

        }
    }

    // STORING ALBUMS


    function createAlbumsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS albums (
            dataID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            title VARCHAR(100),
            artist VARCHAR(100),
            trackName VARCHAR(100),
            image VARCHAR(100),
            url VARCHAR(100))";
        
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function addAlbum($username, $title, $artist, $trackList, $image, $url) {

        for ($a=0; $a<count($trackList); $a++) {
            $trackName = $trackList[$a];

            $sql = "INSERT INTO albums (username, title, trackName, artist, image, url)
                VALUES (:username, :title, :trackName, :artist, :image, :url)";
    
            $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'title' => $title,
                'trackName' => $trackName,
                'artist' => $artist,
                'image' => $image,
                'url' => $url
            ]);
        }

        
    }

    function removeAlbum($username, $title, $artist) {
        $sql = "DELETE FROM albums
                WHERE username=:username AND title=:title AND artist=:artist";
                
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'title' => $title,
            'artist' => $artist
        ]);
    }

    function getAlbums($username) {
        $sql = "SELECT title, trackName, artist, image, url
                FROM albums
                WHERE username=:username";

        // $sql = "SELECT title, image, username FROM music";

        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        // $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetchAll();

        $albumImages = [];
        $albums = [];
        foreach($row as $track) {
            if (in_array($track[3], $albumImages)) {
                
            } else {
                array_push($albumImages, $track[3]);
                $albumTracks = [];
                foreach($row as $tempTrack) {
                    if ($track[3] == $tempTrack[3]) {
                        array_push($albumTracks, $tempTrack[1]);
                    }
                }
                $albumInfo = [$track[0], $albumTracks, $track[2], $track[3], $track[4]];
                array_push($albums, $albumInfo);
            }
        }
        $_SESSION['albums'] = $albums;
    }

    // STORING ARTISTS

    
    function createArtistsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS artists (
            dataID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            artist VARCHAR(100),
            image VARCHAR(100),
            url VARCHAR(100))";
        
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function addArtist($username, $artist, $image, $url) {
        $sql = "INSERT INTO artists (username, artist, image, url)
                VALUES (:username, :artist, :image, :url)";

        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'artist' => $artist,
            'image' => $image,
            'url' => $url
        ]);
    }

    function removeArtist($username, $artist) {
        $sql = "DELETE FROM artists
                WHERE username=:username AND artist=:artist";
                
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'artist' => $artist
        ]);
    }

    function getArtists($username) {
        $sql = "SELECT artist, image, url
                FROM artists
                WHERE username=:username";

        // $sql = "SELECT title, image, username FROM music";

        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        // $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetchAll();

        $_SESSION['artists'] = $row;
    }


    // STORING ALBUMS


    function createPlaylistsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS playlists (
            dataID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            title VARCHAR(100) NOT NULL,
            artist VARCHAR(100) NOT NULL,
            trackName VARCHAR(100) NOT NULL,
            image VARCHAR(100),
            url VARCHAR(100))";
        
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function dropPlaylistTable() {
        $sql = "DROP TABLE playlists";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $pdo->query($sql);
    }

    function addPlaylist($username, $title, $artist, $tracklist, $image, $urlList) {

        $sql = "INSERT INTO playlists (username, title, trackName, artist, image, url)
            VALUES (:username, :title, :trackName, :artist, :image, :url)";

        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmtlist = [];

        for($a=0; $a<count($tracklist); $a++) {

            $stmtlist[$a] = $pdo->prepare($sql);
            $trackName = $tracklist[$a];
            $url = $urlList[$a];
            $stmtlist[$a]->execute([
                'username' => $username,
                'title' => $title,
                'trackName' => $trackName,
                'artist' => $artist,
                'image' => $image,
                'url' => $url
            ]);
        }
    }

    function addToPlaylist($username, $title, $trackName, $artist, $image, $url) {
        $sql = "INSERT INTO playlists (username, title, trackName, artist, image, url)
                VALUES (:username, :title, :trackName, :artist, :image, :url)";
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'title' => $title,
            'trackName' => $trackName,
            'artist' => $artist,
            'image' => $image,
            'url' => $url
        ]);
    }

    function removePlaylist($username, $title) {
        $sql = "DELETE FROM playlists
                WHERE username=:username AND title=:title";
                
        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'title' => $title,
        ]);
    }

    function getPlaylists($username) {
        $sql = "SELECT title, trackName, artist, image, url
                FROM playlists
                WHERE username=:username";

        // $sql = "SELECT title, image, username FROM music";

        $pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk; dbname=2021_comp10120_m8', 'u95206ma', 'deeznuts123');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username
        ]);

        // $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetchAll();

        $albumImages = [];
        $albums = [];

        foreach($row as $track) {
            if (in_array($track[0], $albumImages)) {
                
            } else {
                array_push($albumImages, $track[0]);
                $albumTracks = [];
                $albumInfo = [];
                foreach($row as $tempTrack) {
                    if ($track[0] == $tempTrack[0]) {
                        $tempInfo = [$tempTrack[1], $tempTrack[2], $tempTrack[3], $tempTrack[4]];
                        array_push($albumTracks, $tempInfo);
                    }
                }
                $albumInfo = [$track[0], $albumTracks, $track[2], $track[3]];
                array_push($albums, $albumInfo);
            }
        }
        $_SESSION['playlists'] = $albums;
    }

?>