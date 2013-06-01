<?php

class encjak extends lifeForm {
  
  /**
   * Współczynnik uczący
   *
   * @var float
   */
  public $LearnFactor = 0.1;
  
  /**
   * Konsumpcja encjaka
   *
   * @var int
   */
  public $FoodPerTurn = 5;
  
  /**
   * Współczynnik wysokiego pożywienia (Food > $FoodPerTurn * $FoodHighThreshol)
   *
   * @var int
   */
  public $FoodHighThreshol = 12;
  
  /**
   * Współczynnik średniego pożywienia (Food > $FoodPerTurn * $FoodHighThreshol)
   *
   * @var int
   */
  public $FoodMedThreshol = 6;
  
  /**
   * Zdrowia na ruch
   *
   * @var int
   */
  public $HealthToMove = 10;
  
  /**
   * Regeneracja zdrowia
   *
   * @var int
   */
  public $HealthRegeneration = 8;
  
  /**
   * Procentowy współczynnik podejmowania przez formę życia irracjonalnych decyzji
   *
   * @var int
   */
  public $IrraticDecision = 10;
  
  /*
   * Domyślnie co ile tur forma życia może się rozmnażać
   */
  public $ProcreationThreshold = 5;
  
  /*
   * W jakim wieku forma życia uzyskuje zdolność rozmnażania
   */
  public $ProcreationAge = 4;
  
  public $MaxAge = 100;
  
  public $MaxHealth = 100;
  
  public $randomKillRatio = 1;

  /**
   * Porównanie, czy inne pole ma więcej pożywienia
   *
   * @param position $current
   * @param position $target
   * @return unknown
   */
  protected function compareFood(position $current, position $target) {

    $retVal = 0;
    
    if (parcel::sGetFood ( $target ) > parcel::sGetFood ( $current )) {
      $retVal += 0.5;
    }
    
    if (parcel::sGetFood ( $target ) >= $this->FoodPerTurn * $this->FoodHighThreshol) {
      $retVal += 0.5;
    }
    
    if ($retVal == 0) {
      $retVal = - 1;
    }
    
    return $retVal;
  }

  /**
   * Pobranie ilości jedzenia na północy
   *
   * @return int
   */
  public function getMoreFoodN() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->Y --;
    if ($tPos->Y < 1) {
      $tPos->Y = $worldDimension->Y;
    }
    
    $retVal = $this->compareFood ( $this->Position, $tPos );
    
    unset ( $tPos );
    return $retVal;
  }

  /**
   * Porównanie ilości jedzenia na południu
   *
   * @return int
   */
  public function getMoreFoodS() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->Y ++;
    if ($tPos->Y > $worldDimension->Y) {
      $tPos->Y = 1;
    }
    
    $retVal = $this->compareFood ( $this->Position, $tPos );
    
    unset ( $tPos );
    return $retVal;
  
  }

  /**
   * Porównanie ilości jedzenia na wschodzie
   *
   * @return int
   */
  public function getMoreFoodE() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->X ++;
    if ($tPos->X > $worldDimension->X) {
      $tPos->X = 1;
    }
    
    $retVal = $this->compareFood ( $this->Position, $tPos );
    
    unset ( $tPos );
    return $retVal;
  
  }

  /**
   * Porównanie ilości jedzenia na zachodzie
   *
   * @return int
   */
  public function getMoreFoodW() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->X --;
    if ($tPos->X < 1) {
      $tPos->X = $worldDimension->X;
    }
    
    $retVal = $this->compareFood ( $this->Position, $tPos );
    
    unset ( $tPos );
    return $retVal;
  
  }

  /**
   * czy w polu znajduje się dużo encjaków
   *
   * @return int
   */
  public function getPopulationHigh() {

    if (parcel::sGetLifeFormPopulation ( $this->Position, get_class ( $this ) ) > 6) {
      return 1;
    } else {
      return - 1;
    }
  }

  /**
   * Czy w polu znajduje się średnio encjaków
   *
   * @return int
   */
  public function getPopulationMed() {

    if (parcel::sGetLifeFormPopulation ( $this->Position, get_class ( $this ) ) <= 6 && parcel::sGetLifeFormPopulation ( $this->Position, get_class ( $this ) ) >= 3) {
      return 1;
    } else {
      return - 1;
    }
  }

  /**
   * Czy w polu znajduje się średnio encjaków
   *
   * @return int
   */
  public function getPopulationLow() {

    if (parcel::sGetLifeFormPopulation ( $this->Position, get_class ( $this ) ) < 3) {
      return 1;
    } else {
      return - 1;
    }
  }

  /**
   * Czy forma życia jest zdrowa
   *
   * @return int
   */
  public function getHealthHigh() {

    if ($this->Health->Current > ($this->MaxHealth * 0.66)) {
      return 1;
    } else {
      return - 1;
    }
  }

  /**
   * Czy forma życia jest zdrowa
   *
   * @return int
   */
  public function getHealthMed() {

    if ($this->Health->Current <= ($this->MaxHealth * 0.66) && $this->Health->Current >= ($this->MaxHealth * 0.33)) {
      return 1;
    } else {
      return - 1;
    }
  }

  /**
   * Czy forma życia jest zdrowa
   *
   * @return int
   */
  public function getHealthLow() {

    if ($this->Health->Current < ($this->MaxHealth * 0.33)) {
      return 1;
    } else {
      return - 1;
    }
  }

  /**
   * Czy w polu jest dużo pożywienia
   *
   * @return int
   */
  public function getFoodHigh() {

    if (parcel::sGetFood ( $this->Position ) > $this->FoodPerTurn * $this->FoodHighThreshol) {
      return 1;
    } else {
      return - 1;
    }
  }

  public function getFoodMed() {

    if (parcel::sGetFood ( $this->Position ) <= $this->FoodPerTurn * $this->FoodHighThreshol && parcel::sGetFood ( $this->Position ) >= $this->FoodPerTurn * $this->FoodMedThreshol) {
      return 1;
    } else {
      return - 1;
    }
  }

  public function getFoodLow() {

    if (parcel::sGetFood ( $this->Position ) < $this->FoodPerTurn * $this->FoodMedThreshol) {
      return 1;
    } else {
      return - 1;
    }
  }

  /**
   * Autouczenie się encjaka
   *
   * @return boolean
   */
  public function autoLearn() {

    if (isset ( $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] )) {
      
      /**
       * Czy wykonać uczenie tej reakcji
       */
      $goTeach = false;
      $tReactionType = $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['reaction'];
      $targetReaction = - 1;
      switch ($_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['reaction']) {
        
        /*
         * Pozostał na miejscu
         */
        case 0 :
          $goTeach = true;
          /*
           * Sprawdz, czy reakcja ma być pozytywna
           */
          if ($this->getFoodHigh () == 1) {
            $targetReaction = 1;
          }
          if ($this->getFoodMed () == 1) {
            $targetReaction = 1;
          }
          if ($this->getFoodLow () == 1) {
            $targetReaction = 0;
          }
          if ($this->getHealthHigh () == 1) {
            $targetReaction = 1;
          }
          if ($this->getHealthMed () == 1) {
            $targetReaction = 1;
          }
          break;
        
        /*
         * Poruszenie się w konkretnym kierunku
         */
        case 3 :
        case 4 :
        case 5 :
        case 6 :
          $goTeach = true;
          
          /*
           * Sprawdz, czy reakcja ma być pozytywna
           */
          if ($this->getFoodHigh () == 1) {
            $targetReaction = 1;
          }
          if ($this->getFoodMed () == 1) {
            $targetReaction = 0.5;
          }
          if ($this->getFoodLow () == 1) {
            $targetReaction = 0;
          }
          /*
           * Jeśli zdrowie jest niskie po ruchu, to naucz pozostawania
           */
          if ($this->getHealthLow () == 1) {
            $targetReaction = 1;
            $tReactionType = 0;
          }
          
          // @todo reakcja na inne bodźce niż jedzenie
          break;
      
      }
      
      if ($goTeach) {
        $tLearnFactor = $this->LearnFactor;
        $this->LearnFactor = $tLearnFactor / 5;
        $tReaction [$tReactionType] = $targetReaction;
        $tStimulus = $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['stimuluses'];
        $this->simpleTeach ( $tReaction, $tStimulus );
        $this->LearnFactor = $tLearnFactor;
      }
      
      return true;
    } else {
      return false;
    }
  
  }
}

?>