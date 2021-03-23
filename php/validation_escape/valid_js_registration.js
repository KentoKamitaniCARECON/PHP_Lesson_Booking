//JSバリデーション　会員作成時の全ユーザ情報バリデ

 $(function(){
   $("input[type='submit']").on("click",function(){
     var name = $("input[name='name']").val();
     var address = $("input[name='address']").val();
     var tel = $("input[name='tel']").val();
     var birth = $("input[name='birth']").val();
     var email = $("input[name='email']").val();
     var password = $("input[name='password']").val();
     var error = ["以下の入力欄に不備があります（"];
      if(name=="" || (name.length > 32) ){
         error.push("氏名欄");
         $("form").attr("action","r_registration.php");
       }
      if(address=="" || (address.length > 128) ){
        error.push("住所欄");
        $("form").attr("action","r_registration.php");
       }
       if(tel=="" || !(tel.match(/^[0-9０-９]{1,20}$/)) ){
         error.push("電話欄");
         $("form").attr("action","r_registration.php");
       } 
       if(birth==""){
         error.push("生年月日欄");
         $("form").attr("action","r_registration.php");
       }
       if(email==""||!(email.match(/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/)) ){
         error.push("メールアドレス欄");
         $("form").attr("action","r_registration.php");
       } 
       if(password==""||!(password.match(/^[a-zA-Z0-9]+$/)) ){
         error.push("パスワード欄");
         $("form").attr("action","r_registration.php");
       } 
       if(error.length > 1){
         alert (error+"）");
       } else {
         var OK="問題なし";
         $("form").attr("action","r_registration_confirm.php");
       }
   })
 })
