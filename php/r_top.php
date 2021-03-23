<!-- トップページ・Model User3 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user3.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';

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
  $user3 =new User3($host,$dbname,$user,$pass);
  $user3->connectDb();
    //削除
    if(isset($_GET["del"])) {
        $user3->delete_info($_GET["del"]);
    }
    //登録
    $err3 = array();
    if($_POST) {
      if(empty($_POST["title"])){
        $err3[] = "* タイトルを入力してください。";
      } elseif(mb_strlen($_POST["title"]) > 15) {
        $err3[] = "* タイトルの文字数制限を越えています";
      }
      if(empty($_POST["info"])){
        $err3[] = "* お知らせ内容を入力してください。";
      } 
      if(!empty($_POST['title'])&&!empty($_POST['info'])&&empty($err3)){
        $p_title= htmlspecialchars($_POST["title"],ENT_QUOTES,'UTF-8');
        $p_info= htmlspecialchars($_POST["info"],ENT_QUOTES,'UTF-8');
        $_POST["title"]=$p_title;
        $_POST['info']=$p_info;
          $user3->add_info($_POST);
          header('Location:/php/r_top.php');
      }
    }
    //参照処理
    $result=$user3->findAll_info();
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
<link rel="stylesheet" type="text/css" href="css/toppage.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php require("require_pages.php/r_header.php");?>
  <div class="wrap">
    <div class="title">
      <h2>お知らせ</h2>
    </div>
    <div class="information">
      <table>
        <?php foreach($result as $row): ?>
        <tr>
          <td><?=$created= htmlspecialchars($row["created"],ENT_QUOTES,'UTF-8');?><br>
          タイトル：<?=$title= htmlspecialchars($row["title"],ENT_QUOTES,'UTF-8');?><br>・<?=$info= htmlspecialchars($row["info"],ENT_QUOTES,'UTF-8');?></td>
          </tr>
          <?php if($menbers["User"]["role"]==1):?>
            <tr><th><a href="?del=<?=$row["id"]?>" onClick="if(!confirm('本当に削除しますか？')) return false;">削除</a></th>
          <?php endif;?>
        </tr>
        <tr><th></th></tr>
        <?php endforeach;?>
      </table>
    </div>
    <?php if($menbers["User"]["role"]==1):?>
      <div class="title">
        <h2>登 録</h2>
      </div>
      <div class="information2">
        <form action="r_top.php" method="post">
          <table>
            <tr>
              <th>タイトル</th>
            </tr>
            <tr>
              <td><input type="text" id="check_date" name="title" size=40 value="<?php if(!empty($_POST['title'])){echo$p_title= htmlspecialchars($_POST["title"],ENT_QUOTES,'UTF-8');}?>"></td>
            </tr>
            <tr>
              <th>お知らせ内容</th>
            </tr>
            <tr>
              <td><input type="text" id="check_date" name="info" size=40 value="<?php if(!empty($_POST['info'])){echo$p_info= htmlspecialchars($_POST["info"],ENT_QUOTES,'UTF-8');}?>"></td>
            </tr>
          </table>
          <input type="submit" value="登 録" id="btn" onClick="if(!confirm('登録しますか？')) return false;">
        </form>
      </div>
      <!-- //エラー表示 -->
      <div class="error1">
      <?php if(!empty($err3)){ 
        foreach($err3 as $msg){
          echo $msg.'<br>';}} ?>
      </div>
      <?php endif;?>
      <div class="title">
        <h2>空室検索</h2>
      </div>
      <div class="information2">
        <form action="r_booking.php" method="post">
          <table>
            <tr>
              <th>到着日</th>
            </tr>
            <tr>
              <td><input type="date" id="check_date" min='<?php echo $datein; ?>' max='<?php echo $datein_limit; ?>' name="check_in" class="date_sec" value="<?php echo $datein; ?>"></td>
            </tr>
            <tr>
              <th>出発日</th>
            </tr>
            <tr>
              <td><input type="date" id="check_date" min='<?php echo $dateout; ?>' max='<?php echo $dateout_limit; ?>' name="check_out" class="date_sec" value="<?php echo $dateout; ?>"></td>
            </tr>
          </table>
        <input type="submit" value="空室検索" id="btn">
      </form>
    </div>
  </div>
<?php require("require_pages.php/r_footer.php");?>
</body>
</html>