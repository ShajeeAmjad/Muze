<?php
// (A) LOAD RELATIOSHIP LIBRARY + SET CURRENT USER
require "lib-relation.php";
$uid = 1;
 
// (B) PROCESS RELATIONSHIP REQUEST
if (isset($_POST['req'])) {
  $pass = true;
  switch ($_POST['req']) {
    // (B0) INVALID
    default: $pass = false; $REL->error = "Invalid request"; break;
    // (B1) ADD FRIEND
    case "add": $pass = $REL->request($uid, $_POST['id']); break;
    // (B2) ACCEPT FRIEND
    case "accept": $pass = $REL->acceptReq($_POST['id'], $uid); break;
    // (B3) CANCEL ADD
    case "cancel": $pass = $REL->cancelReq($uid, $_POST['id']); break;
    // (B4) UNFRIEND
    case "unfriend": $pass = $REL->unfriend($uid, $_POST['id'], false); break;
    // (B5) BLOCK
    case "block": $pass = $REL->block($uid, $_POST['id']); break;
    // (B6) UNBLOCK
    case "unblock": $pass = $REL->block($uid, $_POST['id'], false); break;
  }
  echo $pass ? "<div class='ok'>OK</div>" : "<div class='nok'>{$REL->error}</div>";
}
 
// (C) GET + SHOW ALL USERS
$users = $REL->getUsers(); ?>
<script type="text/javascript">
  function relate (req, uid) {
  document.getElementById("ninreq").value = req;
  document.getElementById("ninid").value = uid;
  document.getElementById("ninform").submit();
}
</script>
<div id="userNow">You are now <?=$users[$uid]?>.</div>
<div id="userList"><?php
  $requests = $REL->getReq($uid);
  $friends = $REL->getFriends($uid);
  foreach ($users as $id=>$name) { if ($id != $uid) {
    echo "<div class='urow'>";
    // (C1) USER ID & NAME
    echo "<div class='uname'>$id) $name</div>";
 
    // (C2) BLOCK/UNBLOCK
    if (isset($friends['b'][$id])) {
      echo "<button onclick=\"relate('unblock', $id)\">Unblock</button>";
    } else {
      echo "<button onclick=\"relate('block', $id)\">Block</button>";
    }
 
    // (C3) FRIEND STATUS
    // FRIENDS
    if (isset($friends['f'][$id])) { 
      echo "<button onclick=\"relate('unfriend', $id)\">Unfriend</button>";
    }
    // INCOMING FRIEND REQUEST
    else if (isset($requests['in'][$id])) { 
      echo "<button onclick=\"relate('accept', $id)\">Accept Friend</button>";
    }
    // OUTGOING FRIEND REQUEST
    else if (isset($requests['out'][$id])) { 
      echo "<button onclick=\"relate('cancel', $id)\">Cancel Add</button>";
    }
    //STRANGERS
    else { 
      echo "<button onclick=\"relate('add', $id)\">Add Friend</button>";
    }
    echo "</div>";
  }}
?></div>
 
<!-- (D) NINJA RELATIONSHIP FORM -->
<form id="ninform" method="post" target="_self">
  <input type="hidden" name="req" id="ninreq"/>
  <input type="hidden" name="id" id="ninid"/>
</form>
<div>
  <form method="post">
  <label>Search</label>
  <input type="text" name="search">
  <input type="submit" name="submit">
  </form>
</div>
<?php

$con = new PDO("mysql:host=dbhost.cs.man.ac.uk;dbname=2021_comp10120_m8",'n80569fh','balls1235');

if (isset($_POST["submit"])) {
  $str = $_POST["search"];
  $sth = $con->prepare("SELECT * FROM `users` WHERE username = '$str'");

  $sth -> setFetchMode(PDO:: FETCH_OBJ);
  $sth -> execute();

  if($row = $sth->fetch())
  {
    ?>
    <br><br><br>
    <table>
      <tr>
        <th>Name</th>
      </tr>
      <tr>
        <td><?php echo $row->username;
  //         foreach ($users as $id=>$name) { if ($id != $uid) {
  //   echo "<div class='urow'>";
  //   // (C1) USER ID & NAME
  //   echo "<div class='uname'>$id) $name</div>";
 
  //   // (C2) BLOCK/UNBLOCK
  //   if (isset($friends['b'][$id])) {
  //     echo "<button onclick=\"relate('unblock', $id)\">Unblock</button>";
  //   } else {
  //     echo "<button onclick=\"relate('block', $id)\">Block</button>";
  //   }
 
  //   // (C3) FRIEND STATUS
  //   // FRIENDS
  //   if (isset($friends['f'][$id])) { 
  //     echo "<button onclick=\"relate('unfriend', $id)\">Unfriend</button>";
  //   }
  //   // INCOMING FRIEND REQUEST
  //   else if (isset($requests['in'][$id])) { 
  //     echo "<button onclick=\"relate('accept', $id)\">Accept Friend</button>";
  //   }
  //   // OUTGOING FRIEND REQUEST
  //   else if (isset($requests['out'][$id])) { 
  //     echo "<button onclick=\"relate('cancel', $id)\">Cancel Add</button>";
  //   }
  //   //STRANGERS
  //   else { 
  //     echo "<button onclick=\"relate('add', $id)\">Add Friend</button>";
  //   }
  //   echo "</div>";
  // }}
          ?></td>
      </tr>

    </table>
<?php 
  }
    
    
    else{
      echo "Name Does not exist";
    }


}