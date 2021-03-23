//JSバリデーション　　メール・パスワード

 $(function(){
   $("input[type='submit']").on("click",function(){
     var email = $("input[name='email']").val();
     var password = $("input[name='password']").val();
     var error = ["以下の入力欄に不備があります（"];
       if(email==""||!(email.match(/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/)) ){
         error.push("メールアドレス欄");
       } 
       if(password==""||!(password.match(/^[a-zA-Z0-9]+$/)) ){
         error.push("パスワード欄");
       } 
       if(error.length > 1){
         alert (error+"）");
       } else {
         var OK="問題なし";
       }
   })
 })
