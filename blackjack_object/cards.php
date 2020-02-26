<?php
ini_set('log_errors', 'on');  //ログを取るか
ini_set('error_log', 'php_blackjack.log');  //ログの出力ファイルを指定


// カード格納用
$cards = array();

class Card
{
    private $cards = [];
    private $numbers  = array('2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8,'9' => 9, '10' => 10, 'j' => 10, 'q' => 10, 'k' => 10, 'a' => 1);
    private $marks = ['ハート','ダイヤ','スペード','クラブ'];

    public function __construct()
    {
        foreach ($this->marks as $mark) {
          foreach ($this->numbers as $face => $number) {
            $this->cards[] = array(
            'face' => $face,
            'number' => $number,
            'mark' => $mark
            );
          }
          shuffle($this->cards);
        }
    }

    // 持ち札の合計を出すための関数
    public function sum_hands_cards($hand_cards)
    {
      $total = 0;
      foreach ($hand_cards as $hand_card) {
        $total += $hand_card['number'];
        var_dump($hand_card['number']);
      }
    return $total;
    }

}

?>
