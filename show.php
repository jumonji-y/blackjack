<?php
ini_set('log_errors', 'on');  //ログを取るか
ini_set('error_log', 'php_my.log');  //ログの出力ファイルを指定

// deckクラスの読み込み
include('deckClass.php');
session_start();

// 山札の初期化
unset($_SESSION['deck']);

// もし$_SESSION['deck']が空ならば
if(empty($_SESSION['deck'])){
  $deck[] = new Deck;
  $deck = $deck->shuffleCard();
  $_SESSION['deck'] = $deck;
}else{
  // そうでなければ
  $deck = $_SESSION['deck'];
}

// ゲームが始まったら、カードを2枚ずつ配る
// 配列の初期化
$player_hands = array();
$dealer_hands = array();

// 2枚ずつ配る
for($i = 1; $i < 3; $i++){
  $player_hands[] = array_shift($deck);
  $dealer_hands[] = array_shift($deck);
}
// var_dump($player_hands);
// var_dump($dealer_hands);

$player_hands_sum = array_sum($player_hands);

echo "あなたの持ち札は ",$player_hands[0]," と ",$player_hands[1]," です。<br>";
echo "ディーラーの持ち札は ",$dealer_hands[0]." です。もう一枚のカードは裏返してあります。";
echo "あなたの持ち札の合計は「".$player_hands_sum."」です。もう一枚引きますか？";
?>