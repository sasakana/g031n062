<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'In7+HUsLXu'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$mysqli->set_charset('utf8');

$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // メッセージと名前が空ではない時、フォームで受け取ったメッセージをデータベースに登録
  if ((!empty($_POST['title'])) and (!empty($_POST['thread_pass']))) {

    $title = htmlspecialchars($_POST['title']);
    $thread_pass = htmlspecialchars($_POST['thread_pass']);

    $title = $mysqli->real_escape_string($_POST['title']);
    $thread_pass = $mysqli->real_escape_string($_POST['thread_pass']);

    $mysqli->query("insert into `thread` (`title`, `thread_pass`)
    values (('{$title}'),('{$thread_pass}'))");
    $result_message = 'スレッドを追加しました！';
  }elseif(empty($_POST['title'])){
    $result_message = 'スレッド名を入力してください';
  }else{
    $result_message = 'パスワードを入力してください';
  }

  //削除
  if (!empty($_POST['del'])) {
    $result = $mysqli->query("select * from `thread` where `id`={$_POST['del']}");
    foreach ($result as $row) {
      //指定されたパスワードと入力したパスワードが一致するとき
      if(($row['thread_pass'])===($_POST['pass'])){
        $mysqli->query("delete from `thread` where `id` = {$_POST['del']}");
        $result_message = 'スレッドを削除しました';
      }elseif(empty($_POST['pass'])){  //パスワードが空の時
        $result_message = 'パスワードを入力してください';
      }else{  //指定されたパスワードと入力したパスワードが一致しないとき
        $result_message = 'パスワードが間違っています';
      }
    }
  }
}

//idを降順に並び替え
$result = $mysqli->query('select * from `thread` order by `id` desc');

?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body bgcolor="aliceblue">
    <center><h3><?php echo $result_message; ?></br></h3>
    <h3>スレッドの追加</h3>
    <table frame="hsides">
      <tr>
        <td><form action="thread.php" method="post">
          スレッド名 : </br><input type="text" name="title" size="30" /></br>
          パスワード : </br><input type="password" name="thread_pass" size="30" /></br>
          <input type="submit" name="Submit" value="送信"/>
        </form></td>
      </tr>
    </table></center>

    <h2>スレッド一覧</h2>

    <table rules="rows" width="500">
      <tr>
        <th>スレッド名</th><th>削除(パスワードを入力)</th><th>作成日時</th>
      </tr>

    <?php foreach ($result as $row) : ?>
      <tr>
        <td>
          <a href="thread_contents.php?thread_id=<?php echo $row['id']; ?>&title=<?php echo $row['title']; ?>">
            <?php $title = htmlspecialchars($row['title']); ?>
            <span><?php echo $title; ?></span>

          </a>
        </td>

        <td>
          <form action="thread.php" method="post" >
            <input type="password" name="pass" />
            <input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
            <input type="submit" value="削除" />
          </form>
        </td>

        <td><?php echo $row['thread_time'];?></br></td>
      </tr>
    <?php endforeach; ?>
    </table>

  </body>
</html>
