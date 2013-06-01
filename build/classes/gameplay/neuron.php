<?php

class neuron extends item {
  
  public $Weights = array ();
  public $Output = 0;
  
  /**
   * Współczynnik Beta funckji aktywacji
   *
   * @var float
   */
  public $BetaFactor = 0.5;
  
  /**
   * Rezultat neuronu
   *
   * @var neuronResult
   */
  public $Result = null;

  /**
   * Konstruktor publiczny
   *
   * @param int $ResultID
   * @param array $tResult
   */
  function __construct($ResultID = null, $tResult = null) {

    $this->Result = new neuronResult ( );
    $this->Result->ID = $ResultID;
    $this->Result->Reaction = $tResult ['Reaction'];
    $this->initWeights ();
  }

  /**
   * Obliczenie wartości wyjścia neuronu
   *
   * @param lifeForm $LifeForm
   * @return float
   */
  public function computeReaction($LifeForm) {

    $tOutput = 0;
    
    foreach ( $this->Weights as $tWeight ) {
      try {
        
        if (get_class ( $tWeight ) != 'neuronWeight') {
          throw new mutation ( $LifeForm->LifeFormID );
        }
        
        if (! isset ( $tWeight->Stimulus->Source )) {
          throw new mutation ( $LifeForm->LifeFormID );
        }
        
        if (method_exists ( $LifeForm, $tWeight->Stimulus->Source )) {
          $tSourceValue = $LifeForm->{$tWeight->Stimulus->Source} ();
          $tOutput += $tWeight->computeValue ( $tSourceValue );
        } else {
          throw new Exception ( 'Unknown input' );
        }
      } catch ( Exception $e ) {
        //@todo logowanie błędnych inputów
      }
    }
    /**
     * Przelicz przez funkcję aktywacji
     */
    $tOutput = $this->outputFunction ( $tOutput );
    
    $this->Output = $tOutput;
    
    return $tOutput;
  
  }

  /**
   * Funkcja realizująca krzywą logistyczną
   *
   * @param float $val
   * @return float
   */
  public function outputFunction($val) {

    $retVal = 1 / (1 + exp ( - ($this->BetaFactor * $val) ));
    
    return $retVal;
  }

  /**
   * Pochodna funkcji krzywej logistrycznej
   *
   * @param float $val
   * @return float
   */
  public function outputFunctionDerivative($val) {

    return $val * (1.0 - $val);
  }

  /**
   * Inicjacja wag neuronu do zerowych wartości
   *
   * @return boolean
   */
  protected function initWeights() {

    global $Stimuluses;
    
    foreach ( $Stimuluses as $tKey => $tValue ) {
      $tWeight = new neuronWeight ( $tKey, $tValue, 0 );
      array_push ( $this->Weights, $tWeight );
    }
    
    return true;
  }

  public function destroy() {

    foreach ( $this->Weights as $tWeight ) {
      $tWeight->destroy ();
    }
    item::destroy ();
  }

}

?>