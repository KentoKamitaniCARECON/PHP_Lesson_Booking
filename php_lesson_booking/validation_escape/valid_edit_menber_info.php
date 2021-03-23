<?php 
  //編集バリデーション　名前・住所・電話・誕生日
  $err2 = array();
  if($_POST) {
    if(empty($_POST["name"])){
      $err2[] = "* 氏名を入力してください。";
    } elseif(mb_strlen($_POST["name"]) > 32) {
      $err2[] = "* 氏名の文字数制限を越えています";
    }
    if(empty($_POST["address"])){
      $err2[] = "* 住所を入力してください。";
    } elseif(mb_strlen($_POST["name"]) > 128) {
      $err2[] = "* 住所の文字数制限を越えています";
    }
    if(empty($_POST["tel"])){
      $err2[] = "* 電話を入力してください。";
    } elseif(!preg_match("/^[0-9０-９]+$/",$_POST["tel"]) || mb_strlen($_POST["tel"]) > 20) {
      $err2[] = "* 電話は数字のみ20文字以内で入力してください";
    }
    if(empty($_POST["birth"])){
      $err2[] = "* 生年月日を入力してください。";
    }
  }

  //編集サニタイズ　名前・住所・電話・誕生日
  $escape_name="";
  $escape_address="";
  $escape_tel="";
  $escape_birth="";
  if($_POST) {  
    $escape_name = htmlspecialchars($_POST["name"],ENT_QUOTES,'UTF-8');
    $escape_address = htmlspecialchars($_POST["address"],ENT_QUOTES,'UTF-8');
    $escape_tel = htmlspecialchars($_POST["tel"],ENT_QUOTES,'UTF-8');
    $escape_birth = htmlspecialchars($_POST["birth"],ENT_QUOTES,'UTF-8');
  }
?>

