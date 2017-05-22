<?php

if(!empty($_POST)){
  if ($_POST['answer'] == NULL) {
    echo "答えを入力してください。";
  }
  elseif ($_POST['answer'] !== NULL and $_POST['answer'] === '水戸' or $_POST['answer'] === '水戸市' or $_POST['answer'] === 'みとし' or $_POST['answer'] === 'みと' or $_POST['answer'] === 'mito') {
    echo "正解！さすがです！！";
  }else{
    echo "間違いです。。。もう一度考えてみてください！";
  }
}
?>

<html>
  <head>
  </head>

  <body>
    <h3><?php echo '茨城県の県庁所在地は？'; ?></h3>
    <form action="question1.php" method="post">
      <input type="text"     name="answer" />
      <input type="submit" name="Submit" value="送信"/>
    </form>
  </body>
</html>
