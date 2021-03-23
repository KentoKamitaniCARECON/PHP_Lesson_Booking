<!-- 会員登録の確認ページ・Model User1 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/valid_id_pas.php';
require_once dirname(__FILE__).'/validation_escape/valid_menber_info.php';

//ダイレクトアクセス対策
$referer=$_SERVER['HTTP_REFERER'];
if (!$referer == "localhost/php/r_registration.php") {
  header("Location:/php/r_login_case1.php");
  exit;
} 

try{
  $user =new User($host,$dbname,$user,$pass);
  $user->connectDb();
  
  if(!empty($escape_email)) {
    $result=$user->findbyEmail($escape_email);
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
<div id=header></div>
  <div class="wrap">
    <div class="title">
      <h2>内容確認</h2>
    </div>
    <!-- //バリデーション結果通知 -->
    <div class="error1">
      <p>まだ登録は完了していません。</p>
      <?php if(!empty($err2)){ 
        foreach($err2 as $msg2){
          echo $msg2.'<br>';}} ?>
      <?php if(!empty($err1)){ 
        foreach($err1 as $msg1){
          echo $msg1.'<br>';}} ?>
    </div>
    <div class="error2">
      <?php if(!empty($result)){
        echo "このメールアドレスは既に使用されている為、登録できません。";
      }?>
    </div>
    <div class='editform'>
      <form action="r_registration_complete.php" method="post">
        <table>
          <tr>
            <th id="name">氏名</th>
            <td><?php if(!empty($escape_name)){echo $escape_name;}?><input type="hidden" name="name" value="<?php if(!empty($escape_name)){echo $escape_name;}?>"></td>
          </tr>
          <tr>
            <th id="address">住所</th>
            <td><?php if(!empty($escape_address)){ echo $escape_address;}?><input type="hidden" name="address" value="<?php if(!empty($escape_address)){ echo $escape_address;}?>"></td>
          </tr>
          <tr>
            <th id="tel">電話</th>
            <td><?php if(!empty($escape_tel)){ echo $escape_tel;}?><input type="hidden" name="tel" value="<?php if(!empty($escape_tel)){ echo $escape_tel;}?>"></td>
          </tr>
          <tr>
            <th id="birth">誕生日</th>
            <td><?php if(!empty($escape_birth)){ echo $escape_birth;}?><input type="hidden" name="birth" value="<?php if(!empty($escape_birth)){ echo $escape_birth;} ?>"></td>
          </tr>
          <tr>
            <th id="email">メールアドレス</th>
            <td><?php if(!empty($escape_email)){ echo $escape_email;}?><input type="hidden" name="email" value="<?php if(!empty($escape_email)){ echo $escape_email;}?>"></td>
          </tr>
          <tr>
            <th id="password">パスワード</th>
            <td><?php if(!empty($escape_password)){ echo $escape_password;}?><input type="hidden" name="password" value="<?php if(!empty($escape_password)){ echo $hash_pass;}?>"></td>
          </tr>
        </table>
        <?php if(empty($result)&&empty($err1)&&empty($err2)): ?>
          <input type="submit" id="submit" value="登 録" id="btn"  onClick="if(!confirm('この内容で登録しますか？')) return false;">
        <?php endif;?>
      </form>
      <form action="r_registration.php" method="post">
        <input type="hidden" name="name" value="<?php if(!empty($escape_name)){echo $escape_name;}?>">
        <input type="hidden" name="address" value="<?php if(!empty($escape_address)){ echo $escape_address;}?>">
        <input type="hidden" name="tel" value="<?php if(!empty($escape_tel)){ echo $escape_tel;}?>">
        <input type="hidden" name="birth" value="<?php if(!empty($escape_birth)){ echo $escape_birth;} ?>">
        <input type="hidden" name="email" value="<?php if(!empty($escape_email)){ echo $escape_email;}?>">
        <input type="hidden" name="password" value="<?php if(!empty($escape_password)){ echo $escape_password;}?>">
      <input type="submit" id="submit" value="入力画面へ" id="btn">
    </form>
  </div>
</div>
<?php require("require_pages.php/r_footer.php"); ?>
</body>
</html>