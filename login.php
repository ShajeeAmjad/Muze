<?php require_once(__DIR__ . "/DBFunctions.php"); ?>

<?php

try {
    $conn = new pdo('mysql:host=dbhost.cs.man.ac.uk;', 'u95206ma', 'deeznuts123');
    // $conn = new pdo('mysql:host=localhost:8889;', 'root', 'root');
    // echo "connected to localhost:8889 successfully";
}
catch(PDOException $pe) {
    die("could not connect to host " . $pe->getMessage());
}

// createDatabase();
createTable();
createAlbumsTable();
createArtistsTable();
createPlaylistsTable();

?>

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
    <link rel="stylesheet" href="assets/styles/myStyles.css">
    <link rel="stylesheet" href="assets/styles/loginStyleSheet.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MUZE# - Log In</title>
</head>
<body style="background-image: url(<?php echo $background ?>);">
    <div class="topnav">
        <a href="home.php">HOME</a>
        <a href="discover.php">DISCOVER</a>

        <?php
            if (isset($_SESSION['username'])) {
                echo '<a href="chat.php">CHAT</a>';
                echo '<a href="games.php">GAMES</a>';
            }
        ?>

        
        <a class = "active" style="float: right;" href="login.php">LOGIN</a>
        <?php

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo'<a style="float: right;" href="myMusic.php">MY MUSIC</a>';
            }
            ?>
            
    </div>

    <div class="loginForm">

        <form method="post" action="login.php">
            
            <div class="formTitle">
                Log In
            </div> <br>

            <input type="text" name="username" id="username" required placeholder="Username"> <br>

            <input type="password" name="password" id="password" required placeholder="Password"> <br>

            <input type="hidden" name="action" value="login">
            <input type="submit" name="filled" value="Log in"> <br> <br>
            <?php 
                if (isset($_SESSION['loginError'])) {
                    echo "<div class='error'>Incorrect Username or Password</div>";
                } else {
                    echo "<br>";
                }
            ?>
        </form> <br> <br> <br>
    
        <form method="post" action="login.php">

            <div class="formTitle">
                Sign Up
            </div> <br>
            
            <input type="text" name="username" id="username" required placeholder="Username"> <br>

            <input type="password" name="password" id="password" required placeholder="Password"> <br>

            <input type="hidden" name="action" value="register">
            <input type="submit" name="filled" value="Register"> <br> <br>
            <?php 
                if (isset($_SESSION['registerError'])) {
                    echo "<div class='error'>Sorry, Username already in use</div>";
                } else {
                    echo "<br>";
                }
            ?>
        </form>
    </div>

    <?php
        if(isset($_POST['filled'])) {
            switch($_POST['action']) {
                case 'login':
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $_SESSION['loginError'] = 1;
                    unset($_SESSION['registerError']);
                    authenticateUser($username, $password);
                    echo "<meta http-equiv='refresh' content='0'>";
                break;
                case 'register':
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    addUser($username, $password);
                    $_SESSION['registerError'] = 1;
                    unset($_SESSION['loginError']);
                    echo "<meta http-equiv='refresh' content='0'>";
                break;
            }    
        }
    ?>

    <script src="assets/scripts/global.js"></script>
</body>
</html>