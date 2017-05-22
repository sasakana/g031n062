<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'In7+HUsLXu'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);


$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// メッセージと名前が空ではない時、フォームで受け取ったメッセージをデータベースに登録
  if ((!empty($_POST['message'])) and (!empty($_POST['name']))) {
//名前、メッセージ、パスワードが特殊文字でも表示できるようにする
    $body = $mysqli->real_escape_string($_POST['message']);
    $name = $mysqli->real_escape_string($_POST['name']);
    $password = $mysqli->real_escape_string($_POST['password']);

    $mysqli->query("insert into `messages` (`body`, `name`, `password`)
    values (('{$body}'),('{$name}'),('{$password}'))");
    $result_message = 'データベースに登録しました！';
  }elseif(empty($_POST['message'])){  //メッセージが空の時
    $result_message = 'メッセージを入力してください...';
  }else{  //名前が空の時
    $result_message = '名前を入力してください...';
  }


//編集
  if (!empty($_POST['ins'])) {
    $result = $mysqli->query("select * from `messages` where `id`={$_POST['ins']}");
    foreach($result as $row){
//指定されたパスワードと入力したパスワードが一致し、編集するメッセージが入力されているとき
      if(($row['password'])===($_POST['pass']) and (!empty($_POST['body']))){
//編集された文字が特殊文字でも表示できるようにする    
        $body = $mysqli->real_escape_string($_POST['body']);
        $mysqli->query("update `messages` set body = ('{$body}') where `id` = {$_POST['ins']}");
        $result_message = 'メッセージを編集しました';
      }elseif (($row['password'])!=($_POST['pass'])){  //指定されたパスワードと入力したパスワードが一致しないとき
        $result_message = 'パスワードが間違っています';
      }else{  //指定されたパスワードと入力したパスワードは一致するが、メッセージが入力されていないとき
        $result_message = '編集するメッセージを入力してください。';
      }
    }
  }


//削除
  if (!empty($_POST['del'])) {
    $result = $mysqli->query("select * from `messages` where `id`={$_POST['del']}");
    foreach ($result as $row) {
//指定されたパスワードと入力したパスワードが一致し、編集するメッセージが入力されているとき
      if(($row['password'])===($_POST['pass'])){
        $mysqli->query("delete from `messages` where `id` = {$_POST['del']}");
        $result_message = 'メッセージを削除しました';
      }else{  //指定されたパスワードと入力したパスワードが一致しないとき
        $result_message = 'パスワードが間違っています。';
      }
    }
  }
}


$result = $mysqli->query('select * from `messages` order by `id` desc');

?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body>
    <h3><?php echo $result_message; ?></h3>
    <form action="board_insdel.php" method="post">
      <?php echo "名前 : <br />"?><input type="text" name="name" size="30" /></br>
    </br>
      <?php echo "メッセージ : <br />"?><input type="text" name="message" size="30" /></br>
    </br>
      <?php echo "パスワード : <br />"?><input type="password" name="password" size="30" /></br>
    </br>
      <input type="submit" name="Submit" value="送信"/>
    </form>

    <h2>投稿一覧</h2>

    <table border="1" >
      <tr>
        <td>名前</td><td>メッセージ</td><td>メッセージの編集</td><td>削除</td><td>最終投稿日時</td>
      </tr>

    <?php foreach ($result as $row) : ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['body']; ?></td>
        <td>
          <form action="board_insdel.php" method="post">
            編集内容<input type="text" name="body" /></br>
            パスワード<input type="password" name="pass" />
            <input type="hidden" name="ins" value="<?php echo $row['id']; ?>" />
            <input type="submit" value="編集" />
          </form>
        </td>

        <td>
          <form action="board_insdel.php" method="post">
            パスワード<input type="password" name="pass" />
            <input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
            <input type="submit" value="削除" />
          </form>
        </td>
        <td><?php echo $row['timestamp']; echo "<br />"; ?></td>
      </tr>
    <?php endforeach; ?>
    </table>

  </body>
</html>
