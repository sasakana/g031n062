<html>
  <head>
  </head>

  <body>
    <h3><?php echo '普段は？'; ?></h3>
    <form action="question3_3.php" method="post">
      <input name="question2" type="radio" value="裸眼">裸眼
      <input name="question2" type="radio" value="メガネ">メガネ
      <input name="question2" type="radio" value="コンタクト">コンタクト
      <input name="question2" type="radio" value="時と場合による">時と場合による

      <input type="hidden" name="q1_answer" value=<?php echo $_POST["question1"] ?>>

      <input type="submit" name="Submit" value="送信"/>
    </form>
  </body>
</html>
