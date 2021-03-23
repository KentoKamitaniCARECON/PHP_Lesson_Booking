<?php 
  // バリデーション　
  $err = array();
  if($_POST) {
    if(empty($_POST["check_in"])){
      $err[] = "* 到着日を入力してください。";
    }
    if(empty($_POST["check_out"])){
      $err[] = "* 出発日を入力してください。";
    }
    if(empty($_POST["rooms_id"])){
      $err[] = "* 部屋番号を入力してください。";
    }
    if(empty($_POST["menbers_id"])){
      $err[] = "* 会員番号を入力してください。";
    }
  }

  //サニタイズ
  $p_word="";
  $p_in="";
  $p_out="";
  $p_reservation_id="";
  $escape_check_in="";
  $escape_check_out="";
  $escape_requests="";
  $escape_rooms_id="";
  $escape_menbers_id="";
  if($_POST) {
    if(!empty($_POST["check_in"])){
    $escape_check_in = htmlspecialchars($_POST["check_in"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["check_out"])){
    $escape_check_out = htmlspecialchars($_POST["check_out"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["requests"])){
    $escape_requests = htmlspecialchars($_POST["requests"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["rooms_id"])){
    $escape_rooms_id = htmlspecialchars($_POST["rooms_id"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["menbers_id"])){
    $escape_menbers_id = htmlspecialchars($_POST["menbers_id"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["word"])){
    $p_word = htmlspecialchars($_POST["word"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["in"])){
    $p_in = htmlspecialchars($_POST["in"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["out"])){
    $p_out = htmlspecialchars($_POST["out"],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_POST["reservation_id"])){
    $p_reservation_id = htmlspecialchars($_POST["reservation_id"],ENT_QUOTES,'UTF-8');
    }
  }

  $g_edit="";
  $g_del="";
  if($_GET) {
    if(!empty($_GET['edit'])){
    $g_edit = htmlspecialchars($_GET['edit'],ENT_QUOTES,'UTF-8');
    }
    if(!empty($_GET['del'])){
      $g_del = htmlspecialchars($_GET['del'],ENT_QUOTES,'UTF-8');
    }
  }

?>

