<?php

if(!empty($_POST)){
  if ($_POST['radiobutton'] === "戌") {
    echo "正解!";
  }else{
    echo "不正解!";
  }
}
?>

<html>
  <head>
  </head>

  <body>
    <h3><?php echo '来年（2018年）の干支は？'; ?></h3>
    <form action="question2.php" method="post">
      <input name="radiobutton" type="radio" value="戌">戌
      <input name="radiobutton" type="radio" value="辰">辰
      <input name="radiobutton" type="radio" value="亥">亥
      <input name="radiobutton" type="radio" value="卯">卯

      <input type="submit" name="Submit" value="送信"/>
    </form>
  </body>
</html>
