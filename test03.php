<?php
// 初期処理
ini_set('log_errors', 'on'); // ログを取るか
ini_set('error_log', 'php_my.php');  //ログの出力ファイルを指定

session_start();
// ユーザーとディーラーの持ち手を初期化
$player_hands = array();
$dealer_hands = array();
// ゲームを終了するかどうかのフラグを立てる
// falseなら続行、trueなら終了
$end_game_flg = false;

// 山札を準備する関数
function init_cards()
{
    $cards = array();
    $numbers  = [1,2,3,4,5,6,7,8,9,10,11,12,13];
    $marks = ['ハート','ダイヤ','スペード','クラブ'];

    foreach ($marks as $mark) {
        foreach ($numbers as $number) {
            // for文でもいいかも？1~13を回す感じで。
            $cards[] = array(
            'number' => $number,
            'mark' => $mark
            );
        }
    }
    shuffle($cards);
    return $cards;
}


// 持ち札の合計を出すための関数
function sum_hands_cards($hand_cards)
{
    $total = 0;
    foreach ($hand_cards as $hand_card) {
        $total += $hand_card['number'];
    }
    return $total;
}

// ゲームの勝敗を宣言してくれる関数
function sayJudge($my_total_hands, $opp_total_hands)
{
    // ゲームが終了し、自分の手札が21以上だった場合
    if ($end_game_flg === true && $my_total_hands > 21) {
        echo "残念！負けです";
    // ゲームが終了し、自分の持ち札が21以下で、かつ、自分の手札が相手の手札より強い場合
    } elseif ($end_game_flg === true && $my_total_hands < 21 && $my_total_hands > $opp_total_hands) {
        echo "勝ちです！おめでとう！";
    } elseif ($end_game_flg === true && $my_total_hands < 21 && $my_total_hands < $opp_total_hands) {
        echo "残念！負けです";
    }
}

// ゲームの勝敗判定
// プレーヤーかディーラーの持ち札が21を超えていたら、ゲーム終了
function gameJudge()
{
    if ($total_player_hands > 21 || $total_dealer_hands > 21) {
        $end_game_flg = true;
    } else {
        $end_game_flg = false;
    }
    return $end_game_flg;
}


// ディーラーの思考回路
function decideDealerTurn()
{
    // もしPOST送信（ヒットか、スタンド）があったら
    if (!empty($_POST['hit']) || !empty($_POST['stand'])) {
        // ディーラーがカードを引くかどうか判断するための閾値を設定
        $set_value = 16;
        // 持ち札が16以下なら、ヒット(一枚引く)
        if ($total_dealer_hands <= $set_value) {
            $dealer_hands[] = array_shift($cards);
            $total_dealer_hands = sum_hands_cards($dealer_hands);
        // POST送信（スタンド）があったら、ゲーム終了（勝敗判定）
        } elseif (!empty($_POST['stand'])) {
            $end_game_flg = true;
        }
    }
}

// 初期画面作成処理
function startGame(){
   // 山札の準備
      $cards = init_cards();

      // プレーヤーとディーラーに2枚ずつ配る
      for ($i = 1; $i < 3; $i++) {
          $player_hands[] = array_shift($cards);
          $dealer_hands[] = array_shift($cards);
      }

      // プレーヤーと、ディーラーの持ち札の合計を算出
      $total_player_hands = sum_hands_cards($player_hands);
      $total_dealer_hands = sum_hands_cards($dealer_hands);

}


// ゲームの処理の流れ
  // startGame();
  if (empty($_SESSION)) {

  // 山札の準備
      $cards = init_cards();

      // プレーヤーとディーラーに2枚ずつ配る
      for ($i = 1; $i < 3; $i++) {
          $player_hands[] = array_shift($cards);
          $dealer_hands[] = array_shift($cards);
      }

      // プレーヤーと、ディーラーの持ち札の合計を算出
      $total_player_hands = sum_hands_cards($player_hands);
      $total_dealer_hands = sum_hands_cards($dealer_hands);

      $_SESSION['cards'] = $cards;
      $_SESSION['player_cards'] = $player_hands;
      $_SESSION['dealer_cards'] = $dealer_hands;

      gameJudge();

      var_dump($player_hands);
      // }else{
  }
   
    // もしPOST送信(ヒット)されていたら
   if (!empty($_POST['hit'])) {
    $cards = $_SESSION['cards'];
    $player_hands = $_SESSION['player_cards'];
    $dealer_hands = $_SESSION['dealer_cards'];

    // if (!empty($_SESSION)) {
    //     $cards = $_SESSION['cards'];
    //     $player_hands = $_SESSION['player_cards'];
    //     $dealer_hands = $_SESSION['dealer_cards'];
    // }
    // カードをもう一枚引く
    shuffle($cards);
    $player_hands[] = array_shift($cards);
    $_SESSION['player_cards'] += $player_hands;

    // カードの数を足す
    $total_player_hands = sum_hands_cards($player_hands);
    $total_dealer_hands = sum_hands_cards($dealer_hands);

    var_dump($_SESSION['player_cards']);
    // ゲームの勝敗判定
    // プレーヤーかディーラーの持ち札が21を超えていたら、ゲーム終了
    if ($total_player_hands > 21 || $total_dealer_hands > 21) {
        $end_game_flg = true;
    } else {
        $end_game_flg = false;
    }
}
// もしリスタートボタンが押されたら
 if(!empty($_POST['restart'])){
    $_SESSION = array();
    startGame();
 }
  // もしスタンドボタンを押されたら
  if (!empty($_POST['stand'])) {
  
     $cards = $_SESSION['cards'];
     $player_hands = $_SESSION['player_cards'];
     $dealer_hands = $_SESSION['dealer_cards'];

      // ディーラーの次の手を判断する
      decideDealerTurn();

      // ゲームの勝敗判定
      // プレーヤーかディーラーの持ち札が21を超えていたら、ゲーム終了
      if ($total_player_hands > 21 || $total_dealer_hands > 21) {
          $end_game_flg = true;
      } else {
          $end_game_flg = false;
      }

      // カードの数を足す
      $total_player_hands = sum_hands_cards($player_hands);
      $total_dealer_hands = sum_hands_cards($dealer_hands);

      $_SESSION['cards'] = $cards;
      $_SESSION['player_cards'] = $player_hands;
      $_SESSION['dealer_cards'] = $dealer_hands;
      //         // if ($total_player_hands > 21 || $total_dealer_hands > 21) {
    //         // $end_game_flg = true;
    //         // }

    //         // sessionに値を詰める
    //         $_SESSION["name"] = $user_name;
    //         $_SESSION['$cards'] = $cards;
    //         $_SESSION['player_cards'] = $player_hands;
    //         $_SESSION['dealer_cards'] = $dealer_hands;
    //     }
  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>BlackJack</title>
</head>
<body>

<p>
ディーラーの手札：
<?php
foreach ($dealer_hands as $card) {
    echo '【'.$card['mark'].'の'.$card['number'].'】  ';
}
?>
<br>
合計：
<?php
 echo $total_dealer_hands;
?>
<hr>
<p>
あなたの手札：
<?php
foreach ($player_hands as $card) {
    echo '【'.$card['mark'].'の'.$card['number'].'】  ';
}

?>
<br>
合計：
<?php
 echo $total_player_hands;

 sayJudge($total_player_hands, $total_dealer_hands);

?>
</p>


<form action="" method="post">
 
 <?php if ($end_game_flg === true) {?>

   <!-- restart:試合キャンセル -->
  <input type="submit" name="restart" value="RESTART">

  <?php } else { ?>

  <!-- hit:もう一枚引く -->
  <input type="submit" name="hit" value="HIT">
  <!-- stand:カードを引かずに結果を見る -->
  <input type="submit" name="stand" value="STAND">
  <!-- restart:試合キャンセル -->
  <input type="submit" name="restart" value="RESTART">

  <?php } ?>

</form>

</body>
</html>
