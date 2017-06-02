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
  if ((!empty($_POST['message'])) and (!empty($_POST['name'])and (!empty($_POST['password'])))) {
    //名前、メッセージが特殊文字でも表示できるようにする
    $message = htmlspecialchars($_POST['message']);
    $name = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);

    $body = $mysqli->real_escape_string($_POST['message']);
    $name = $mysqli->real_escape_string($_POST['name']);
    $password = $mysqli->real_escape_string($_POST['password']);

    $mysqli->query("insert into `messages` (`message`, `name`, `password`,`thread_id`)
    values (('{$message}'),('{$name}'),('{$password}'),('{$_GET['thread_id']}'))");
    $result_message = 'メッセージを登録しました！';
  }elseif(empty($_POST['message'])){  //メッセージが空の時
    $result_message = 'メッセージを入力してください';
  }elseif(empty($_POST['name'])){  //名前が空の時
    $result_message = '名前を入力してください';
  }else{  //名前が空の時
    $result_message = 'パスワードを入力してください';
  }


  //編集
  if (!empty($_POST['up'])) {
    $result = $mysqli->query("select * from `messages` where `id`={$_POST['up']}");
    foreach($result as $row){
      //指定されたパスワードと入力したパスワードが一致し、編集するメッセージが入力されているとき
      if(($row['password'])===($_POST['pass']) and (!empty($_POST['message']))){

        $message = htmlspecialchars($_POST['message']);

        //編集された文字が特殊文字でも表示できるようにする
        $body = $mysqli->real_escape_string($_POST['message']);
        $mysqli->query("update `messages` set message = ('{$message}') where `id` = {$_POST['up']}");
        $result_message = 'メッセージを編集しました';
      }elseif(empty($_POST['message'])){  //メッセージが空の時
        $result_message = '編集するメッセージを入力してください';
      }elseif(empty($_POST['pass'])){  //パスワードが空の時
        $result_message = 'パスワードを入力してください';
      }elseif (($row['password'])!=($_POST['pass'])){  //指定されたパスワードと入力したパスワードが一致しないとき
        $result_message = 'パスワードが間違っています';
      }else{  //指定されたパスワードと入力したパスワードは一致するが、メッセージが入力されていないとき
        $result_message = '編集するメッセージを入力してください';
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
      }elseif(empty($_POST['pass'])){  //名前が空の時
        $result_message = 'パスワードを入力してください';
      }else{  //指定されたパスワードと入力したパスワードが一致しないとき
        $result_message = 'パスワードが間違っています';
      }
    }
  }
}

$query = "select `thread`.`title`, messages.* from `thread` inner join `messages` on `thread`.`id` = `messages`.`thread_id`
where `thread`.`id`={$_GET['thread_id']} order by `messages`.`id` desc";
$result = $mysqli->query($query);


?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body bgcolor="aliceblue">
    <center>
      <h2><?php echo "【". $_GET['title']."】のページ"; ?></h2>
      <h3><?php echo $result_message; ?></h3>
      <h3>投稿フォーム</h3>
      <table frame="hsides">
        <tr>
          <td><form action ="thread_contents.php?thread_id=<?php echo $_GET['thread_id'] ?>&title=<?php echo $_GET['title']; ?>" method="post">
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
        <td><?php $message = htmlspecialchars($row['message']); ?>
          <span><?php echo $message; ?></span></td>
        <td>
          <form action="thread_contents.php?thread_id=<?php echo $_GET['thread_id'] ?>&title=<?php echo $_GET['title']; ?>" method="post">
            編集内容<input type="text" name="message" /></br>
            パスワード<input type="password" name="pass" />
            <input type="hidden" name="up" value="<?php echo $row['id']; ?>" />
            <input type="submit" value="編集" />
          </form>
        </td>

        <td>
          <form action="thread_contents.php?thread_id=<?php echo $_GET['thread_id'] ?>&title=<?php echo $_GET['title']; ?>" method="post">
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
