<?php 
session_start();
require_once("conn.php");
require_once("utils.php");

$username = NULL;
$user = NULL;
if (!empty($_SESSION["username"])) {
  $username = $_SESSION["username"];
  $user = getUserFromSession($username);
}

$page = 1;
if (!empty($_GET['page'])) {
  $page = intval($_GET['page']);
}
$items_per_page = 5;
$offset = ($page - 1) * $items_per_page;

$stmt = $conn->prepare(
  'select '. 
    'C.id as id, C.content as content, C.title as title, '.
    'C.create_at as create_at, U.nickname as nickname, U.username as username '.
    'from comments as C '.
    'left join users as U on C.username = U.username '.
    'where C.is_deleted is null '.    
    'order by C.id desc '.
    'limit ? offset ? '
  );
  $stmt->bind_param('ii', $items_per_page, $offset);
  $result = $stmt->execute();
  if (!$result) {
    die ("Error:" . $conn->error);
  }
  //拿出結果
  $result = $stmt->get_result();

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
          <li><a class="hide" href="register.php">註冊</a></li>
          <?php if (!empty($_SESSION['username'])) {?>
          <li><a href="edit.php">新增文章</a></li>
          <li><a href="admin.php">管理後台</a></li> 
          <li><a href="handle_logout.php">登出</a></li>
          <?php } else {?>
          <li><a href="login.php">登入</a></li>  
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
      <?php while ($row = $result->fetch_assoc()) {?>
      <article class="post">
        <div class="post__header">
          <?php echo escape($row['title'])?>
            <?php if (!empty($username)) {?>
              <div class="post__actions">
                <a class="post__action" href="update.php?id=<?php echo escape($row['id'])?>">編輯</a>
              </div>
            <?php }?>
            </div>      
            <div class="post__info">
            <?php echo escape($row['create_at']);?>         
            </div>
            <div class="post__content">
            <?php echo substr(escape($row['content']), 0, 200);?>
            </div>
            <a class="btn-read-more" href="blog.php?id=<?php echo escape($row['id'])?>">READ MORE</a>
      </article>
          <?php }?>
          <?php 
            $stmt = $conn->prepare(
              'select count(id) as count from comments where is_deleted is null'
            );
            $items_per_page = 5;
            $result = $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['count'];
            // ceil 無條件進位
            $total_page = ceil($count / $items_per_page);
          ?>
          <div class="page-info">
            <div class="page-total">總共有 <?php echo $count ?> 筆資料， 
            <span><?php echo $page?> / <?php echo $total_page ?></span> 分頁
            </div>
            <div class="page-btn">
            <?php if ($page != 1) { ?>
              <a href="index.php?page=1">首頁</a>
              <a href="index.php?page=<?php echo $page - 1 ?>">上一頁</a>
              </div>
            <?php } ?>
            <?php if ($page != $total_page) {?>
              <a href="index.php?page=<?php echo $page + 1 ?>">下一頁</a>
              <a href="index.php?page=<?php echo $total_page ?>">最末頁</a>
            <?php }?>
            </div>
          </div>
    </div>
  </div>
  <footer>Copyright © 2020 Who's Blog All Rights Reserved.</footer>
</body>
</html>