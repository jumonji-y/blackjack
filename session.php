<?php
// sessionスタート
session_start();

// もしポスト送信（送信ボタン）があれば
if (!empty($_POST["submit"])) {
  // セッションに値を詰め直す
    $_SESSION["name"] = $_POST["name"];
    // カウントを増やす
    $_SESSION["count"] = $_SESSION["count"]+1;
}else{
    // もしポスト送信（送信ボタン）がなければ
    // $_SESSION["name"]は何も入れない
    $_SESSION["name"] = '';
}
?>



<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
<!-- もし$_SESSION["name"]が空なら、カウントは0にリセット -->
  <?php if(empty($_SESSION['name'])){
    $_SESSION['count'] = 0;
  ?>

  <form action="" method="POST">
      <p> あなたの名前を教えて下さい。</p>
      <input type="text" name="name" value="">
      <input type="submit" name="submit" value="送信">
  </form>
<?php }else{ ?>

<!-- もし$_SESSION["name"]が入っていたら、名前を表示して、何回表示させたかをカウントする -->
<p>こんにちは！  <?php echo $_SESSION["name"] ?>さん</p>
<p><?php echo $_SESSION["count"]; ?>回目</p>

<form action="" method="POST">
      <p> あなたの名前を教えて下さい。</p>
      <input type="text" name="name" value="">
      <input type="submit" name="submit" value="送信">
  </form>

<?php } ?>

</body>
</html>