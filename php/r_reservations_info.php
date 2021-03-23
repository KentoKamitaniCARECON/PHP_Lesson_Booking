<!-- 予約一覧ページ・Model User2 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user2.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';
require_once dirname(__FILE__).'/validation_escape/valid_reservations_info.php';

//セッションとログイン
if(!isset($_SESSION["User"])&&!$_SESSION["User"]['role']==1) {
  header("Location:/php/r_login_case1.php");
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

try{
  $user2 =new User2($host,$dbname,$user,$pass);
  $user2->connectDb();

  //編集
  if(!empty($_GET['edit'])){
    $res_id=$user2->findById($g_edit);
    if(!empty($_POST['check_in'])&&!empty($_POST['check_out'])&&empty($err)){
      $result=$user2->findMenber($escape_menbers_id);
      if($date3 <= 6 && ($day2->format('Y-m-d')>$day1->format('Y-m-d'))&&!empty($result)) {
        $result=$user2->findBydate2($date1,$date2,$self_id);
        foreach($result as $key){
        if($key['id']==$escape_rooms_id){
          $arr['id']=$_POST['id'];
          $arr['check_in']=$escape_check_in;
          $arr['check_out']=$escape_check_out;
          $arr['requests']=$escape_requests;
          $arr['rooms_id']=$escape_rooms_id;
          $arr['menbers_id']=$escape_menbers_id;
          $user2->edit($arr);
          $result=NULL;
          $dup=NULL;
          break;
        }else{
          $dup="記入された部屋は既に予約されているか、その部屋番号は存在しません。";
          $result=NULL;
        }}
      }elseif(($day2->format('Y-m-d')> $day1->format('Y-m-d')) && $date3 > 6&&!empty($result)){
        $dup="長期滞在は6泊までです。";
        $result=NULL;
      }else{
        $dup="日付もしくは、会員番号を正しく入力してください。";
        $result=NULL;
      }
    }
    //参照処理
    $result["User"]=$user2->findByReservId($g_edit);
  }

  $page_set=0;
  //削除
  if(isset($_GET["del"])) {
    if($_SESSION["User"]["id"] != $_GET["del"]){
      $user2->deleteReservation($g_del);
    }
    $word='%%';
    $result=$user2->findByWord($word);
    $page_set=1;
  }
  
  //ID検索
  $reservation_id="";
  if(isset($_POST["reservation_id"])) {
    $id=$p_reservation_id;
    $result['User']=$user2->findByReservId($id);
    if(empty($result['User'])){
      $reservation_id="0件";
    }
  }
  
  //検索
  if(isset($_POST["word"])) {
    $_SESSION['word']='%'.$p_word.'%';
    $word=$_SESSION['word'];
    $page=0;
    $result=$user2->findByWord($word,$page);
    $page_set=1;
  }elseif(isset($_GET['page']) && empty($_POST["word"])) {
    $page_set=1;
    $word=$_SESSION['word'];
    $page=$_GET['page'];
    $result=$user2->findByWord($word,$page);
    }

  if(isset($_POST["in"])&&isset($_POST["out"])) {
    $_SESSION['in']=$p_in;
    $_SESSION['out']=$p_out;
    $arr['in']=$_SESSION['in'];
    $arr['out']=$_SESSION['out'];
    $page=0;
    $result=$user2->findByInOut($arr,$page);
    $page_set=2;
  }elseif(isset($_GET['page2']) && empty($_POST["in"])){
    $page_set=2;
    $arr['in']=$_SESSION['in'];
    $arr['out']=$_SESSION['out'];
    $page2=$_GET['page2'];
    $result=$user2->findByInOut($arr,$page2);
  }

  if(!empty($_GET["return"])) {
    $word='%%';
    $result=$user2->findByWord($word);
    $page_set=1;
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
<link rel="stylesheet" type="text/css" href="css/detail.css">
</head>
<body>
<?php require("require_pages.php/r_header.php");?>
<div class="wrap">
  <div class="title">
      <h2>予約情報</h2>
  </div>
  <div class="check_menber">
    <form action="r_reservations_info.php" method="post">
      <input type="text" name="word" size="40" value="<?php if(isset($_POST["word"])){echo $p_word; } ?>">
      <input type="submit" class="check_submit" value="フリーワード検索">
    </form>
    <p>空欄で検索ボタンを押すと日付順に全て表示</p>
    <p>＊会員情報は会員一覧で編集できます。</p>
  </div>
  <div class="check_menber">
    <form action="r_reservations_info.php" method="post">
      <label id="in">IN：<input type="date" name="in" class="date_sec2" min="<?php echo $res_past; ?>" max="<?php echo $res_pre; ?>" value="<?php if(isset($_POST["in"])){echo $p_in; }else{ echo date('Y-m-d');} ?>"></label><br>
      <label class="out">OUT：<input type="date" name="out" class="date_sec2" min="<?php echo $res_past; ?>" max="<?php echo $res_pre; ?>" value="<?php if(isset($_POST["out"])){echo $p_out; }else{ echo $dateout;} ?>"></label><br>
      <input type="submit" class="check_submit" value="IN/OUT日検索">
    </form>
  </div>
  <div class="check_menber">
    <form action="r_reservations_info.php" method="post">
      <label id="reservation_id">ID：<input type="text" name="reservation_id" size="40" value="<?php if(isset($_POST["reservation_id"])){echo $p_reservation_id; } ?>"></label>
      <input type="submit" class="check_submit" value="予約番号検索">
    </form>
  </div>
  <?php if(empty($result)||!empty($reservation_id)): ?>
    <div class="close">
      <?php echo "該当予約なし。";?>
    </div>
    <?php else: ?>
  <div class="menber_detail">
    <table>
      <tr>
        <th class="fixed">ID</th>
        <th>到着日</th>
        <th>出発日</th>
        <th>室名</th>
        <th>部屋タイプ</th>
        <th>会員番号</th>
        <th>氏名</th>
        <th>電話</th>
        <th>メール</th>
        <th>ご要望</th>
        <th class="fixed2">設定</th>
      </tr>
      <?php foreach($result as $row): ?> 
        <tr>
          <td class="fixed"><?=$row_id=htmlspecialchars($row["id"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_check_in=htmlspecialchars($row["check_in"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_check_out=htmlspecialchars($row["check_out"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_room=htmlspecialchars($row["room"],ENT_QUOTES,'UTF-8');?>
          (<?=$row_rid=htmlspecialchars($row["rid"],ENT_QUOTES,'UTF-8');?>)</td>
          <td><?=$row_beds=htmlspecialchars($row["beds"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_mid=htmlspecialchars($row["mid"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_name=htmlspecialchars($row["name"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_tel=htmlspecialchars($row["tel"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_email=htmlspecialchars($row["email"],ENT_QUOTES,'UTF-8');?></td>
          <td><?=$row_requests=htmlspecialchars($row["requests"],ENT_QUOTES,'UTF-8');?></td>
          <td class="fixed2">
            <a href="?edit=<?=$row_id?>">編集</a>
              <a href="?del=<?=$row_id?>" onClick="if(!confirm('ID:<?=$row_id?>を削除しますか？')) return false;">削除</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
  <?php if($page_set==1): ?>
    <div class="total_res">
      <?php echo $result[0]["count"]."件"; ?>
    </div>
    <div class="paging">
      <?php for($i=0;$i<$result[0]["count"]/10;$i++){
        if(isset($_GET['page']) && $_GET['page']==$i){
          echo ($i+1)."・";
        } else {
          echo "<a href='?page=".$i."'>".($i+1)."・</a>";
        }
      } ?>
  <?php endif; ?>
  <?php if($page_set==2): ?>
    <div class="total_res">
      <?php echo $result[0]["count"]."件"; ?>
    </div>
    <div class="paging">
      <?php for($i=0;$i<$result[0]["count"]/10;$i++){
      if(isset($_GET['page2']) && $_GET['page2']==$i){
        echo ($i+1)."・";
      } else {
        echo "<a href='?page2=".$i."'>".($i+1)."・</a>";
      }
      } ?>
    <?php endif; ?>
  <?php endif; ?>
<!-- //情報編集 -->
  <?php if(isset($_POST["check_in"])||isset($_GET["edit"])):?>
    <div class='editform'>
    <!-- //バリデーション結果通知 -->
    <div id="error">
      <?php if(!empty($dup)):?>
          <?php echo $dup.'<br>';?>
        <?php endif;?>
        <?php if(!empty($err)):?>
          <?php foreach($err as $msg):?>
            <?php  echo $msg.'<br>'; ?>
        <?php endforeach;?>
      <?php elseif(!empty($_POST)&&empty($err)&&empty($dup)):?>
        <?php  echo '変更が完了しました。'; ?>
      <?php endif;?>
    </div>
    <form action="" method="post">
      <table>
        <input type="hidden" name="id" value="<?php echo $g_edit; ?>">
        <tr>
          <th id="check_in">到着日</th>
          <td><input type="date" min='<?php echo $datein; ?>' max='<?php echo $datein_limit; ?>' name="check_in" class="date_sec3" value="<?php if(!empty($escape_check_in)){echo $escape_check_in;}else{ echo $edit_check_in= htmlspecialchars($res_id['check_in'],ENT_QUOTES,'UTF-8');} ?>"></td>
        </tr>
        <tr>
          <th id="check_out">出発日</th>
          <td><input type="date" min='<?php echo $dateout; ?>' max='<?php echo $dateout_limit; ?>' name="check_out" class="date_sec3" value="<?php if(!empty($escape_check_out)){ echo $escape_check_out;}else{ echo $edit_check_out= htmlspecialchars($res_id['check_out'],ENT_QUOTES,'UTF-8');} ?>"></td>
        </tr>
        <tr>
          <th id="requests">ご要望</th>
          <td><textarea id="textbox" name="requests"  rows="8"><?php if(!empty($escape_requests)){ echo $escape_requests;}else{ echo $edit_requests= htmlspecialchars($res_id['requests'],ENT_QUOTES,'UTF-8');} ?></textarea></td>
        </tr>
        <tr>
          <th id="rooms_id">部屋番号</th>
          <td><input type="text" name="rooms_id" size="50" value="<?php if(!empty($escape_rooms_id)){ echo $escape_rooms_id;}else{ echo $edit_rooms_id= htmlspecialchars($res_id['rooms_id'],ENT_QUOTES,'UTF-8');} ?>"></td>
        </tr>
        <tr>
          <th id="menbers_id">会員番号</th>
          <td><input type="text" name="menbers_id" size="50" value="<?php if(!empty($escape_menbers_id)){ echo $escape_menbers_id;}else{ echo $edit_menbers_id= htmlspecialchars($res_id['menbers_id'],ENT_QUOTES,'UTF-8');} ?>"></td>
        </tr>
      </table>
        <input type="submit" class="edit_submit" value="編 集" id="btn" onClick="if(!confirm('編集しますか？')) return false;">
      </form>
      <div class="close">
        <a href="?return=1">編集フォームを閉じる</a>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php require("require_pages.php/r_footer.php");?>
</body>
</html>