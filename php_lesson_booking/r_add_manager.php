<!-- 管理者作成ページ・Model User1 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';
require_once dirname(__FILE__).'/validation_escape/valid_id_pas.php';
require_once dirname(__FILE__).'/validation_escape/valid_menber_info.php';

//セッションとログイン
if(!isset($_SESSION["User"])) {
  header("Location:/php/r_login_case1.php");
  exit;
}
//ログアウト
if(isset($_GET["logout"])) {
  $_SESSION =array();
  session_destroy();
}
//ログインしたユーザ情報を設定
if(isset($_SESSION["User"])){
  $menbers["User"]=$_SESSION["User"];
}

try{
  $user =new User($host,$dbname,$user,$pass);
  $user->connectDb();

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
<link rel="stylesheet" type="text/css" href="css/detail.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="validation_escape/valid_js_manager_registration.js"></script>
</head>
<body>
<?php require("require_pages.php/r_header.php"); ?>
  <div class="wrap">
    <div class="title">
      <h2>管理者作成</h2>
    </div>
    <div class='editform'>
    <!-- //バリデーション結果通知 -->
    <div class="error1">
    <?php if(!empty($err2)){ 
      foreach($err2 as $msg2){
        echo $msg2.'<br>';}} ?>
    <?php if(!empty($err1)){ 
      foreach($err1 as $msg1){
        echo $msg1.'<br>';}} ?>
    </div>
    <form action="r_add_manager_complete.php" method="post">
      <table>
        <tr>
          <th id="name">氏名</th>
          <td><input type="text" name="name" size="50" value="<?php if(!empty($escape_name)){echo $escape_name;}?>"></td>
        </tr>
        <tr>
          <th id="address">住所</th>
          <td><input type="text" name="address" size="50" value="<?php if(!empty($escape_address)){ echo $escape_address;}?>"></td>
        </tr>
        <tr>
          <th id="tel">電話</th>
          <td><input type="text" name="tel" size="50" value="<?php if(!empty($escape_tel)){ echo $escape_tel;}?>"></td>
        </tr>
        <tr>
          <th id="birth">誕生日</th>
          <td><input type="date" min="<?php echo $set_birth; ?>" max="<?php echo date('Y-m-d'); ?>" name="birth" class="date_sec3" value="<?php if(!empty($escape_birth)){ echo $escape_birth;}else{ echo '2000-01-01'; } ?>"></td>
        </tr>
        <tr>
          <th id="email">メールアドレス</th>
          <td><input type="text" name="email" size="50" value="<?php if(!empty($escape_email)){ echo $escape_email;}?>"></td>
        </tr>
        <tr>
          <th id="password">パスワード</th>
          <td><input type="password" name="password" size="50" value="<?php if(!empty($escape_password)){ echo $escape_password;}?>"></td>
        </tr>
      </table>
      <input type="submit" class="edit_submit" value="登 録" id="btn" onClick="if(!confirm('内容に不備はありませんか？')) return false;">
    </form>
  </div>
</div>
<?php require("require_pages.php/r_footer.php"); ?>
</body>
</html>