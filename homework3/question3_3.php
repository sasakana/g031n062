<html>
  <head>
  </head>

  <body>
    <h3><?php echo '興味のある研究分野を選択してください（複数選択可）'; ?></h3>
    <form action="result.php" method="post">
      <input name="question3[]" type="checkbox" value="教育">教育
      <input name="question3[]" type="checkbox" value="観光">観光
      <input name="question3[]" type="checkbox" value="農業">農業
      <input name="question3[]" type="checkbox" value="LS">LS
      <input name="question3[]" type="checkbox" value="地域">地域

      <input type="hidden" name="q1_answer" value=<?php echo $_POST["q1_answer"] ?>>
      <input type="hidden" name="q2_answer" value=<?php echo $_POST["question2"] ?>>

      <input type="submit" name="Submit" value="送信"/>
    </form>
  </body>
</html>
