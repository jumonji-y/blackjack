<?php
// 初期処理
ini_set('log_errors', 'on'); // ログを取るか
ini_set('error_log', 'php_blackjack.log');  //ログの出力ファイルを指定

session_start();
// ユーザーとディーラーの持ち手を初期化
$player_hands = array();
$dealer_hands = array();
// ゲームを終了するかどうかのフラグを立てる
// falseなら続行、trueなら終了
$end_game_flg = false;
$my_total_hands = 0;
$opp_total_hands = 0;

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
function sayJudge($end_game_flg, $my_total_hands, $opp_total_hands)
{
    // ゲームが終了し、自分の手札が21以上だった場合
    if($end_game_flg == true && $my_total_hands == $opp_total_hands && $my_total_hands <= 21 && $opp_total_hands <=21) {
      echo "引き分けです。";
    // ゲームが終了し、自分の持ち札が21以下で、かつ、自分の手札が相手の手札より強い場合
    } elseif ($end_game_flg == true && $my_total_hands <= 21 && $my_total_hands > $opp_total_hands) {
        echo "勝ちです！おめでとう！";
    } elseif ($end_game_flg == true && $my_total_hands <= 21 && $my_total_hands < $opp_total_hands && $opp_total_hands > 21) {
        echo "勝ちです！おめでとう！";
    } elseif ($end_game_flg == true && $my_total_hands <= 21 && $my_total_hands < $opp_total_hands && $opp_total_hands <= 21) {
        echo "残念！負けです";
    } elseif ($end_game_flg == true && $my_total_hands > 21 && $opp_total_hands <= 21) {
        echo "残念！負けです";
    } elseif ($end_game_flg == true && $my_total_hands > 21 && $opp_total_hands >= 21) {
        echo "引き分けです。両者とも21以上！";
    }
}

// ゲームの勝敗判定
// プレーヤーかディーラーの持ち札が21を超えていたら、ゲーム終了
function gameJudge($total_player_hands,$total_dealer_hands)
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
            return $total_dealer_hands;
        // POST送信（スタンド）があったら、ゲーム終了（勝敗判定）
        } elseif (!empty($_POST['stand'])) {
            return $end_game_flg = true;
        }
    }
}

// 初期画面作成処理
function startGame()
{
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
      $_SESSION['player_hands'] = $player_hands;
      $_SESSION['dealer_hands'] = $dealer_hands;
      $_SESSION['total_dealer_hands'] = $total_dealer_hands;
      $_SESSION['total_player_hands'] = $total_player_hands;

      gameJudge($total_player_hands, $total_dealer_hands);
   echo "エンドフラグ：".$end_game_flg;
}


// ゲームの処理の流れ
  // if (empty($_POST)  || empty($_SESSION)) {
  if (empty($_POST)) {

    global $end_game_flg;
    $_SESSION['message'] = 'ゲームスタート！';

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
      $_SESSION['player_hands'] = $player_hands;
      $_SESSION['dealer_hands'] = $dealer_hands;
      $_SESSION['total_dealer_hands'] = $total_dealer_hands;
      $_SESSION['total_player_hands'] = $total_player_hands;
      $_SESSION['end_game_flg'] = $end_game_flg;

      gameJudge($_SESSION['total_player_hands'],$_SESSION['total_dealer_hands']);
  //  echo "エンドフラグ：".$end_game_flg;
  var_dump(gameJudge($_SESSION['total_player_hands'],$_SESSION['total_dealer_hands']));
      //  var_dump($_SESSION['cards']);
  }
      // もしPOST送信(ヒット)されていたら
      if (!empty($_POST['hit'])) {
          $cards = $_SESSION['cards'];
          $player_hands = $_SESSION['player_hands'];
          $dealer_hands = $_SESSION['dealer_hands'];
          $end_game_flg = false;

          // カードをもう一枚引く
          $player_hands[] = array_shift($_SESSION['cards']);
          $_SESSION['player_hands'] += $player_hands;
          $cards = $_SESSION['cards'];
          var_dump($cards);

          // 手札のカードの数を足す
          $total_player_hands = sum_hands_cards($player_hands);
          $total_dealer_hands = sum_hands_cards($dealer_hands);

          //  decideDealerTurn();
          if (!empty($_POST['hit']) || !empty($_POST['stand'])) {
            // ディーラーがカードを引くかどうか判断するための閾値を設定
            $set_value = 16;
            // 持ち札が16以下なら、ヒット(一枚引く)
            if ($total_dealer_hands <= $set_value) {
                $dealer_hands[] = array_shift($cards);
                $total_dealer_hands = sum_hands_cards($dealer_hands);
                // return $total_dealer_hands;
            // POST送信（スタンド）があったら、ゲーム終了（勝敗判定）
            } elseif (!empty($_POST['stand'])) {
                $end_game_flg = true;
            }
        }

          // ゲームの勝敗判定
          // プレーヤーかディーラーの持ち札が21を超えていたら、ゲーム終了
          if ($total_player_hands > 21 || $total_dealer_hands > 21) {
              $end_game_flg = true;
            } else {
              $end_game_flg = false;
            }

          $_SESSION['cards'] = $cards;
          $_SESSION['player_hands'] = $player_hands;
          $_SESSION['dealer_hands'] = $dealer_hands;
          $_SESSION['total_dealer_hands'] = $total_dealer_hands;
          $_SESSION['total_player_hands'] = $total_player_hands;
          $_SESSION['message'] = 'カードを引いた！';
          $_SESSION['end_game_flg'] = $end_game_flg;

          var_dump($_SESSION['end_game_flg']);
      } elseif (!empty($_POST['restart'])) {
          // もしリスタートボタンが押されたら
          $_SESSION = array();

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

          gameJudge($total_player_hands,$total_dealer_hands);

          $_SESSION['cards'] = $cards;
          $_SESSION['player_hands'] = $player_hands;
          $_SESSION['dealer_hands'] = $dealer_hands;
          $_SESSION['total_dealer_hands'] = $total_dealer_hands;
          $_SESSION['total_player_hands'] = $total_player_hands;
          $_SESSION['message'] = 'ゲーム再スタート！';

        // もしスタンドボタンを押されたら
      } elseif (!empty($_POST['stand'])) {
          $cards = $_SESSION['cards'];
          $player_hands = $_SESSION['player_hands'];
          $dealer_hands = $_SESSION['dealer_hands'];

          // プレーヤーと、ディーラーの持ち札の合計を算出
          $total_player_hands = sum_hands_cards($player_hands);
          $total_dealer_hands = sum_hands_cards($dealer_hands);

          // ディーラーの次の手を判断する
          // decideDealerTurn();
          if (!empty($_POST['hit']) || !empty($_POST['stand'])) {
              // ディーラーがカードを引くかどうか判断するための閾値を設定
              $set_value = 16;
              // 持ち札が16以下なら、ヒット(一枚引く)
              if ($total_dealer_hands <= $set_value) {
                  $dealer_hands[] = array_shift($cards);
                  $total_dealer_hands = sum_hands_cards($dealer_hands);
                  // return $total_dealer_hands;
              // POST送信（スタンド）があったら、ゲーム終了（勝敗判定）
              } elseif (!empty($_POST['stand'])) {
                  $end_game_flg = true;
              }
          }

          // プレーヤーと、ディーラーの持ち札の合計を算出
          $total_player_hands = sum_hands_cards($player_hands);
          $total_dealer_hands = sum_hands_cards($dealer_hands);


          // ゲームの勝敗判定
          // プレーヤーかディーラーの持ち札が21を超えていたら、ゲーム終了
          $end_game_flg = true;

          gameJudge($total_player_hands, $total_dealer_hands);

          $_SESSION['cards'] = $cards;
          $_SESSION['player_hands'] = $player_hands;
          $_SESSION['dealer_hands'] = $dealer_hands;
          $_SESSION['total_dealer_hands'] = $total_dealer_hands;
          $_SESSION['total_player_hands'] = $total_player_hands;
          $_SESSION['message'] = 'スタンドしました。';
          $_SESSION['end_game_flg'] = $end_game_flg;

          var_dump($_SESSION['end_game_flg']);

          //         // if ($total_player_hands > 21 || $total_dealer_hands > 21) {
    //         // $end_game_flg = true;
    //         // }

    //         // sessionに値を詰める
    //         $_SESSION["name"] = $user_name;
    //         $_SESSION['$cards'] = $cards;
    //         $_SESSION['player_hands'] = $player_hands;
    //         $_SESSION['dealer_hands'] = $dealer_hands;
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

<div class="message">
  <p class="message-area">
   <?php
   echo $_SESSION['message'];
  //  echo "エンドフラグ：".$end_game_flg;
   ?>
  </p>
</div>

<p>
ディーラーの手札：
<?php
foreach ($_SESSION['dealer_hands'] as $card) {
    echo '【'.$card['mark'].'の'.$card['number'].'】  ';
}
?>
<br>
合計：
<?php
 echo $_SESSION['total_dealer_hands'];
?>
<hr>
<p>
あなたの手札：
<?php
foreach ($_SESSION['player_hands'] as $card) {
    echo '【'.$card['mark'].'の'.$card['number'].'】  ';
}
?>
<br>
合計：
<?php
 echo $_SESSION['total_player_hands'];
?>
<br>
<?php 
 sayJudge($_SESSION['end_game_flg'], $_SESSION['total_player_hands'], $_SESSION['total_dealer_hands']);
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
