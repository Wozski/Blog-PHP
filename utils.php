<?php 
require_once("conn.php");
// 從 database 拿 nickname
/*
function getUserFromUsername ($username) {
	// 全域性變數在 function 要使用 conn 需要宣告
	global $conn;
	$sql = sprintf("select * from users where username='%s'",
		$username
    );
    $result = $conn->query($sql);
    $rows = $result->fetch_assoc();
    return $rows;
}*/

// 產生隨機的 token
function generateToken() {
	$s = '';
	for ($i=1; $i<16; $i++) {
		$s .= chr(rand(65,90));
	}
	return $s;
}
/*
function getUserFromToken($token) {
// 全域性變數在 function 要使用 conn 需要宣告
	global $conn;
  $sql = sprintf(
    "select username from tokens where token='%s'",
  	$token,
  );
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $username = $row["username"];
  $sql = sprintf(
    "select * from users where username='%s'",
  	$username,
  );
  $result = $conn->query($sql);
  $row = $result->fetch_assoc(); //nickname, id, username
  return $row;
};*/

// 從 Session 拿 username
function getUserFromSession($username) {
	global $conn;
	 $sql = sprintf(
    "select * from users where username='%s'",
  	$username
  );
  $result = $conn->query($sql);
  $row = $result->fetch_assoc(); //nickname, id, username, role
  return $row;
};

function escape($str) {
  return htmlspecialchars($str, ENT_QUOTES);
}
?>