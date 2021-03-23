<!-- 予約作成ページ・予約作成の確認ページ・再予約ページ・再予約の確認ページ・予約作成の完了ページ・予約一覧ページ -->
<?php 
require_once("model_db.php");
  class User2 extends DB {

    //参照　ユーザの予約情報取得
    public function findById($id) {
      $sql="SELECT * FROM reservations WHERE id=:id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":id"=>$id);
      $stmt->execute($params);
      $res_id=$stmt->fetch();
      return $res_id;
    }

    //参照　ユーザの会員情報取得
    public function findMenber($id) {
      $sql="SELECT * FROM menbers WHERE id=:id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":id"=>$id);
      $stmt->execute($params);
      $result=$stmt->fetch();
      return $result;
    }

    //参照　IN/OUT日から空き部屋取得
    public function findBydate($date1,$date2) {
      $sql="SELECT DISTINCT r.room,r.beds,r.id FROM rooms r LEFT JOIN reservations v on v.rooms_id=r.id WHERE r.id NOT IN (SELECT v.rooms_id FROM reservations v WHERE v.check_in=:date1 OR v.check_out=:date2 OR(v.check_in < :date1 AND v.check_out > :date2) OR (v.check_in > :date1 AND v.check_out < :date2) OR (:date2 > v.check_in AND v.check_in > :date1) OR (:date1 < v.check_out AND v.check_out < :date2)) ORDER BY r.id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":date1"=>$date1,":date2"=>$date2);
      $stmt->execute($params);
      $result=$stmt->fetchAll();
      return $result;
    }

    //参照　IN/OUT日から空き部屋取得　※変更用(自分の予約は空室扱い)
    public function findBydate2($date1,$date2,$menber) {
      $sql="SELECT DISTINCT r.room,r.beds,r.id FROM rooms r LEFT JOIN reservations v on v.rooms_id=r.id WHERE r.id NOT IN (SELECT v.rooms_id FROM reservations v WHERE v.menbers_id!=:menber AND ( v.check_in=:date1 OR v.check_out=:date2 OR(v.check_in < :date1 AND v.check_out > :date2) OR (v.check_in > :date1 AND v.check_out < :date2) OR (:date2 > v.check_in AND v.check_in > :date1) OR (:date1 < v.check_out AND v.check_out < :date2))) ORDER BY r.id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":date1"=>$date1,":date2"=>$date2,":menber"=>$menber);
      $stmt->execute($params);
      $result=$stmt->fetchAll();
      return $result;
    }

    //参照　フリーワードから予約一覧検索
    public function findByWord($word,$page=0):Array {
      $sql='SELECT l.id,l.check_in,l.check_out,l.requests,(r.id) AS rid,r.room,r.beds,(m.id) AS mid ,m.name,m.tel,m.email,(SELECT COUNT(l.id) FROM reservations l INNER JOIN rooms r ON l.rooms_id=r.id INNER JOIN menbers m ON l.menbers_id=m.id WHERE CONCAT (requests,room,beds,name,tel,email) LIKE :word) AS count FROM reservations l INNER JOIN rooms r ON l.rooms_id=r.id INNER JOIN menbers m ON l.menbers_id=m.id WHERE CONCAT (l.requests,room,beds,name,tel,email) LIKE :word ORDER BY l.check_in desc LIMIT 10 OFFSET '.(10*$page);
      $stmt=$this->connect->prepare($sql);
      $params=array(":word"=>$word);
      $stmt->execute($params);
      $result=$stmt->fetchAll();
      return $result;
    }

    //参照　日付から予約一覧検索
    public function findByInOut($arr,$page=0):Array {
      $sql='SELECT l.id,l.check_in,l.check_out,l.requests,(r.id) AS rid,r.room,r.beds,(m.id) AS mid ,m.name,m.tel,m.email,(SELECT COUNT(l.id) FROM reservations l INNER JOIN rooms r ON l.rooms_id=r.id INNER JOIN menbers m ON l.menbers_id=m.id WHERE (l.check_in >= :indate AND l.check_out <= :outdate)) AS count FROM reservations l INNER JOIN rooms r ON l.rooms_id=r.id INNER JOIN menbers m ON l.menbers_id=m.id WHERE (l.check_in >= :indate AND l.check_out <= :outdate) ORDER BY l.check_in asc LIMIT 10 OFFSET '.(10*$page);
      $stmt=$this->connect->prepare($sql);
      $params=array(":indate"=>$arr['in'],":outdate"=>$arr['out']);
      $stmt->execute($params);
      $result=$stmt->fetchAll();
      return $result;
    }

    //参照　予約番号から予約一覧検索
    public function findByReservId($id) {
      $sql='SELECT l.id,l.check_in,l.check_out,l.requests,(r.id) AS rid,r.room,r.beds,(m.id) AS mid,m.name,m.tel,m.email FROM reservations l INNER JOIN rooms r ON l.rooms_id=r.id INNER JOIN menbers m ON l.menbers_id=m.id WHERE l.id=:id';
      $stmt=$this->connect->prepare($sql);
      $params=array(":id"=>$id);
      $stmt->execute($params);
      $result=$stmt->fetch();
      return $result;
    }
    
    //参照　同日同部屋の予約ないかチェック
    public function beforeChk($arr) {
      $sql="SELECT id FROM reservations WHERE check_in=:check_in AND check_out=:check_out AND rooms_id=:rooms_id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":check_in"=>$arr["check_in"],":check_out"=>$arr["check_out"],":rooms_id"=>$arr["rooms_id"]);
      $stmt->execute($params);
      $result=$stmt->fetchAll();
      return $result;
    }

    //登録　予約登録
    public function add($arr){
      $sql="INSERT INTO reservations (check_in,check_out,requests,rooms_id,menbers_id) VALUES(:check_in,:check_out,:requests,:rooms_id,:menbers_id)";
      $stmt =$this->connect->prepare($sql);
      $params=array(":check_in"=>$arr["check_in"],":check_out"=>$arr["check_out"],":requests"=>$arr["requests"],":rooms_id"=>$arr["rooms_id"],":menbers_id"=>$arr["menbers_id"]);
      $stmt->execute($params);
    }

    //編集　予約変更
    public function edit($arr) {
      $sql="UPDATE reservations SET check_in=:check_in,check_out=:check_out,requests=:requests,rooms_id=:rooms_id,menbers_id=:menbers_id,updated=:updated WHERE id=:id";
      $stmt=$this->connect->prepare($sql);
      $params=array(":id"=>$arr["id"],":check_in"=>$arr["check_in"],":check_out"=>$arr["check_out"],":requests"=>$arr["requests"],":rooms_id"=>$arr["rooms_id"],":menbers_id"=>$arr["menbers_id"],":updated"=>date("Y-m-d H:i:s"));
      $stmt->execute($params);
    }

    //削除　予約削除
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