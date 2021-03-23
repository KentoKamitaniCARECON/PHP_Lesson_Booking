<!-- 予約作成の完了ページ・Model User2 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user2.php';

//セッションとログイン
if(!isset($_SESSION["User"])) {
  header("Location:/php/r_login_case1.php");
  exit;
}
//ダイレクトアクセス対策
$referer=$_SERVER['HTTP_REFERER'];
if (!$referer == "localhost/php/r_booking_confirm.php"||!$referer == "localhost/php/r_re_booking_confirm.php") {
  header("Location:/php/r_top.php");
  exit;
} 
//ログアウト
if(isset($_GET["logout"])){
  $_SESSION =array();
  session_destroy();
}
//トークンセッションが空は戻れない
if(empty($_SESSION['token'])){
  header("Location:/php/r_top.php");
  exit;
}
//ログインしたユーザ情報を設定
if(isset($_SESSION["User"])){
  $menbers["User"]=$_SESSION["User"];
}
if(isset($_SESSION["res_id"])){
  $res["id"]=$_SESSION["res_id"];
}
//トークン　二重送信対策
$token = isset($_POST["token"]) ? $_POST["token"] : "";
$session_token = isset($_SESSION["token"]) ? $_SESSION["token"] : "";

try{
  $user2 =new User2($host,$dbname,$user,$pass);
  $user2->connectDb();

  if(!empty($_POST['requests'])){
    $requests=htmlspecialchars($_POST['requests'],ENT_QUOTES,'UTF-8');
    $_POST['requests']=$requests;
  }

  //登録
  if(!empty($_SESSION["res_id"])){
    if(($token != "" && $token == $session_token)){
      $result=$user2->beforeChk($_POST);
        if(empty($result)){
        $user2->edit($_POST);
        unset($_SESSION["token"]);
      }
    }
  }else{
    if($token != "" && $token == $session_token) {
      $result=$user2->beforeChk($_POST);
      if(empty($result)){
      $user2->add($_POST);
      unset($_SESSION["token"]);
      }
    }
  }
}
catch (PDOException $e) {
  print "エラー！！".$e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>sample</title>
<link rel="stylesheet" type="text/css" href="css/base.css">
<link rel="stylesheet" type="text/css" href="css/book.css">
</head>
<body>
<div id="header"></div>
<div class="wrap">
  <div class="title">
    <?php if(isset($_SESSION["res_id"])&&!empty($_POST['id'])){
        if($res["id"]==$_POST['id']) {
      echo "<h2>ご変更完了</h2>";}
    }else{ echo "<h2>ご予約完了</h2>";} ?>
  </div>
  <div class="mr">
    <h2><?php echo $menbers_name=htmlspecialchars($menbers['User']['name'],ENT_QUOTES,'UTF-8')."様"; ?></h2>
    <h2>ご予約ありがとうございます。</h2>
    <h2><a href='r_mypage.php'>MY PAGE</a>よりご予約を確認できます。</h2>
  </div>
</div>
<?php require("require_pages.php/r_footer.php");?>
</body>
</html>