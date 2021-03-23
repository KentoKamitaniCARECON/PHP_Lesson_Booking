<!-- マイページ・Model User1 -->
<?php 
session_start();
require_once dirname(__FILE__).'/require_pages.php/config.php';
require_once dirname(__FILE__).'/require_pages.php/model_user.php';
require_once dirname(__FILE__).'/validation_escape/set_date.php';
require_once dirname(__FILE__).'/validation_escape/valid_id_pas.php';
require_once dirname(__FILE__).'/validation_escape/valid_menber_info.php';

//ダイレクトアクセス対策とログイン
if(!isset($_SESSION["User"])) {
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
  $escape_id= htmlspecialchars($menbers["User"]["id"],ENT_QUOTES,'UTF-8');
  $escape_role= htmlspecialchars($menbers["User"]["role"],ENT_QUOTES,'UTF-8');
  $escape_other= htmlspecialchars($menbers["User"]["other"],ENT_QUOTES,'UTF-8');
}
 
try{
  $user =new User($host,$dbname,$user,$pass);
  $user->connectDb();

  //編集
  if(!empty($_POST)){
    if(empty($err1)&&empty($err2)){
      $arr['name']=$escape_name;
      $arr['address']=$escape_address;
      $arr['tel']=$escape_tel;
      $arr['birth']=$escape_birth;
      $arr['email']=$escape_email;
      $arr['password']=$hash_pass;
      $arr['id']=$_POST['id'];
      $arr['role']=$_POST['role'];
      $arr['other']=$_POST['other'];
        if($arr['email']!=$menbers["User"]['email']) {
          $duplicate=$user->findbyEmail($arr['email']);
            if(empty($duplicate)){
            $user->edit($arr);
            $result["User"]=$user->findById($escape_id);
            $_SESSION["User"]=$result["User"];
            header("Location:/php/r_mypage.php");
          }else{
            $duplicate_email="*このメールアドレスはご利用できません";
          }        
        }else{
          $user->edit($arr);
          $result["User"]=$user->findById($escape_id);
          $_SESSION["User"]=$result["User"];
          header("Location:/php/r_mypage.php");
        }
    }
  }
  //削除
  if(isset($_GET["del"])) {
      $user->deleteReservation($_GET["del"]);
  }
  //予約履歴表示
  $history=$menbers["User"]["id"];
    $page2=0;
    $book_count=$user->findHistory($history,$page2);
  if(isset($_GET['page2'])){
      $page2=$_GET['page2'];
      $book_count=$user->findHistory($history,$page2);
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
<link rel="stylesheet" type="text/css" href="css/mypage.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php require("require_pages.php/r_header.php");?>
  <div class="wrap">
    <div class="title">
      <h2>会員情報</h2>
    </div>
    <div id="info">
      <table>
        <tr>
          <th>氏名</th><td><?=$menbers_name = htmlspecialchars($menbers["User"]["name"],ENT_QUOTES,'UTF-8')?></td>
        </tr>
        <tr>
          <th>住所</th><td><?=$menbers_address = htmlspecialchars($menbers["User"]["address"],ENT_QUOTES,'UTF-8')?></td>
        </tr>
        <tr>
          <th>電話</th><td><?=$menbers_tel = htmlspecialchars($menbers["User"]["tel"],ENT_QUOTES,'UTF-8')?></td>
        </tr>
        <tr>
          <th>生年月日</th><td><?=$menbers_birth = htmlspecialchars($menbers["User"]["birth"],ENT_QUOTES,'UTF-8')?></td>
        </tr>
        <tr>
          <th>メールアドレス</th><td><?=$menbers_email = htmlspecialchars($menbers["User"]["email"],ENT_QUOTES,'UTF-8')?></td>
        </tr>
        <?php if($menbers["User"]['role']==1):?>
          <tr>
            <th>権限</th><td><?php if($menbers["User"]['role']==1)
            {echo "管理者";} ?></td>
          </tr>
        <?php endif;?>
        <tr>
          <th>設定</h><td>
            <a href="?edit=<?=$menbers["User"]['id']?>">編集する</a>
          </td>
        </tr>
      </table>
    </div>
    <!-- //編集フォーム -->
    <?php if(!isset($_GET['close'])&&isset($_GET["edit"])||($_SERVER['HTTP_REFERER'] == "http://localhost/php/r_login_case2.php") || !empty($duplicate) || !empty($err2) || !empty($err1) ):?>
    <div id='editform'>
    <div id="close"><a href="?close=1">※編集フォームを閉じる</a><br><p>以下にユーザ情報を編集できます。</p></div>
      <!-- //バリデーション結果通知 -->
      <div class="error1">
        <?php if(!empty($err2)){ 
          foreach($err2 as $msg2){
          echo $msg2.'<br>';}} ?>
        <?php if(!empty($err1)){ 
          foreach($err1 as $msg1){
          echo $msg1.'<br>';}} ?>
      </div>
      <div class="error2">
        <?php if(!empty($duplicate_email)){echo $duplicate_email;} ?>
      </div>
       <form action="r_mypage.php" method="post">
        <table>
          <input type="hidden" name="id" value="<?php echo $escape_id; ?>">
          <input type="hidden" name="role" value="<?php echo $escape_role; ?>">
          <input type="hidden" name="other" value="<?php echo $escape_other; ?>">
          <tr>
            <th>氏名</th>
            <td><input type="text" id="input_box" name="name" size="20" value="<?php if(!empty($escape_name)){echo $escape_name;}else{echo $menbers_name= htmlspecialchars($menbers["User"]["name"],ENT_QUOTES,'UTF-8');}?>"></td>
          </tr>
          <tr>
            <th>住所</th>
            <td><input type="text" id="input_box" name="address" size="20" value="<?php if(!empty($escape_address)){echo $escape_address;}else{echo $menbers_address= htmlspecialchars($menbers["User"]["address"],ENT_QUOTES,'UTF-8');}?>"></td>
          </tr>
          <tr>
            <th>電話</th>
            <td><input type="text" id="input_box" name="tel" size="20" value="<?php if(!empty($escape_tel)){echo $escape_tel;}else{echo $menbers_tel= htmlspecialchars($menbers["User"]["tel"],ENT_QUOTES,'UTF-8');}?>"></td>
          </tr>
          <tr>
            <th>生年月日</th>
            <td><input type="date"  id="input_box" min="<?php echo $set_birth; ?>" max="<?php echo date('Y-m-d'); ?>" name="birth" size="20" value="<?php if(!empty($escape_birth)){echo $escape_birth;}else{echo $menbers_birth= htmlspecialchars($menbers["User"]["birth"],ENT_QUOTES,'UTF-8');}?>"></td>
          </tr>
          <tr>
            <th>メールアドレス</th>
            <td><input type="text" id="input_box" name="email" size="20" value="<?php if(!empty($escape_email)){echo $escape_email;}else{echo $menbers_email= htmlspecialchars($menbers["User"]["email"],ENT_QUOTES,'UTF-8');}?>"></td>
          </tr>
          <tr>
            <th>パスワード</th>
            <td><input type="password" id="input_box" name="password" size="20" value=""></td>
          </tr>
        </table>
        <input type="submit" id="edit_submit" value="編 集" id="btn" onClick="if(!confirm('編集しますか？')) return false;">
      </form>
    </div>
  <?php endif; ?>
  <div class="title">
    <h2>予約確認</h2>
  </div>
  <?php if(!empty($book_count)) :?>
    <div id="history_res">
      <table>
      <div id="total_res">
        <p>総予約件数：<?php echo $book_count[0]['count'];?>件</p>
      </div>  
        <tr>
          <th>到着日</th>
          <th>出発日</th>
          <th>部屋番号</th>
          <th>備考</th>
          <th>設定</th>
        </tr>
        <?php foreach($book_count as $detail): ?> 
          <tr>
            <td><?=$detail_check_in=htmlspecialchars($detail["check_in"],ENT_QUOTES,'UTF-8')?></td>
            <td><?=$detail_check_out=htmlspecialchars($detail["check_out"],ENT_QUOTES,'UTF-8')?></td>
            <td><?=$detail_room=htmlspecialchars($detail["room"],ENT_QUOTES,'UTF-8')?></td>
            <td><?=$detail_beds=htmlspecialchars($detail["beds"],ENT_QUOTES,'UTF-8')?></td>
            <?php if($detail["check_in"] >= $date_set):?>
              <td>
                <div id="interm">
                  <a href="?del=<?=$detail["id"]?>" onClick="if(!confirm('本当に削除しますか？')) return false;"><input type="submit" id="history_del" value="削除する"></a>
                  <form action="r_re_booking.php" method="post">
                  <input type="hidden" name="res_id" value="<?php echo $detail["id"];?>">
                  <input type="hidden" name="check_in" value="<?php echo $detail_check_in=htmlspecialchars($detail["check_in"],ENT_QUOTES,'UTF-8');?>">
                  <input type="hidden" name="check_out" value="<?php echo $detail_check_out=htmlspecialchars($detail["check_out"],ENT_QUOTES,'UTF-8');?>">
                  <input type="submit" id="history_edit" value="変更する">
                  </form>
                </div>
              </td>
              <?php else:?>
              <td>
                <div id="outofterm">
                  <?php echo "変更・取消期間外"; ?>
                </div>
              </td>
            <?php endif;?>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
        <?php if(($book_count[0]['count'])>=10): ?>
        <div class="paging">
          <?php for($i=0;$i<$book_count[0]['count']/10;$i++){
            if(isset($_GET['page2']) && $_GET['page2']==$i){
              echo ($i+1)."・";
            } else {
              echo "<a href='?page2=".$i."'>".($i+1)."・</a>";
            }
          } ?>
        </div>
      <?php endif; ?>
      <?php else :?>
        <div id="no_res">
          <?php echo '<p>'."予約履歴なし".'</p>'; ?>
        </div>
    <?php endif; ?>
  </div>
  <?php require("require_pages.php/r_footer.php");?>
</body>
</html>