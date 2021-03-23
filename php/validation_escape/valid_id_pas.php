<?php 
  //バリデーション　メール・パスワード
  $err1 = array();
  if($_POST) {
    if(empty($_POST["email"])){
      $err1[] = "* メールアドレスを入力してください";
    } else if (!filter_var($_POST["email"],FILTER_VALIDATE_EMAIL) ){
      $err1[] = "* メールアドレスを正しく入力してください";
    }
    if (!preg_match("/^[a-zA-Z0-9]{1,20}+$/",$_POST["password"])) {
    $err1[] = "* パスワードは20文字以下の半角英数字で入力してください";
    }
  }
  //サニタイズ　メール・パスワード
  $escape_email="";
  $escape_password="";
  if($_POST) {  
    $escape_password = htmlspecialchars($_POST["password"],ENT_QUOTES,'UTF-8');
    $escape_email = htmlspecialchars($_POST["email"],ENT_QUOTES,'UTF-8');

    if(!empty($escape_password)){
      $hash_pass = password_hash($escape_password, PASSWORD_DEFAULT);
    }
  }

?>

