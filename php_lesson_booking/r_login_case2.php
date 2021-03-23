<!-- IDとパスワード失念時のログイン・Model User -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';
require_once dirname(__FILE__).'/validation_escape/valid_menber_info.php';

try{
  $user =new User($host,$dbname,$user,$pass);
  $user->connectDb();

  if(!empty($_POST) && empty($err2)){
    $_POST['name']=$escape_name;
    $_POST['address']=$escape_address;
    $_POST['tel']=$escape_tel;
    $_POST['birth']=$escape_birth;
    $result=$user->login2($_POST);
    if(!empty($result)) {
      $_SESSION["User"]=$result;
      header("Location:/php/r_mypage.php");
      exit;
    }else{
      $notmatch="* 登録情報が間違っています";
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
<script type="text/javascript" src="validation_escape/valid_js_login2.js"></script>
</head>
<body>
  <div id=header></div>
  <div class="wrap">
    <div class="title">
      <h2>ID・パスワードを<br>忘れた方へ</h2>
    </div>
    <div class="mr">
      <p>会員情報を入力してください。</p>
    </div>
    <!-- //バリデーション結果通知 -->
    <div class="error1">
      <?php if(!empty($err2)){ 
        foreach($err2 as $msg){
        echo $msg.'<br>';}} ?>
    </div>
    <div class="error2">
      <?php if(!empty($notmatch)){ 
        echo $notmatch;} ?>
    </div>
    <div class='editform'>
      <form action="r_login_case2.php" method="post">
        <table>
          <tr>
            <th>氏名</th>
            <td><input type="text" name="name" size="40" value="<?php if(!empty($escape_name)){echo $escape_name;}?>"></td>
          </tr>
          <tr>
            <th>住所</th>
            <td><input type="text" name="address" size="40" value="<?php if(!empty($escape_address)){ echo $escape_address;}?>"></td>
          </tr>
          <tr>
            <th>電話</th>
            <td><input type="text" name="tel" size="40" value="<?php if(!empty($escape_tel)){ echo $escape_tel;}?>"></td>
          </tr>
          <tr>
            <th>生年月日</th>
            <td><input type="date" min="<?php echo $set_birth; ?>" max="<?php echo date('Y-m-d'); ?>" name="birth" class="date_sec3" value="<?php if(!empty($escape_birth)){ echo $escape_birth;}else{ echo '2000-01-01'; } ?>"></td>
          </tr>
        </table>
        <input type="submit" id="submit" value="LOG IN" id="btn">
      </form>
    </div>
    <div class="mr">
      <a href="r_login_case1.php">ログイン<a><br>
      <a href="r_registration.php">会員登録<a><br>
    </div>
  </div>
  <?php require("require_pages.php/r_footer.php"); ?>
</body>
</html>