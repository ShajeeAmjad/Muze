<?php
class Relation {
  // (A) CONSTRUCTOR - CONNECT TO DATABASE
  private $pdo = null;
  private $stmt = null;
  public $error = "";
  function __construct () {
    try {
      $this->pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET, 
        DB_USER, DB_PASSWORD, 
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
    } catch (Exception $ex) { exit($ex->getMessage()); }
  }

  // (B) DESTRUCTOR - CLOSE DATABASE CONNECTION
  function __destruct () {
    if ($this->stmt!==null) { $this->stmt = null; }
    if ($this->pdo!==null) { $this->pdo = null; }
  }

  // (C) HELPER FUNCTION - EXECUTE SQL QUERY
  function query ($sql, $data=null) {
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($data);
      return true;
    } catch (Exception $ex) {
      $this->error = $ex->getMessage();
      return false;
    }
  }



  //(D) SEND FRIEND REQUEST
  function request ($user1, $user2) {
    $connection = new mysqli("dbhost.cs.man.ac.uk","n80569fh","balls1235","2021_comp10120_m8");
    if ($connection->connect_error){
      die("Connection failed:" . $mysqli_connect_error());
    }
    $sql = "SELECT username FROM users WHERE userID ='".$user1 . "'";
    $result= mysqli_query($connection, $sql);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username1 = $row["username"];
      }
    }
    $sql2 = "SELECT username FROM users WHERE userID ='".$user2 . "'";
    $result= mysqli_query($connection, $sql2);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username2 = $row["username"];
      }
    }
    mysqli_close($connection);
    // (D1) CHECK IF ALREADY FRIENDS
    $this->query(
      "SELECT * FROM `Friendship` WHERE `user1`=? AND `user2`=? AND `requestAccepted`='1'",
      [$username1, $username2]
    );
    $result = $this->stmt->fetch();
    if (is_array($result)) {
      $this->error = "Already added as friends";
      return false;
    }

    // (D2) CHECK FOR PENDING REQUESTS
    $this->query(
      "SELECT * FROM `Friendship` WHERE ".
      "(`requestAccepted`='0' AND `user1`=? AND `user2`=?) OR ".
      "(`requestAccepted`='0' AND `user1`=? AND `user2`=?)",
      [$username1, $username2, $username2, $username1]
    );
    $result = $this->stmt->fetch();
    if (is_array($result)) {
      $this->error = "Already has a pending friend request";
      return false;
    }

    // (D3) ADD FRIEND REQUEST
    return $this->query(
      "INSERT INTO `Friendship` (user1, user2, requestAccepted) VALUES (?,?,0)",
      [$username1,$username2]
    );
  }

  // (E) ACCEPT FRIEND REQUEST
  function acceptReq ($user1, $user2) {

    $connection = new mysqli("dbhost.cs.man.ac.uk","n80569fh","balls1235","2021_comp10120_m8");
    if ($connection->connect_error){
      die("Connection failed:" . $mysqli_connect_error());
    }
    $sql = "SELECT username FROM users WHERE userID ='".$user1 . "'";
    $result= mysqli_query($connection, $sql);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username1 = $row["username"];
      }
    }
    $sql2 = "SELECT username FROM users WHERE userID ='".$user2 . "'";
    $result= mysqli_query($connection, $sql2);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username2 = $row["username"];
      }
    }
    mysqli_close($connection);
    // (E1) UPGRADE STATUS TO "F"RIENDS
    $this->query(
      "UPDATE `Friendship` SET `requestAccepted`='1' WHERE `requestAccepted`='0' AND `user1`=? AND `user2`=?",
      [$username1, $username2]
    );
    if ($this->stmt->rowCount()==0) {
      $this->error = "Invalid friend request";
      return false;
    }

    // // (E2) ADD RECIPOCAL RELATIONSHIP
    // return $this->query(
    //   "INSERT INTO `Friendship` (`user1`, `user2`, `requestAccepted`) VALUES (?,?,'1')",
    //   [$user2, $user1]
    // );
  }

  // (F) CANCEL FRIEND REQUEST
  function cancelReq ($user1, $user2) {
    $connection = new mysqli("dbhost.cs.man.ac.uk","n80569fh","balls1235","2021_comp10120_m8");
    if ($connection->connect_error){
      die("Connection failed:" . $mysqli_connect_error());
    }
    $sql = "SELECT username FROM users WHERE userID ='".$user1 . "'";
    $result= mysqli_query($connection, $sql);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username1 = $row["username"];
      }
    }
    $sql2 = "SELECT username FROM users WHERE userID ='".$user2 . "'";
    $result= mysqli_query($connection, $sql2);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username2 = $row["username"];
      }
    }
    mysqli_close($connection);
    return $this->query(
      "DELETE FROM `Friendship` WHERE `requestAccepted`='0' AND `user1`=? AND `user2`=?",
      [$username1, $username2]
    );
  }

  // (G) UNFRIEND
  function unfriend ($user1, $user2) {
    $connection = new mysqli("dbhost.cs.man.ac.uk","n80569fh","balls1235","2021_comp10120_m8");
    if ($connection->connect_error){
      die("Connection failed:" . $mysqli_connect_error());
    }
    $sql = "SELECT username FROM users WHERE userID ='".$user1 . "'";
    $result= mysqli_query($connection, $sql);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username1 = $row["username"];
      }
    }
    $sql2 = "SELECT username FROM users WHERE userID ='".$user2 . "'";
    $result= mysqli_query($connection, $sql2);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username2 = $row["username"];
      }
    }
    mysqli_close($connection);
    return $this->query(
      "DELETE FROM `Friendship` WHERE ".
      "(`requestAccepted`='1' AND `user1`=? AND `user2`=?) OR ".
      "(`requestAccepted`='1' AND `user1`=? AND `user2`=?)",
      [$username1, $username2, $username2, $username1]
    );
  }


  // (I) GET FRIEND REQUESTS
  function getReq ($uid) {
    //(I1) GET OUTGOING FRIEND REQUESTS (FROM USER TO OTHER PEOPLE)
    $connection = new mysqli("dbhost.cs.man.ac.uk","n80569fh","balls1235","2021_comp10120_m8");
    if ($connection->connect_error){
      die("Connection failed:" . $mysqli_connect_error());
    }
    $sql = "SELECT username FROM users WHERE userID ='".$uid . "'";
    $result= mysqli_query($connection, $sql);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username = $row["username"];
      }
    }

    mysqli_close($connection);
    $req = ["in"=>[], "out"=>[]];
    $this->query(
      "SELECT * FROM `Friendship` WHERE `requestAccepted`='0' AND `user1`=?",
      [$username]
    );
    while ($row = $this->stmt->fetch()) { $req['out'][$row['user2']] = $row['since']; }

    // (I2) GET INCOMING FRIEND REQUESTS (FROM OTHER PEOPLE TO USER)
    $this->query(
      "SELECT * FROM `Friendship` WHERE `requestAccepted`='0' AND `user2`=?", [$username]
    );
    while ($row = $this->stmt->fetch()) { $req['in'][$row['user1']] = $row['since']; }
    return $req;
  }

  // (J) GET FRIENDS & FOES (BLOCKED)
  function getFriends ($uid) {
     $connection = new mysqli("dbhost.cs.man.ac.uk","n80569fh","balls1235","2021_comp10120_m8");
    if ($connection->connect_error){
      die("Connection failed:" . $mysqli_connect_error());
    }
    $sql = "SELECT username FROM users WHERE userID ='".$uid ."'";
    $result= mysqli_query($connection, $sql);
    if(mysqli_num_rows($result)>0){
      while($row=mysqli_fetch_assoc($result)){
        $username = $row["username"];
      }
    }
    $connection -> close();
    // (J1) GET FRIENDS
    $friends = ["1"=>[]];
    $this->query(
      "SELECT * FROM `Friendship` WHERE `requestAccepted`='1' AND `user1`=?", [$username]
    );
    while ($row = $this->stmt->fetch()) { $friends["1"][$row['user2']] = $row['since']; }

    // (J2) GET FOES
    // $this->query(
    //   "SELECT * FROM `Friendship` WHERE `requestAccepted`='B' AND `user1`=?", [$uid]
    // );
    // while ($row = $this->stmt->fetch()) { $friends["b"][$row['user2']] = $row['since']; }
    // return $friends;
  }

  // (K) GET ALL USERS

    function getUsers () {
    $this->query("SELECT * FROM `users`");
    $users = [];
    while ($row = $this->stmt->fetch()) { $users[$row['userID']] = $row['username']; }
    return $users;
  }

}

// (L) DATABASE SETTINGS - CHANGE TO YOUR OWN!
define("DB_HOST", "dbhost.cs.man.ac.uk");
define("DB_NAME", "2021_comp10120_m8");
define("DB_CHARSET", "utf8");
define("DB_USER", "n80569fh");
define("DB_PASSWORD", "balls1235");

// (M) NEW RELATION OBJECT
$REL = new Relation();