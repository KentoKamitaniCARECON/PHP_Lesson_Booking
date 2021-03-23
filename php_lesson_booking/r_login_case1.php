<!-- Mailとパスワードによるログインフォーム・Model User -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/valid_id_pas.php';

try{
$user =new User($host,$dbname,$user,$pass);
$user->connectDb();

if(!empty($_POST)&&empty($err1)){
  $duplicate=$user->findbyEmail($escape_email);
  if(!empty($duplicate)) {
      $dup_pass=$duplicate['0']['password'];
    if(password_verify($escape_password,$dup_pass)){
      $id= htmlspecialchars($duplicate['0']['id'],ENT_QUOTES,'UTF-8');
      $result=$user->findById($id);
      $_SESSION["User"]=$result;
      header("Location:/php/r_top.php");
      exit;
    }else{
      $notmatch="* パスワードが間違っています";
    }
  }else{
    $notmatch="* IDが間違っています";
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
<script type="text/javascript" src="validation_escape/valid_js_login1.js"></script>
</head>
<body>
<div id=header></div>
<div class="wrap">
  <div class="title">
    <h2>ログイン</h2>
  </div>
  <div class="mr">
    <p>ログインは以下にmailとパスワードを入力してください。</p>
  </div>
  <!-- //バリデーション結果通知 -->
  <div class="error1">
    <?php if(!empty($err1)){ 
      foreach($err1 as $msg){
        echo $msg.'<br>';}} ?>
  </div>
  <div class="error2">
    <?php if(!empty($notmatch)){ 
      echo $notmatch;} ?>
  </div>
  <div class='editform'>
    <form action="r_login_case1.php" method="post">
      <table>
        <tr>
          <th>mail</th>
        </tr>
        <tr>
          <td><input type="text" name="email" size="40" value="<?php if(!empty($escape_email)){ echo $escape_email;}?>"></td>
        </tr>
        <tr>
          <th>パスワード</th>
        </tr>
        <tr>
          <td><input type="password" name="password" size="40" value="<?php if(!empty($escape_password)){ echo $escape_password;}?>"></td>
        </tr>
      </table>
      <input type="submit" id="submit" value="LOG IN" id="btn">
    </form>
  </div>
  <div class="mr">
    <a href="r_registration.php">会員登録<a><br>
    <a href="r_login_case2.php">ID/パスワード忘れた<a>
  </div>
</div>
<?php require("require_pages.php/r_footer.php"); ?>
</body>
</html>