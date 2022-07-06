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
        DB_USER, DB_PASSWORD, [
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
  function request ($from, $to) {
    // (D1) CHECK IF ALREADY FRIENDS
    $this->query(
      "SELECT * FROM `Friendship` WHERE `user1`=? AND `user2`=? AND `requestAccepted`='F'",
      [$from, $to]
    );
    $result = $this->stmt->fetch();
    if (is_array($result)) {
      $this->error = "Already added as friends";
      return false;
    }

    // (D2) CHECK FOR PENDING REQUESTS
    $this->query(
      "SELECT * FROM `Friendship` WHERE ".
      "(`requestAccepted`='P' AND `user1`=? AND `user2`=?) OR ".
      "(`requestAccepted`='P' AND `user1`=? AND `user2`=?)",
      [$from, $to, $to, $from]
    );
    $result = $this->stmt->fetch();
    if (is_array($result)) {
      $this->error = "Already has a pending friend request";
      return false;
    }

    // (D3) ADD FRIEND REQUEST
    return $this->query(
      "INSERT INTO `Friendship` (`user1`, `user2`, `requestAccepted`) VALUES (?,?,'P')",
      [$from, $to]
    );
  }

  // (E) ACCEPT FRIEND REQUEST
  function acceptReq ($from, $to) {
    // (E1) UPGRADE STATUS TO "F"RIENDS
    $this->query(
      "UPDATE `Friendship` SET `requestAccepted`='F' WHERE `requestAccepted`='P' AND `user1`=? AND `user2`=?",
      [$from, $to]
    );
    if ($this->stmt->rowCount()==0) {
      $this->error = "Invalid friend request";
      return false;
    }

    // (E2) ADD RECIPOCAL RELATIONSHIP
    return $this->query(
      "INSERT INTO `Friendship` (`user1`, `user2`, `requestAccepted`) VALUES (?,?,'F')",
      [$to, $from]
    );
  }

  // (F) CANCEL FRIEND REQUEST
  function cancelReq ($from, $to) {
    return $this->query(
      "DELETE FROM `Friendship` WHERE `requestAccepted`='P' AND `user1`=? AND `user2`=?",
      [$from, $to]
    );
  }

  // (G) UNFRIEND
  function unfriend ($from, $to) {
    return $this->query(
      "DELETE FROM `Friendship` WHERE ".
      "(`requestAccepted`='F' AND `user1`=? AND `user2`=?) OR ".
      "(`requestAccepted`='F' AND `user1`=? AND `user2`=?)",
      [$from, $to, $to, $from]
    );
  }

  // (H) BLOCK & UNBLOCK
  function block ($from, $to, $blocked=true) {
    // (H1) BLOCK
    if ($blocked) { return $this->query(
      "INSERT INTO `Friendship` (`user1`, `user2`, `requestAccepted`) VALUES (?,?,'B')",
      [$from, $to]
    ); }

    // (H2) UNBLOCK
    else { return $this->query(
      "DELETE FROM `Friendship` WHERE `user1`=? AND `user2`=? AND `requestAccepted`='B'",
      [$from, $to]
    ); }
  }

  // (I) GET FRIEND REQUESTS
  function getReq ($uid) {
    // (I1) GET OUTGOING FRIEND REQUESTS (FROM USER TO OTHER PEOPLE)
    $req = ["in"=>[], "out"=>[]];
    $this->query(
      "SELECT * FROM `Friendship` WHERE `requestAccepted`='P' AND `user1`=?",
      [$uid]
    );
    while ($row = $this->stmt->fetch()) { $req['out'][$row['user2']] = $row['since']; }

    // (I2) GET INCOMING FRIEND REQUESTS (FROM OTHER PEOPLE TO USER)
    $this->query(
      "SELECT * FROM `Friendship` WHERE `requestAccepted`='P' AND `user2`=?", [$uid]
    );
    while ($row = $this->stmt->fetch()) { $req['in'][$row['user1']] = $row['since']; }
    return $req;
  }

  // (J) GET FRIENDS & FOES (BLOCKED)
  function getFriends ($uid) {
    // (J1) GET FRIENDS
    $friends = ["f"=>[], "b"=>[]];
    $this->query(
      "SELECT * FROM `Friendship` WHERE `requestAccepted`='F' AND `user1`=?", [$uid]
    );
    while ($row = $this->stmt->fetch()) { $friends["f"][$row['user2']] = $row['since']; }

    // (J2) GET FOES
    $this->query(
      "SELECT * FROM `Friendship` WHERE `requestAccepted`='B' AND `user1`=?", [$uid]
    );
    while ($row = $this->stmt->fetch()) { $friends["b"][$row['user2']] = $row['since']; }
    return $friends;
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