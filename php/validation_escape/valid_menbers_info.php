<?php 
  // バリデーション　会員一覧編集用
  $err = array();
  if($_POST) {
    if(empty($_POST["name"])){
      $err[] = "* 氏名を入力してください。";
    } elseif(mb_strlen($_POST["name"]) > 32) {
      $err[] = "* 氏名の文字数制限を越えています";
    }
    if(empty($_POST["address"])){
      $err[] = "* 住所を入力してください。";
    } elseif(mb_strlen($_POST["name"]) > 128) {
      $err[] = "* 住所の文字数制限を越えています";
    }
    if(empty($_POST["tel"])){
      $err[] = "* 電話を入力してください。";
    } elseif(!preg_match("/^[0-9０-９]+$/",$_POST["tel"]) || mb_strlen($_POST["tel"]) > 20) {
      $err[] = "* 電話は数字のみ20文字以内で入力してください";
    }
    if(empty($_POST["birth"])){
      $err[] = "* 生年月日を入力してください。";
    }
    if(empty($_POST["role"])){
      $err[] = "* 権限を選択して下さい";
    }
  }

  //サニタイズ　会員一覧編集用
  $p_word="";
  $escape_name="";
  $escape_address="";
  $escape_tel="";
  $escape_birth="";
  $escape_other="";
  $escape_role="";
  if($_POST) {
    if(!empty($_POST["name"])){
    $escape_name = htmlspecialchars($_POST["name"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["address"])){
    $escape_address = htmlspecialchars($_POST["address"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["tel"])){
    $escape_tel = htmlspecialchars($_POST["tel"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["birth"])){
    $escape_birth = htmlspecialchars($_POST["birth"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["other"])){
    $escape_other = htmlspecialchars($_POST["other"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["role"])){
    $escape_role = htmlspecialchars($_POST["role"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["word"])){
    $p_word = htmlspecialchars($_POST["word"],ENT_QUOTES,'UTF-8');
    }
  }
?>