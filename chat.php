<?php
    session_start();

    require_once "internal-api/Chat.php";

    if (!empty($_POST["chatInput"])) {
	    Chat::SendMessage($_POST["selectedChat"], $_POST["chatInput"]);
    }

    if (isset($_SESSION['background'])) {
        $background = $_SESSION['background'];
    } else {
        $background = "assets/images/desert.jpg";
    }

    // todo: remove this! used as placeholder for logging in
    $_SESSION["userId"] ??= 12;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="assets/styles/myStyles.css">
    <link rel="stylesheet" type="text/css" href="assets/styles/chatStyleSheet.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MUZE# - Chat</title>
</head>
<body style="background-image: url(<?php echo $background ?>);">
    <div class="topnav">
        <a href="home.php">HOME</a>
        <a href="discover.php">DISCOVER</a>
        <a class = "active" href="chat.php">CHAT</a>
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

    <div class="chatArea">
        <ul class="chatSidebar">
            <?php
            function chatOption($chatId, $chatOption, $isSelected=false) {
                $selected = $isSelected ? "selected" : "";
                ?>
                <a href="chat.php?selectedChat=<?=$chatId?>">
                    <li class="chatOption <?=$selected?>" id="chatOption<?=$chatId?>">
                        <img src="assets/images/redblack.jpg" alt="<?=$chatOption["userName"]?>'s profile picture">
                        <div>
                            <h5><?=$chatOption["userName"]?></h5>
                            <p class="lastMessage"><?=$chatOption["lastMessage"]?></p>
                        </div>
                    </li>
                </a>
                <?php
            }
            $chats = Chat::ListChats();
            $selectedChatId = $_GET["selectedChat"] ?? "";
            foreach ($chats as $chatId => $chatOption) { // now show the rest of the chats
                chatOption($chatId, $chatOption, $chatId == $selectedChatId);
            }
            ?>
        </ul>
        <div class="chatMain">
            <ul class="chatMessages"><?php
                if (!empty($selectedChatId)) foreach (Chat::GetChatMessages($selectedChatId) as $messageId => $messageObj) {
                    $ownMessage = $messageObj["author"] == $_SESSION['username'] ? "chatMessage-ownMessage": "";
                    ?>
                    <li class="chatMessage <?=$ownMessage?>" id="chatMessage<?=$messageId?>">
                        <?=$messageObj["text"]?>
                    </li>
                    <?php
                }
            ?></ul>
            <form id="chatInputForm" action="chat.php?selectedChat=<?=$_GET["selectedChat"] ?? ""?>" method="post">
                <input type="hidden" name="selectedChat" value="<?=$_GET["selectedChat"] ?? ""?>">
                <input class="chatInput" name="chatInput" placeholder="Send a message..." aria-label="Send a message">
            </form>
        </div>
    </div>

    <!-- empty flex child to give us a gap at the bottom -->
    <div></div>

<script src="assets/scripts/global.js"></script>
<script src="assets/scripts/chatScript.js"></script>
</body>
</html>
