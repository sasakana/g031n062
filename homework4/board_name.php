<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'In7+HUsLXu'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message'])) {
    $mysqli->query("insert into `messages` (`body`, `name`) values ('{$_POST['message']}','{$_POST['name']}')");
    $result_message = 'データベースに登録しました！';
  } else {
    $result_message = 'メッセージを入力してください...';
  }
}


$result = $mysqli->query('select * from `messages` order by `id` desc');

?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body>
    <p><?php echo $result_message; ?></p>
    <form action="board_name.php" method="post">
      <?php echo "名前 : <br />"?><input type="text" name="name" size="30" /><?php echo "<br />"; ?>
      <?php echo "<br />";?>
      <?php echo "メッセージ : <br />"?><input type="text" name="message" size="30" /><?php echo "<br />";?>
      <?php echo "<br />";?>
      <input type="submit" name="Submit" value="送信"/>
    </form>

    <h2><?php echo "投稿一覧" ?></h2>

    <table border="1" >
      <tr>
        <td><?php echo "名前"; ?></td><td><?php echo "メッセージ"; ?></td><td><?php echo "投稿日時 <br />";?></td>
      </tr>

    <?php foreach ($result as $row) : ?>
      <tr>
        <td><?php echo $row['name']; ?></td><td><?php echo $row['body']; ?></td><td><?php echo $row['timestamp']; echo "<br />"; ?></td>
      </tr>
    <?php endforeach; ?>
    </table>

  </body>
</html>
