<?php
$study = "";
foreach ($_POST["question3"] as $key => $value) {
    $study = $study." ".$value;
}
?>

<html>
  <head>
  </head>

  <body>
    <ul>
    <h3><li><?php echo 'あなたの好きな季節は？ [回答] : ' . $_POST["q1_answer"]; ?></li></h3>
    <h3><li><?php echo '普段は？ [回答] : ' . $_POST["q2_answer"]; ?></li></h3>
    <h3><li><?php echo 'あなたの興味のある研究分野は？ [回答] : ' . $study; ?></li></h3></ul>
  </body>
</html>
