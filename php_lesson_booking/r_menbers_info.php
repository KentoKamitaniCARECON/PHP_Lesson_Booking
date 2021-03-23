<!-- 会員一覧ページ・Model User1 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/valid_menbers_info.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';

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
}
try{
  $user =new User($host,$dbname,$user,$pass);
  $user->connectDb();

  //編集
  if(isset($_GET["edit"])) {
    if(!empty($_POST)&&empty($err)) {
      $arr['password']=$_POST['password'];
        $arr['email']=$_POST['email'];
        $arr['id']=$_POST['id'];
        $arr['role']=$escape_role;
        $arr['name']=$escape_name;
        $arr['address']=$escape_address;
        $arr['tel']=$escape_tel;
        $arr['birth']=$escape_birth;
        $arr['other']=$escape_other;
        $user->edit($arr);
    }
    //参照処理
    $result["User"]=$user->findById($_GET["edit"]);
  }

  //編集
  if(isset($_GET["history"])) {
    $_SESSION['history']=$_GET["history"];
    $history=$_SESSION['history'];
    $page2=0;
    $book_count=$user->findHistory($history,$page2);
    $result["User"]=$user->findById($_GET["history"]);       
  } elseif(isset($_GET['page2']) && empty($_GET["history"])) {
    $history=$_SESSION['history'];
    $result["User"]=$user->findById($history);       
      $page2=$_GET['page2'];
      $book_count=$user->findHistory($history,$page2);
      }

  //削除
  if(isset($_GET["del"])) {
    if($_SESSION["User"]["id"] != $_GET["del"]){
      $user->deleteMenber($_GET["del"]);
      $user->del_Res($_GET["del"]);
      $user->del_Com($_GET["del"]);
      $user->del_Like($_GET["del"]);
    }
    $word=$_SESSION['word'];
    $result=$user->findByWord($word);
  }
  
  if(!empty($_GET["return"])) {
    $word=$_SESSION['word'];
    $result=$user->findByWord($word);
  }
  
  //検索
  if(isset($_POST["word"])) {
    $_SESSION['word']='%'.$_POST["word"].'%';
    $word=$_SESSION['word'];
    $page=0;
    $result=$user->findByWord($word,$page);
  }elseif(isset($_GET['page']) && empty($_POST["word"])) {
      $page=$_GET['page'];
      $word=$_SESSION['word'];
      $result=$user->findByWord($word,$page);
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
    <h2>会員情報</h2>
  </div>
  <div class="check_menber">
    <form action="r_menbers_info.php" method="post">
      <input type="text" name="word" size="40" value="<?php if(!empty($_POST["word"])){echo $p_word= htmlspecialchars($_POST["word"],ENT_QUOTES,'UTF-8'); } ?>">
      <input type="submit" class="check_submit" value="検索">
    </form>
    <p>空欄で検索ボタンを押すと会員番号順に全て表示</p>
    <p>＊管理者によるメールアドレスとパスワードの変更はできません。</p>
  </div>
  <?php if(!empty($result[0]["count"])):?>
    <div class="total_res">
      <?php echo $result[0]["count"]."件"; ?>
    </div>
  <?php endif;?>
  <?php if(!empty($result) || isset($_GET["edit"]) || isset($_GET["history"]) || (!empty($result) && isset($_GET["del"]))): ?>
    <div class="menber_detail">
      <table>
        <tr>
          <th class="fixed">ID</th>
          <th>氏名</th>
          <th>住所</th>
          <th>電話</th>
          <th>生年月日</th>
          <th>メールアドレス</th>
          <th>権限</th>
          <th>備考</th>
          <th>履歴</th>
          <th class="fixed2">設定</th>
        </tr>      
        <?php foreach($result as $row): ?> 
          <tr>
            <td class="fixed"><?=$row_id=htmlspecialchars($row["id"],ENT_QUOTES,'UTF-8');?></td>
            <td><?=$row_name=htmlspecialchars($row["name"],ENT_QUOTES,'UTF-8');?></td>
            <td><?=$row_address=htmlspecialchars($row["address"],ENT_QUOTES,'UTF-8');?></td>
            <td><?=$row_tel=htmlspecialchars($row["tel"],ENT_QUOTES,'UTF-8');?></td>
            <td><?=$row_birth=htmlspecialchars($row["birth"],ENT_QUOTES,'UTF-8');?></td>
            <td><?=$row_email=htmlspecialchars($row["email"],ENT_QUOTES,'UTF-8');?></td>
            <td>
              <?php if($row_role=htmlspecialchars($row['role'],ENT_QUOTES,'UTF-8')==1){
                  echo "管理者";
              } elseif($row_role=htmlspecialchars($row['role'],ENT_QUOTES,'UTF-8')==3){
                echo "不良会員";
              }else{
                echo "一般会員";
              } ?>
            </td>
            <td><?=$row_other=htmlspecialchars($row["other"],ENT_QUOTES,'UTF-8')?></td>
            <td>
              <a href="?history=<?=$row_id?>">履歴</a>
            </td>
            <td class="fixed2">
              <a href="?edit=<?=$row_id?>">編集 /</a>
                <a href="?del=<?=$row_id?>" onClick="if(!confirm('過去・現在の予約、これまでの投稿も削除されます。ID:<?=$row_id?>を本当に削除しますか？')) return false;">削除</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <!-- //会員情報編集 -->
    <?php if(!empty($_GET["edit"])):?>
      <div class='editform'>
      <!-- //バリデーション結果通知 -->
        <div id="error">
          <?php if(!empty($err)):?>
            <?php foreach($err as $msg):?>
              <?php  echo $msg.'<br>'; ?>
            <?php endforeach;?>
          <?php elseif(!empty($_POST)&&empty($err)):?>
            <?php  echo '変更が完了しました。'; ?>
    <?php endif;?>
    </div>
    <form action="" method="post">
      <table>
        <input type="hidden" name="id" value="<?php echo $g_id = htmlspecialchars($_GET["edit"],ENT_QUOTES,'UTF-8'); ?>">
        <input type="hidden" name="email" value="<?php echo $result_email= htmlspecialchars($result["User"]["email"],ENT_QUOTES,'UTF-8'); ?>">
        <input type="hidden" name="password" value="<?php echo $result_password= htmlspecialchars($result["User"]["password"],ENT_QUOTES,'UTF-8'); ?>">
          <tr>
            <th id="name">氏名</th>
            <td><input type="text" name="name" size="50" value="<?php if(!empty($escape_name)){echo $escape_name;}else{echo $result_name= htmlspecialchars($result["User"]["name"],ENT_QUOTES,'UTF-8');} ?>"></td>
          </tr>
          <tr>
            <th id="address">住所</th>
            <td><input type="text" name="address" size="50" value="<?php if(!empty($escape_address)){echo $escape_address;}else{echo $result_address= htmlspecialchars($result["User"]["address"],ENT_QUOTES,'UTF-8');} ?>"></td>
          </tr>
          <tr>
            <th id="tel">電話</th>
            <td><input type="text" name="tel" size="50" value="<?php if(!empty($escape_tel)){echo $escape_tel;}else{echo $result_tel= htmlspecialchars($result["User"]["tel"],ENT_QUOTES,'UTF-8');} ?>"></td>
          </tr>
          <tr>
            <th id="birth">生年月日</th>
            <td><input type="date" min="<?php echo $set_birth; ?>" max="<?php echo date('Y-m-d'); ?>" name="birth" class="date_sec3" value="<?php if(!empty($escape_birth)){echo $escape_birth;}else{echo $result_birth= htmlspecialchars($result["User"]["birth"],ENT_QUOTES,'UTF-8');} ?>"></td>
          </tr>
          <?php if($result["User"]["role"]==1): ?>
            <input type="hidden" name="role" value=1>
          <?php else:?>
            <tr>
            <th id="role">権限</th>
            <td><input type="radio" name="role" size="50" value="2" <?php if($result["User"]["role"]==2){echo "checked='checked'";}?>>一般会員/<input type="radio" name="role" size="50" value="3" <?php if($result["User"]["role"]==3){echo "checked='checked'";}?>>不良会員</td>
            </tr>
          <?php endif;?>
          <tr>
            <th id="other">備考</th>
            <td><input type="text" name="other" size="50" value="<?php if(!empty($escape_other)){echo $escape_other;}else{echo $result_other= htmlspecialchars($result["User"]["other"],ENT_QUOTES,'UTF-8');} ?>"></td>
          </tr>
      </table>
      <input type="submit" value="送　信" class="edit_submit" id="btn" onClick="if(!confirm('編集しますか？')) return false;">
    </form>     
      <div class="close">
        <a href="?return=1">※編集フォームを閉じる</a>
      </div>
    </div>
  <?php endif; ?>
  <?php if(empty($_GET["edit"])&&empty($_GET["history"])&&!isset($_GET["page2"])): ?>
    <div class="paging">
      <?php for($i=0;$i<$result[0]["count"]/5;$i++){
            if(isset($_GET['page']) && $_GET['page']==$i){
              echo ($i+1)."・";
            } else {
              echo "<a href='?page=".$i."'>".($i+1)."・</a>";
            }
          } ?>
      <?php endif; ?>
      <?php else: ?>
        <div class="close"><p><?php echo "該当会員情報なし。";?></p></div>
    <?php endif; ?>
    <?php if(isset($_GET["history"]) || isset($_GET["page2"])):?>
      <?php if(!empty($book_count)) :?>
        <div class="total_res">
          <h2>総予約件数：<?php echo $book_count[0]['count'];?>件</h2>
        </div> 
        <div id="res_detail">
        <table>
          <tr>
            <th>到着日</th>
            <th>出発日</th>
            <th>部屋番号</th>
            <th>部屋タイプ</th>
            <th>備考</th>
          </tr>
          <?php foreach($book_count as $detail): ?> 
            <tr>
              <td><?=$detail_check_in= htmlspecialchars($detail["check_in"],ENT_QUOTES,'UTF-8');?></td>
              <td><?=$detail_check_out= htmlspecialchars($detail["check_out"],ENT_QUOTES,'UTF-8');?></td>
              <td><?=$detail_room= htmlspecialchars($detail["room"],ENT_QUOTES,'UTF-8');?></td>
              <td><?=$detail_beds= htmlspecialchars($detail["beds"],ENT_QUOTES,'UTF-8');?></td>
              <td><?=$detail_requests= htmlspecialchars($detail["requests"],ENT_QUOTES,'UTF-8');?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
      <?php if(empty($_GET["edit"]) && empty($_POST)): ?>
        <div class="paging">
          <?php for($i=0;$i<$book_count[0]['count']/10;$i++){
            if(isset($_GET['page2']) && $_GET['page2']==$i){
              echo ($i+1)."・";
            } else {
              echo "<a href='?page2=".$i."'>".($i+1)."・</a>";
            }
          } ?>
      <?php endif; ?>
      <div class="close">
        <a href="?return=1>">履歴を閉じる</a>
      </div>
      <?php else :?>
        <div class="close"><?php echo "予約履歴なし"; ?>
      <a href="?return=1">戻る</a></div>
    <?php endif; ?>
  <?php endif; ?>
</div>
<?php require("require_pages.php/r_footer.php");?>
</body>
</html>