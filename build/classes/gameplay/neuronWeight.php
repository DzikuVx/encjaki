<?php

class neuronWeight extends item {
  
  /**
   * Stymulus neuronu
   *
   * @var neuronStimulus
   */
  public $Stimulus = '';
  
  /**
   * Wartość wagi
   *
   * @var float
   */
  public $Weight = 0;

  /**
   * @return float
   */
  public function getWeight() {

    return $this->Weight;
  }

  /**
   * @param float $Weight
   */
  public function setWeight($Weight) {

    $this->Weight = $Weight;
  }

  /**
   * Zwiększenie wagi neuronu
   *
   * @param float $amount
   */
  public function incWeight($amount) {

    $this->Weight += $amount;
  }

  function __construct($StimulusID, $tStimulus, $Weight = 0) {

    /*
			 * nowa waga neuronu, inicjacja
			 */
    $this->Weight = $Weight;
    $this->Stimulus = new neuronStimulus ( );
    $this->Stimulus->ID = $StimulusID;
    $this->Stimulus->Source = $tStimulus ['Source'];
  }

  /**
   * Obliczenie wartości wyjścia wejścia neuronu
   *
   * @param float $val
   * @return float
   */
  public function computeValue($val) {

    return $this->Weight * $val;
  }

}

?>