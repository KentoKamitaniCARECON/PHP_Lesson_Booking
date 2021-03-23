//JSバリデーション　名前・住所・電話・誕生日

 $(function(){
   $("input[type='submit']").on("click",function(){
     var name = $("input[name='name']").val();
     var address = $("input[name='address']").val();
     var tel = $("input[name='tel']").val();
     var birth = $("input[name='birth']").val();
     var error = ["以下の入力欄に不備があります（"];
      if(name=="" || (name.length > 32) ){
         error.push("氏名欄");
       }
      if(address=="" || (address.length > 128) ){
        error.push("住所欄");
       }
       if(tel=="" || !(tel.match(/^[0-9０-９]{1,20}$/)) ){
         error.push("電話欄");
       } 
       if(birth==""){
         error.push("生年月日欄");
       } 
       if(error.length > 1){
         alert (error+"）");
       } else {
         var OK="問題なし";
       }
   })
 })
