<?php

$db_user = 'root';     // ユーザー名
$db_pass = 'In7+HUsLXu'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);


$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // メッセージと名前が空ではない時、フォームで受け取ったメッセージをデータベースに登録
  if ((!empty($_POST['message'])) and (!empty($_POST['name'])and (!empty($_POST['password'])))) {
    //名前、メッセージが特殊文字でも表示できるようにする
    $message = htmlspecialchars($_POST['message']);
    $name = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);

    $body = $mysqli->real_escape_string($_POST['message']);
    $name = $mysqli->real_escape_string($_POST['name']);

    $mysqli->query("insert into `messages` (`body`, `name`, `password`,`thread_id`)
    values (('{$body}'),('{$name}'),('{$_POST['password']}'),('{$_GET['id1']}'))");
    $result_message = 'メッセージを登録しました！';
  }elseif(empty($_POST['message'])){  //メッセージが空の時
    $result_message = 'メッセージを入力してください...';
  }elseif(empty($_POST['name'])){  //名前が空の時
    $result_message = '名前を入力してください...';
  }else{  //名前が空の時
    $result_message = 'パスワードを入力してください...';
  }


  //編集
  if (!empty($_POST['ins'])) {
    $result = $mysqli->query("select * from `messages` where `id`={$_POST['ins']}");
    foreach($result as $row){
      //指定されたパスワードと入力したパスワードが一致し、編集するメッセージが入力されているとき
      if(($row['password'])===($_POST['pass']) and (!empty($_POST['body']))){

        $body = htmlspecialchars($_POST['body']);

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
      //指定されたパスワードと入力したパスワードが一致するとき
      if(($row['password'])===($_POST['pass'])){
        $mysqli->query("delete from `messages` where `id` = {$_POST['del']}");
        $result_message = 'メッセージを削除しました';
      }else{  //指定されたパスワードと入力したパスワードが一致しないとき
        $result_message = 'パスワードが間違っています。';
      }
    }
  }
}

$query = "select `thread`.`thread_name`, messages.* from `thread` inner join `messages` on `thread`.`id` = `messages`.`thread_id`
where `thread`.`id`={$_GET['id1']} order by `messages`.`id` desc";
$result = $mysqli->query($query);


?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body bgcolor="aliceblue">
    <center><h3><?php echo $result_message; ?></h3>
      <h3>投稿フォーム</h3>
      <table frame="hsides">
        <tr>
          <td><form action ="thread_contents.php?id1=<?php echo $_GET['id1'] ?>" method="post">
            名前：</br><input type="text" name="name" size="30" /></br>
            投稿内容：</br><input type="text" name="message" size="30" /></br>
            パスワード：</br><input type="password" name="password" size="30" /></br>

            <input type="submit"  value="投稿する"/>
          </form></td>
        </tr>
      </table>
    </center>

    <h3>投稿一覧</h3>

    <table border="1" >
      <tr>
        <th>名前</th><th>投稿内容</th><th>投稿の編集</th><th>削除</th><th>最終投稿日時</th>
      </tr>

    <?php foreach ($result as $row) : ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php $body = htmlspecialchars($row['body']); ?>
          <span><?php echo $body; ?></span></td>
        <td>
          <form action="thread_contents.php?id1=<?php echo $_GET['id1'] ?>" method="post">
            編集内容<input type="text" name="body" /></br>
            パスワード<input type="password" name="pass" />
            <input type="hidden" name="ins" value="<?php echo $row['id']; ?>" />
            <input type="submit" value="編集" />
          </form>
        </td>

        <td>
          <form action="thread_contents.php?id1=<?php echo $_GET['id1'] ?>" method="post">
            パスワード<input type="password" name="pass" />
            <input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
            <input type="submit" value="削除" />
          </form>
        </td>
        <td><?php echo $row['timestamp']; ?></br></td>
      </tr>
    <?php endforeach; ?>
    </table></br>

    <a href="thread.php">戻る</a>

  </body>
</html>
