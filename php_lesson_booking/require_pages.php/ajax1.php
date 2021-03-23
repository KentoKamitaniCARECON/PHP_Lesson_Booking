<!-- GOODボタン適用 -->
<?php
require_once("config.php");
require_once("model_user3.php");

try{
  $user3 =new User3($host,$dbname,$user,$pass);
  $user3->connectDb();

  if($_POST) {
    $user3->like_add($_POST);
  }

} catch(PDOException $e){
  echo 'エラー'.$e->getMessage();
}
?>