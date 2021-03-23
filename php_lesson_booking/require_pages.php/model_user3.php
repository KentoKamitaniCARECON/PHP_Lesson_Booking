<!-- トップページ・GOODボタンAJAX・アバウトアス -->
<?php 
require_once("model_db.php");
  class User3 extends DB {

    //参照　トップページお知らせの取得
    public function findAll_info() {
      $sql="SELECT * FROM informations ORDER BY created desc";
      $stmt=$this->connect->prepare($sql);
      $stmt->execute();
      $result=$stmt->fetchAll();
      return $result;
    }

    //参照　ABOUTUS投稿の取得
    public function findAll_comments() {
      $sql="SELECT c.id,c.pen_name,c.comment,c.menbers_id,c.created,count(l.comments_id)AS count FROM like_comments l RIGHT JOIN comments c ON c.id=l.comments_id GROUP BY c.id ORDER BY c.id desc LIMIT 15";
      $stmt=$this->connect->prepare($sql);
      $stmt->execute();
      $result=$stmt->fetchAll();
      return $result;
    }

    //登録　トップページお知らせの登録
    public function add_info($arr){
      $sql="INSERT INTO informations (title,info,created) VALUES(:title,:info,:created)";
      $stmt =$this->connect->prepare($sql);
      $params=array(":title"=>$arr["title"],":info"=>$arr["info"],":created"=>date("Y-m-d"));
      $stmt->execute($params);
    }

    //登録　ABOUTUS投稿を送信
    public function add_comments($arr){
      $sql="INSERT INTO comments (pen_name,comment,menbers_id,created) VALUES(:pen_name,:comment,:menbers_id,:created)";
      $stmt =$this->connect->prepare($sql);
      $params=array(":pen_name"=>$arr["pen_name"],":comment"=>$arr["comment"],":menbers_id"=>$arr["menbers_id"],":created"=>date("Y-m-d"));
      $stmt->execute($params);
    }

    //削除　トップページのお知らせ削除
    public function delete_info($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM informations WHERE id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }

    //削除　ABOUTUS投稿の削除
    public function delete_comments($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM comments WHERE id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }

    //削除　ABOUTUS投稿の削除と同時にGOODも削除
    public function delete_like_comments($id=null) {
      if(isset($id)) {
        $sql="DELETE FROM like_comments WHERE comments_id=:id";
        $stmt=$this->connect->prepare($sql);
        $params=array(":id"=>$id);
        $stmt->execute($params);
      }
    }

    //登録　AJAXグッドの登録
    public function like_add($arr) {
      $sql = 'INSERT INTO like_comments (menbers_id, comments_id, created) VALUES (:menbers_id, :comments_id, :created)';
      $stmt = $this->connect->prepare($sql);
      $params = array(
        ':menbers_id' => $arr['menbers_id'],
        ':comments_id' => $arr['comments_id'],
        ':created' => date("Y-m-d H:i:s")
      );
      $stmt->execute($params);
    }

    //削除　AJAXグッドの削除
    public function like_del($arr) {
      $sql = 'DELETE FROM like_comments WHERE :menbers_id = menbers_id AND :comments_id = comments_id';
      $stmt = $this->connect->prepare($sql);
      $params = array(
        ':menbers_id' => $arr['menbers_id'],
        ':comments_id' => $arr['comments_id']
      );
      $stmt->execute($params);
    }

    //参照　ABOUTUSユーザのGOOD済み投稿の取得
    public function liked_comments($mid,$cid) {
      $sql="SELECT id FROM like_comments WHERE menbers_id=:mid AND comments_id=:cid";
      $stmt=$this->connect->prepare($sql);
      $params=array(":mid"=>$mid,":cid"=>$cid);
      $stmt->execute($params);
      $liked=$stmt->fetch();
      return $liked;
    }
  }
?>