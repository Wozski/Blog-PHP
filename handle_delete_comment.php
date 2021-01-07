<?php
  require_once("conn.php");
  require_once("utils.php");
  session_start();
  if (empty($_GET["id"])) 
    {
    //header("Location: index.php?errCode=1");
    die("資料不齊全");
    }
  $id = $_GET['id'];
  if (!empty($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $title = $_POST['title'];
    $content = $_POST["content"];
    $sql = "update comments set is_deleted=1 where id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
  }
  if (!$result) {
    die($conn->error);
  }
  header("Location: index.php");
?>