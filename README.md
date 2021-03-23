# PHP_Lesson_Booking
PHP_自作 ホテル等の顧客管理・予約管理
# 使い方
ユーザ種類：3<br>
1.管理者、2.一般ユーザ、3.不良客ユーザ


-**管理者
  空室検索・予約の作成・マイページの利用が可能<br>
トップページお知らせ欄の投稿と削除が可能<br>
顧客一覧・予約一覧の閲覧/編集/削除が可能<br>
他の管理者ユーザの作成が可能<br>
アバウトアスページの投稿の送信・削除が可能<br>

メールアドレス→ kanrisha@gmail.com<br>
パスワード→ 777


-**一般ユーザ

空室検索・予約の作成・マイページの利用が可能<br>
アバウトアスページの投稿の送信が可能<br>

メールアドレス→ momotaro@gmail.com<br>
メールアドレス→ inu@gmail.com<br>
メールアドレス→ saru@gmail.com<br>
メールアドレス→ kiji@gmail.com<br>
メールアドレス→ murabitoa@gmail.com<br>
メールアドレス→ murabitob@gmail.com<br>
全員共通パスワード→ 777


-**不良客ユーザ

管理者が一般ユーザから不良客ユーザに変更が可能<br>
ログインと空室検索は出来るが、予約の作成は不可<br>
既に持っている予約の変更も不可<br>

メールアドレス→ oni@gmail.com<br>
パスワード→ 777

# 環境
MAMP/MySQL/PHP

# データベース
データベース名：rsv_app
お使いのphpMyAdminに上のデータベースを作り、入っているDB.sqlをインポートしていただければお使いいただけるようになると思います。

・comments:アバウトアスページの投稿用テーブル<br>
・informations:トップページお知らせ欄用テーブル<br>
・like_comments:アバウトアスページの投稿のGOODボタン用テーブル<br>
・menbers:会員用テーブル<br>
・reservations:予約用テーブル<br>
・rooms:全部屋の情報用テーブル
