<?php 
session_start();
require_once("conn.php");
require_once("utils.php");

$id = $_GET['id'];

$username = NULL;
$user = NULL;
if (!empty($_SESSION["username"])) {
  $username = $_SESSION["username"];
  $user = getUserFromSession($username);
}
$stmt = $conn->prepare(
    'select * from comments where id=?'
  );
  $stmt->bind_param('i', $id);
  $result = $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
?>
<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">

  <title>部落格</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="normalize.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="wrapper navbar__wrapper">
      <div class="navbar__site-name">
        <a href='index.php'>Who's Blog</a>
      </div>
      <ul class="navbar__list">
        <div>
          <li><a href="list.php">文章列表</a></li>
          <li><a href="#">分類專區</a></li>
          <li><a href="#">關於我</a></li>
        </div>
        <div>
          <li><a href="list.php">回到上一頁</a></li>
          <?php if (!empty($username)) {?>  
          <li><a href="admin.php">管理後台</a></li>
          <li><a href="handle_logout.php">登出</a></li>
          <?php } else {?>
          <li><a href="handle_logout.php">登入</a></li>
          <?php }?>
        </div>
      </ul>
    </div>
  </nav>
  <section class="banner">
    <div class="banner__wrapper">
      <h1>存放技術之地</h1>
      <div>Welcome to my blog</div>
    </div>
  </section>
  <div class="container-wrapper">
    <div class="posts">
      <article class="post">
        <div class="post__header">
          <div><?php echo escape($row['title'])?></div>
          <div class="post__actions">
            <?php if (!empty($_SESSION['username'])) {?>
            <a class="post__action" href="edit.php">編輯</a>
            <?php }?>
          </div>
        </div>
        <div class="post__info">
          <?php echo escape($row['create_at'])?>
        </div>
        <div class="post__content">
          <?php echo escape($row['content'])?>
        </div>
      </article>
    </div>
  </div>
  <footer>Copyright © 2020 Who's Blog All Rights Reserved.</footer>
</body>
</html>