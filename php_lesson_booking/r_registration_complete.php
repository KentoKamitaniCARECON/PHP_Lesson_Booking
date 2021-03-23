<!-- 会員登録の完了ページ・Model User1 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';

//ダイレクトアクセス対策
$referer=$_SERVER['HTTP_REFERER'];
if (!$referer == "localhost/php/r_registration_confirm.php") {
  header("Location:/php/r_login_case1.php");
  exit;
} 

try{
  $user =new User($host,$dbname,$user,$pass);
  $user->connectDb();
  
  if(!empty($_POST)) {
    $user->add($_POST);
    $result=$user->login($_POST);
    if(!empty($result)) {
      $_SESSION["User"]=$result;
    }else{
      header("Location:/php/r_login_case1.php");
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  setTimeout(function(){
    window.location.href = 'r_top.php';
  }, 5*1000);
</script>
</head>
<body>
<div id="header"></div>
<div class="wrap">
  <div class="title">
    <h2>登録完了</h2>
  </div>
  <div class="mr">
    <h2><?php echo $menbers_name=htmlspecialchars($_SESSION["User"]['name'],ENT_QUOTES,'UTF-8')."様"; ?></h2>
    <h2>ご登録ありがとうございます。</h2>
    <h2>5秒後にトップ画面へ移行します</h2>
    <h2>自動で移行しない場合は<span><a href="r_top.php">コチラ</a></span>をクリック</h2>
  </div>
</div>
<?php require("require_pages.php/r_footer.php"); ?>
</body>
</html>