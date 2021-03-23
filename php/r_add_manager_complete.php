<!-- 管理者作成の完了ページ・Model User1 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';
require_once dirname(__FILE__).'/validation_escape/valid_id_pas.php';
require_once dirname(__FILE__).'/validation_escape/valid_menber_info.php';

//ダイレクトアクセス対策
$referer=$_SERVER['HTTP_REFERER'];
if (!$referer == "localhost/php/r_add_manager.php") {
  header("Location:/php/r_login_case1.php");
  exit;
} 
//ログアウト
if(isset($_GET["logout"])) {
  $_SESSION =array();
  session_destroy();
}
//セッションとログイン
if(!isset($_SESSION["User"])) {
  header("Location:/php/r_login_case1.php");
  exit;
}
//ログインしたユーザ情報を設定
if(isset($_SESSION["User"])){
  $menbers["User"]=$_SESSION["User"];
}

try{
  $user =new User($host,$dbname,$user,$pass);
  $user->connectDb();

  if(isset($_POST)){
    if(empty($err1)&&empty($err2)) {
      $result=$user->findbyEmail($escape_email);
      if(empty($result)) {
        $arr['email']=$escape_email;
        $arr['password']=$hash_pass;
        $arr['name']=$escape_name;
        $arr['address']=$escape_address;
        $arr['tel']=$escape_tel;
        $arr['birth']=$escape_birth;
        $user->addManager($arr);
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
</head>
<body>
<?php require("require_pages.php/r_header.php"); ?>
<div class="wrap">
  <div class="title">
    <h2>登録</h2>
  </div>
  <div id="add_manager">
    <?php if(empty($err1)&&empty($err2)&&empty($result)):?>
      <p>新規管理者の登録成功</p>
      <p>管理者<?php echo $escape_name;?>さんでログインし直すには<a href="r_login_case1.php">コチラ</a>をクリック</p>
    <?php else:?>
      <p>新規管理者の登録失敗</p>
  </div>
    <!-- //バリデーション結果通知 -->
    <?php if(!empty($result)):?>
      <div class="error2">
        <p>このメールアドレスは既に使用されている為、登録できません。</p>
      </div>
    <?php endif;?>
    <?php if(!empty($err2)||!empty($err1)):?>
      <div class="error1">
        <p>入力内容に不備があります。下記より入力画面へ戻って下さい。</p>
      </div>
    <?php endif;?>
    <form action="r_add_manager.php" method="post">
      <input type="hidden" name="name" value="<?php if(!empty($escape_name)){echo $escape_name;}?>">
      <input type="hidden" name="address" value="<?php if(!empty($escape_address)){ echo $escape_address;}?>">          <input type="hidden" name="tel" value="<?php if(!empty($escape_tel)){ echo $escape_tel;}?>">
      <input type="hidden" name="birth" value="<?php if(!empty($escape_birth)){ echo $escape_birth;}else{ echo '2000-01-01'; } ?>">
      <input type="hidden" name="email" value="<?php if(!empty($escape_email)){ echo $escape_email;}?>">
      <input type="hidden" name="password" value="<?php if(!empty($escape_password)){ echo $escape_password;}?>">
      <input type="submit" id="add_manager2" value="入力画面へ戻る">
    </form>
  <?php endif;?>
</div>
<?php require("require_pages.php/r_footer.php"); ?>
</body>
</html>