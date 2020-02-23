<?php

// カード山のクラス設計
class Deck{

  private $deck;
  private $numbers  = [1,2,3,4,5,6,7,8,9,10,11,12,13];
  private $marks = ['ハート','ダイヤ','スペード','クラブ'];

  public function __construct(){
    foreach ($this->marks as $mark) {
      foreach ($this->numbers as $number) {
        // $this->deck[] = $mark.'の'.$number;
        // $deck = $this->deck;
        //           var_dump($number);

        $this->deck = [
          'number' => $number,
          'mark' => $mark
        ];
        // var_dump($deck);

      }
    }
  }

  // カードをシャッフルする
  public function shuffleCard(){
    shuffle($this->deck);
    return $this->deck;
  }
}


?>