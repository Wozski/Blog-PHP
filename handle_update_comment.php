<?php
  require_once("conn.php");
  require_once("utils.php");
  session_start();
  $id = $_POST['id'];
  if (
    empty($_POST["content"]) || 
    empty($_POST["title"]))
    {
    //header("Location: update.php?errCode=1");
    die("資料不齊全");
  }
  if (!empty($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $title = $_POST['title'];
    $content = $_POST["content"];
    $sql = "update comments set content=?, title=? where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $content, $title, $id);
    $result = $stmt->execute();
  }
  if (!$result) {
    die($conn->error);
  }
  header("Location: index.php");
?>