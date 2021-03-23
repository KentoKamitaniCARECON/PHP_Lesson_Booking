<!-- アバウトアス・Model User3 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user3.php';

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
        $user3->delete_comments($_GET["del"]);
        $user3->delete_like_comments($_GET["del"]);
    }
      //登録
    $err4 = array();
    if($_POST) {
        if(empty($_POST["pen_name"])){
          $err4[] = "* ペンネームを入力してください。";
        } elseif(mb_strlen($_POST["pen_name"]) > 15) {
          $err4[] = "* ペンネームの文字数制限を越えています";
        }
        if(empty($_POST["comment"])){
          $err4[] = "* 投稿内容を入力してください。";
        } 
        if(!empty($_POST['pen_name'])&&!empty($_POST['comment'])&&empty($err4)){
          $p_pen_name= htmlspecialchars($_POST["pen_name"],ENT_QUOTES,'UTF-8');
          $p_comment= htmlspecialchars($_POST["comment"],ENT_QUOTES,'UTF-8');
          $menbers_id= htmlspecialchars($_POST["menbers_id"],ENT_QUOTES,'UTF-8');
          $arr["pen_name"]=$p_pen_name;
          $arr['comment']=$p_comment;
          $arr['menbers_id']=$menbers_id;
          $user3->add_comments($arr);
          header('Location:/php/r_about_us.php');
        }
    }
    //参照処理
    $result=$user3->findAll_comments();
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
<link rel="stylesheet" type="text/css" href="css/aboutus.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(function() {
  $("#photo_prev").on("click",function() {
    var id = $("#photo ul").data("id") -1;
    if (id !== -1) {
      $("#photo ul").animate({
        "marginLeft" : id * -1380 + "%"
      });
      $("#photo ul").data("id",id);
    }
  });
  $("#photo_next").on("click",function() {
    var id = $("#photo ul").data("id") +1;
    if (id < 2) {
      $("#photo ul").animate({
        "marginLeft" : id * -101 + "%"
      });
      $("#photo ul").data("id",id);
    }
  });
});
</script>
</head>
<body>
<?php require("require_pages.php/r_header.php");?>
<div class="wrap">
  <div class="title">
    <h2>ライブラリー</h2>
  </div>
    <div id="rooms_photo" class="clearfix">
      <div id="photo_prev">
        <img src="image/thumb_prev.png" alt="prev">
      </div>
      <div id="photo">
        <ul class="clearfix" data-id="0">
          <li>
            <img src="image/pic1.jpg" alt="最新">
          </li>
          <li>
            <img src="image/pic2.jpg" alt="最新">
          </li>
          <li>
            <img src="image/pic3.jpg" alt="最新">
          </li>
          <li>
            <img src="image/pic4.jpg" alt="最新">
          </li>
          <li>
            <img src="image/pic5.jpg" alt="最新">
          </li>
          <li>
            <img src="image/pic6.jpg" alt="最新">
          </li>
        </ul>
      </div>
      <div id="photo_next">
        <img src="image/thumb_next.png" alt="next">
      </div>
    </div>
    <div class="title">
      <h2>自由投稿</h2>
    </div>
    <div class="error1">
      <?php if(!empty($err4)){ 
      foreach($err4 as $msg){
      echo $msg.'<br>';}} ?>
    </div>
    <div id='twit'>
      <form action="r_about_us.php" method="post">
        <input type="hidden" name="menbers_id" value="<?php echo $menbers["User"]['id']; ?>">
        <table>
          <tr>
            <th>ペンネーム</th>
            <td><input type="text" name="pen_name" size="60" value=""></td>
          </tr>
          <tr>
            <th>コメント</th>
            <td><textarea id="textbox" name="comment" cols="50" rows="8"></textarea></td>
          </tr>
        </table>
        <input type="submit" value="投 稿" id="twit_submit" onClick="if(!confirm('投稿しますか？')) return false;">
      </form> 
    </div>
    <?php foreach($result as $row): ?>
      <div id="comment">
        <p><?php echo $row['created'];?>・<?php echo $row['id'];?>件目</p>
        <div id="comment_penname">
          <p><?php echo $r_pen_name=htmlspecialchars($row['pen_name'],ENT_QUOTES,'UTF-8');
          if($row["menbers_id"]!=1){
            echo "様よりお言葉を頂きました。";
            }else{
              echo "からお伝えします。";
            }?></p>
        </div>
        <div id="comment_contemts">
          <p><?php echo $r_comment=htmlspecialchars($row['comment'],ENT_QUOTES,'UTF-8');?></p>
        </div>
        <input type="hidden" name="mid" class="m_id" value="<?php echo $row['id']; ?>">
        <?php $liked=$user3->liked_comments($menbers['User']['id'],$row['id']); ?>
        <?php if(!empty($liked)): ?>
          <div class='heart on'>
            <img src='image/good_on.jpg'>
            <p><?php echo $row['count'],"GOOD"; ?></p>
          </div>
        <?php else: ?>
          <div class='heart'>
            <img src='image/good_off.jpg'>
            <p><?php echo $row['count'],"GOOD"; ?></p> 
          </div>
        <?php endif;?>
        <?php if($menbers["User"]["role"]==1):?>
          <div id="del">
            <a href="?del=<?=$row["id"]?>" onClick="if(!confirm('本当に削除しますか？')) return false;">投稿の削除</a>
          </div>
        <?php endif;?>
      </div>
    <?php endforeach;?>
  <?php require("require_pages.php/r_footer.php");?>
<!-- //グッドボタン処理 -->
<script>
  $(function() {
    $('.heart').on('click',function() {
       var menbers_id = <?php echo $menbers["User"]["id"]; ?>;
       var $_t = $(this).parent();
      var like_id = $_t.find('.m_id');
      var  comments_id = like_id.val();
       if($(this).hasClass("on")){
         $(this).removeClass("on");
         $(this).html("<img src='image/good_off.jpg'><p>GOODを取消ました</p>");
         $.ajax({
           url:'require_pages.php/ajax2.php',
           type:'POST',
           data:{
             "menbers_id" : menbers_id,
             "comments_id" : comments_id
           }
         })
      }else{
         $(this).addClass("on");
         $(this).html("<img src='image/good_on.jpg'><p>GOOD !</p>");
         $.ajax({
           url:'require_pages.php/ajax1.php',
           type:'POST',
           data:{
             "menbers_id" : menbers_id,
             "comments_id" : comments_id
           }
        })
      }
    });
  });
</script>
</body>
</html>