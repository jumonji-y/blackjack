<?php
include('./cards.php');

session_start();

// ユーザーとディーラーの持ち手を初期化
$player_hands = array();
$dealer_hands = array();

// ゲームを終了するかどうかのフラグを立てる
// falseなら続行、trueなら終了
$end_game_flg = false;

// 自分の手札の合計値
$my_total_hands = 0;
$opp_total_hands = 0;

// ゲームの処理の流れ
  // if (empty($_POST)  || empty($_SESSION)) {
  // if (empty($_POST)) {
      $_SESSION['message'] = 'ゲームスタート！';

      // 山札の準備
      $cards = new Card;
 var_dump($cards);
      // プレーヤーとディーラーに2枚ずつ配る
      // for ($i = 1; $i < 3; $i++) {
          $player_hands = $cards[1];
          $dealer_hands[] = array_shift($cards);
      // }
// var_dump($player_hands);
      // プレーヤーと、ディーラーの持ち札の合計を算出
      $total_player_hands = $cards->sum_hands_cards($player_hands);
      $total_dealer_hands = $cards->sum_hands_cards($dealer_hands);

      $_SESSION['cards'] = $cards;
      $_SESSION['player_hands'] = $player_hands;
      $_SESSION['dealer_hands'] = $dealer_hands;
      $_SESSION['total_dealer_hands'] = $total_dealer_hands;
      $_SESSION['total_player_hands'] = $total_player_hands;
      $_SESSION['end_game_flg'] = $end_game_flg;

      // gameJudge($_SESSION['total_player_hands'], $_SESSION['total_dealer_hands']);
  // }


?>