<!-- 再予約ページ・Model User2 -->
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
if (!$referer == "http://localhost/php/r_mypage.php"||!$referer == "http://localhost/php/r_re_booking_confirm.php") {
  header("Location:/php/r_mypage.php");
  exit;
} 
//ログアウト
if(isset($_GET["logout"])){
  $_SESSION =array();
  session_destroy();
}
//ログインしたユーザ情報を設定
if(isset($_SESSION["User"])){
  $menbers["User"]=$_SESSION["User"];
  $self_id= htmlspecialchars($menbers["User"]["id"],ENT_QUOTES,'UTF-8');
}
if(!empty($_POST['res_id'])){
  $_SESSION["res_id"]=$_POST['res_id'];
}

try{
  $user2 =new User2($host,$dbname,$user,$pass);
  $user2->connectDb();
  
  if(!empty($_POST)&&!empty($date3)) {
    if($date3 <= 6 && ($day2->format('Y-m-d')> $day1->format('Y-m-d'))) {
      $result=$user2->findBydate2($date1,$date2,$self_id);
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
<?php require("require_pages.php/r_header.php");?>
<div class="wrap">
  <div class="title">
      <h2>予約変更</h2>
  </div>
  <?php if(!empty($_POST)) :?>
    <div class="mr">
      <label id="check_in">到着日：<?php echo $date1; ?></label><br>
      <label id="check_out">出発日：<?php echo $date2; ?></label>
    </div>
    <?php if(!empty($result)&&(($day2->format('Y-m-d')> $day1->format('Y-m-d')))) :?>
      <div class="reservation">
        <table>
          <tr>
            <th>番号</th>
            <th>部屋</th>
            <th>タイプ</th>
            <th></th>
          </tr>
            <?php foreach($result as $row): ?>
              <form action="r_re_booking_confirm.php" method="post">
              <tr>
                <input type="hidden" name="check_in" value="<?php echo $date1; ?>">
                <input type="hidden" name="check_out" value="<?php echo $date2; ?>">
                <input type="hidden" name="rooms_id" value="<?php echo $rooms_id=htmlspecialchars($row['id'],ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="room" value="<?php echo $room=htmlspecialchars($row['room'],ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="beds" value="<?php echo $beds=htmlspecialchars($row['beds'],ENT_QUOTES,'UTF-8'); ?>">
                <td><?=$rooms_id?></td>
                <td><?=$room?></td>
                <td><?=$beds?></td>
                <td>
                  <input type="submit" value="変更する" id="btn">
                </td>
              </tr>
            </form>
          <?php endforeach; ?>
        </table>
      </div>
      <?php elseif(!empty($_POST['check_in']) && !empty($_POST['check_out']) && $date3 <= 6 && ($day2->format('Y-m-d')> $day1->format('Y-m-d')) && empty($result)) :?>
        <div class="error"><?php echo "満室です" ?></div>
        <?php elseif(($day2->format('Y-m-d')> $day1->format('Y-m-d')) && $date3 > 6) :?>
        <div class="error"><?php echo "長期滞在は6泊までです" ?></div>
        <?php elseif($day2->format('Y-m-d')< $day1->format('Y-m-d')) :?>
        <div class="error"><?php echo "日付を正しく入力してください" ?></div>
        <?php else :?>
        <div class="error"><?php echo "日付を正しく入力してください" ?></div>
    <?php endif;?>
  <?php endif;?>
  <div class="check_date">
    <form action="r_re_booking.php" method="post">
      <table>
        <tr>
          <th>到着日：</th><td><input type="date" min='<?php echo $datein; ?>' max='<?php echo $datein_limit; ?>' name="check_in" class="date_sec" value="<?php if($_POST['check_in']){echo $date1; } ?>"></td>
        </tr>
        <tr>
          <th>出発日：</th><td><input type="date" min='<?php echo $dateout; ?>' max='<?php echo $dateout_limit; ?>' name="check_out" class="date_sec" value="<?php if($_POST['check_out']){echo $date2; } ?>"></th>
        </tr>
      </table>
      <input type="submit" class='check_submit' value="空室再検索" id="btn">
    </form>
  </div>
  <div class="mr"><a href="r_mypage.php">マイページへ</a></div>
</div>
<?php require("require_pages.php/r_footer.php");?>
</body>
</html>