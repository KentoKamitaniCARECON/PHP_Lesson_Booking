<!-- ログイン1・ログイン2・会員一覧・マイページ・会員登録ページ・会員登録の確認ページ・会員登録の完了ページ・管理者作成ページ・管理者作成の完了ページ -->
<?php 
require_once("model_db.php");
  class User extends DB {
    
    //ログイン　メールとパスワードによるログイン
    public function login($arr) {
      $sql="SELECT * FROM menbers WHERE email =:email AND password=:password";
      $stmt=$this->connect->prepare($sql);
      $params=array(":email"=>$arr["email"],":password"=>$arr["password"]);
      $stmt->execute($params);
      $result=$stmt->fetch();
      return $result;
    }
    //ログイン2　ID/パスワードを失念時のログイン
    public function login2($arr) {
      $sql="SELECT * FROM menbers WHERE name=:name AND address=:address AND tel=:tel AND birth=:birth";
      $stmt=$this->connect->prepare($sql);
      $params=array(":name"=>$arr["name"],":address"=>$arr["address"],":tel"=>$arr["tel"],":birth"=>$arr["birth"]);
      $stmt->execute($params);
      $result=$stmt->fetch();
      return $result;
    }
    
    //参照　ユーザ番号から会員情報取得
    public function findById($id) {
      $sql="SELECT * FROM menbers WHERE id=:id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":id"=>$id);
      $stmt->execute($params);
      $result=$stmt->fetch();
      return $result;
    }

    //参照　フリーワードによる会員一覧検索
    public function findByWord($word,$page=0):Array {
      $sql='SELECT *,(SELECT count(id) FROM menbers WHERE CONCAT (name,address,tel,birth,email,role) LIKE :word) AS count FROM menbers WHERE CONCAT (name,address,tel,birth,email,role) LIKE :word LIMIT 5 OFFSET '.(5*$page);
      $stmt=$this->connect->prepare($sql);
      $params=array(":word"=>$word);
      $stmt->execute($params);
      $result=$stmt->fetchAll();
      return $result;
    }

    //参照　会員の予約経歴取得
    public function findHistory($id,$page2=0):Array{
      $sql='SELECT r.id,r.check_in,r.check_out,r.requests,l.beds,l.room,(SELECT count(id) FROM reservations WHERE menbers_id=:id) AS count FROM reservations r LEFT JOIN rooms l ON r.rooms_id=l.id WHERE menbers_id=:id ORDER BY r.check_in desc LIMIT 10 OFFSET '.(10*$page2);
      $stmt=$this->connect->prepare($sql);
      $params=array(":id"=>$id);
      $stmt->execute($params);
      $book_count=$stmt->fetchAll();
      return $book_count;
    }

    //参照　他ユーザのメールアドレスと重複ないか確認
    public function findbyEmail($email) {
      $sql="SELECT * FROM menbers WHERE email=:email";
      $stmt=$this->connect->prepare($sql);
      $params=array(":email"=>$email);
      $stmt->execute($params);
      $duplicate=$stmt->fetchAll();
      return $duplicate;
    }

    //登録　会員登録
    public function add($arr){
      $sql="INSERT INTO menbers (name,address,tel,birth,email,password,role) VALUES(:name,:address,:tel,:birth,:email,:password,:role)";
      $stmt =$this->connect->prepare($sql);
      $params=array(":name"=>$arr["name"],":address"=>$arr["address"],":tel"=>$arr["tel"],":birth"=>$arr["birth"],":email"=>$arr["email"],":password"=>$arr["password"],":role"=>2);
      $stmt->execute($params);
    }

    //登録　管理者の登録
    public function addManager($arr){
      $sql="INSERT INTO menbers (name,address,tel,birth,email,password,role) VALUES(:name,:address,:tel,:birth,:email,:password,:role)";
      $stmt =$this->connect->prepare($sql);
      $params=array(":name"=>$arr["name"],":address"=>$arr["address"],":tel"=>$arr["tel"],":birth"=>$arr["birth"],":email"=>$arr["email"],":password"=>$arr["password"],":role"=>1);
      $stmt->execute($params);
    }

    //編集　会員情報の編集
    public function edit($arr) {
      $sql="UPDATE menbers SET name=:name,address=:address,tel=:tel,birth=:birth,email=:email,password=:password,role=:role,other=:other,updated=:updated WHERE id=:id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":id"=>$arr["id"],":name"=>$arr["name"],":address"=>$arr["address"],":tel"=>$arr["tel"],":birth"=>$arr["birth"],":email"=>$arr["email"],":password"=>$arr["password"],":role"=>$arr["role"],":other"=>$arr["other"],":updated"=>date("Y-m-d H:i:s"));
      $stmt->execute($params);
    }

    //削除　会員の削除
    public function deleteMenber($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM menbers WHERE id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }
    
    //削除　会員を削除と同時に予約も削除
    public function del_Res($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM reservations WHERE menbers_id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }

    //削除　会員を削除と同時に投稿も削除
    public function del_Com($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM comments WHERE menbers_id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }

    //削除　会員を削除と同時にそのユーザのGOODも削除
    public function del_Like($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM like_comments WHERE menbers_id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }

    //削除　予約の削除
    public function deleteReservation($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM reservations WHERE id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }
  }
?>