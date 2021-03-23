<!-- 会員登録ページ・Model User1 -->
<?php 
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';
require_once dirname(__FILE__).'/validation_escape/valid_id_pas.php';
require_once dirname(__FILE__).'/validation_escape/valid_menber_info.php';

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
<link rel="stylesheet" type="text/css" href="css/book.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="validation_escape/valid_js_registration.js"></script>
</head>
<body>
<div id=header></div>
  <div class="wrap">
    <div class="title">
      <h2>会員登録</h2>
    </div>
    <!-- //バリデーション結果通知 -->
    <div class="error1">
      <?php if(!empty($err2)){ 
        foreach($err2 as $msg2){
          echo $msg2.'<br>';}} ?>
      <?php if(!empty($err1)){ 
        foreach($err1 as $msg1){
          echo $msg1.'<br>';}} ?>
    </div>
    <div class='editform'>
      <form action="r_registration_confirm.php" method="post">
        <table>
          <tr>
            <th id="name">氏名</th>
            <td><input type="text" name="name" size="40" value="<?php if(!empty($escape_name)){echo $escape_name;}?>"></td>
          </tr>
          <tr>
            <th id="address">住所</th>
            <td><input type="text" name="address" size="40" value="<?php if(!empty($escape_address)){ echo $escape_address;}?>"></td>
          </tr>
          <tr>
            <th id="tel">電話</th>
            <td><input type="text" name="tel" size="40" value="<?php if(!empty($escape_tel)){ echo $escape_tel;}?>"></td>
          </tr>
          <tr>
            <th id="birth">誕生日</th>
            <td><input type="date" min="<?php echo $set_birth; ?>" max="<?php echo date('Y-m-d'); ?>" name="birth" class="date_sec3" value="<?php if(!empty($escape_birth)){ echo $escape_birth;}else{ echo '2000-01-01'; } ?>"></td>
          </tr>
          <tr>
            <th id="email">メール</th>
            <td><input type="text" name="email" size="40" value="<?php if(!empty($escape_email)){ echo $escape_email;}?>"></td>
          </tr>
          <tr>
            <th id="password">パスワード</th>
            <td><input type="password" name="password" size="40" value="<?php if(!empty($escape_password)){ echo $escape_password;}?>"></td>
          </tr>
        </table>
      <input type="submit" id="submit" value="確認" id="btn">
    </form>
  </div>
  <div class="mr">
    <a href="r_login_case1.php">ログイン<a><br>
    <a href="r_login_case2.php">ID/パスワード忘れた<a>
  </div>
</div>
<?php require("require_pages.php/r_footer.php"); ?>
</body>
</html>