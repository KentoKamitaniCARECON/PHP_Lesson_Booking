<div id=header>
  <div id="header_contents">
    <div id="header_logo">
      <h1><a href="r_top.php">TOP<br>PAGE</a></h1>
    </div>
    <!-- 会員専用 -->
    <?php if($menbers['User']['role'] !=1) :?>
    <div id="header_list1">
      <ul>
      <li><a href="r_mypage.php"><img src="image/mypage.png" alt="mypage"><br>MY PAGE</a></li>
      <li><a href="r_about_us.php"><img src="image/aboutus.png" alt="aboutus"><br>ABOUT US</a></li>
      <li><a href="r_login_case1.php" onClick="if(!confirm('ログアウトしますか？')) return false;"><img src="image/logout.png" alt="logout"><br>LOG OUT</a></li>
      </ul>
    </div>
  <?php else :?>
    <!-- 管理者専用 -->
    <div id="header_list2">
      <ul>
      <li><a href="r_mypage.php"><img src="image/mypage.png" alt="mypage"><br>MY PAGE</a></li>
      <li><a href="r_about_us.php"><img src="image/aboutus.png" alt="aboutus"><br>ABOUT US</a></li>
      <li><a href="r_login_case1.php" onClick="if(!confirm('ログアウトしますか？')) return false;"><img src="image/logout.png" alt="logout"><br>LOG OUT</a></li>
      <li><a href="r_menbers_info.php"><img src="image/menber_info.png" alt="menber_info"><br>会員一覧</a></li>
      <li><a href="r_reservations_info.php"><img src="image/res_info.png" alt="mypage"><br>予約一覧</a></li>
      <li><a href="r_add_manager.php"><img src="image/add_mgr.png" alt="add_mgr"><br>管理者作成</a></li>
      </ul>
    </div>
  <?php endif;?>
  </div>
</div>