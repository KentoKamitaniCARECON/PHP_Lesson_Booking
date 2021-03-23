<!-- 再予約の確認ページ・Model User2 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user2.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';

//セッションとログイン
if(!isset($_SESSION["User"])) {
  header("Location:/php/r_login_case1.php");
  exit;
}
//ダイレクトアクセス対策
$referer=$_SERVER['HTTP_REFERER'];
if (!$referer == "localhost/php/r_re_booking.php") {
  header("Location:/php/mypage.php");
  exit;
} 
//トークン　二重送信対策
$token = uniqid('', true);
$_SESSION['token'] = $token;
//ログアウト
if(isset($_GET["logout"])){
  $_SESSION =array();
  session_destroy();
}
//ログインしたユーザ情報を設定
if(isset($_SESSION["User"])){
  $menbers["User"]=$_SESSION["User"];
}
if(isset($_SESSION["res_id"])){
  $res["id"]=$_SESSION["res_id"];
}

try{
  $user2 =new User2($host,$dbname,$user,$pass);
  $user2->connectDb();

  if(isset($_SESSION["res_id"])){
    $res_id=$user2->findById($res["id"]);
    $res_requests=htmlspecialchars($res_id['requests'],ENT_QUOTES,'UTF-8');
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
</head>
<body>
<?php require("require_pages.php/r_header.php"); ?>
  <div class="wrap">
    <div class="title">
      <h2>予約確認</h2>
    </div>
    <div class="mr">
      <?php if($menbers["User"]["role"]!=3){
         echo $menbers_name=htmlspecialchars($menbers['User']['name'],ENT_QUOTES,'UTF-8')."様<br>予約はまだ変更されていません。" ; }
         else{ echo "予約の変更を受付できません。" ;} ?>
    </div>
    <?php if($menbers["User"]["role"]!=3): ?>
    <div id='res_conf'>
      <form action="r_booking_complete.php" method="post">
      <table>
        <!-- トークンPOST送信 -->
        <input type="hidden" name="token" value="<?php echo $token;?>">
        <input type="hidden" name="id" value="<?php if(isset($res['id'])){ echo $res['id'];}?>">
        <input type="hidden" name="check_in" value="<?php echo $date1; ?>">
        <tr>
          <th>到着日：</th><td><?php echo $date1; ?></td>
        </tr>
        <input type="hidden" name="check_out" value="<?php echo $date2; ?>">
        <tr>
          <th>出発日：</th><td><?php echo $date2; ?></td>
        </tr>
        <input type="hidden" name="rooms_id" value="<?php echo $rooms_id=htmlspecialchars($_POST['rooms_id'],ENT_QUOTES,'UTF-8'); ?>">
        <tr>
          <th>部屋番号：</th><td><?php echo $rooms_id=htmlspecialchars($_POST['rooms_id'],ENT_QUOTES,'UTF-8'); ?></td>
        </tr>
        <input type="hidden" name="menbers_id" value="<?php echo $menbers_id=htmlspecialchars($menbers["User"]["id"],ENT_QUOTES,'UTF-8'); ?>">
        <tr>
          <th>部屋：</th><td><?php echo $room=htmlspecialchars($_POST["room"],ENT_QUOTES,'UTF-8'); ?></td>
        </tr>
        <tr>
          <th>タイプ：</th><td><?php echo $beds=htmlspecialchars($_POST["beds"],ENT_QUOTES,'UTF-8'); ?></td>
        </tr>
        <tr>
          <th>ご要望:</th><td><textarea id="textbox" name="requests" rows="8"><?php if(!empty($res_requests)){ echo $res_requests;}?></textarea></td>
        </tr>
      </table>
      <input type="submit" class="edit_submit" value="変 更" id="btn" onClick="if(!confirm('本当に変更しますか？')) return false;">
    </form>
    <form action="r_re_booking.php" method="post">
      <input type="hidden" name="check_in" value="<?php echo $date1; ?>">
      <input type="hidden" name="check_out" value="<?php echo $date2; ?>">
      <input type="submit" class="edit_submit" value="戻 る" id="btn"onClick="if(!confirm('前のページへ戻りますか？')) return false;">
    </form>
  </div>
</div>
<?php else:?>
  <div class="mr">
    <h2><?php echo $menbers_name=htmlspecialchars($menbers['User']['name'],ENT_QUOTES,'UTF-8');?>様　お電話にて直接お問い合わせ下さい。</h2>
    <h2><a href="r_top.php">トップ画面へ</a></h2>
  </div>
<?php endif;?>
<?php require("require_pages.php/r_footer.php");?>
</body>
</html>