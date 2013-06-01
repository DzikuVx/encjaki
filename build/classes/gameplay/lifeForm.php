<?php
/**
 * Klasa bazowa form życia
 * @author Paweł Spychalski <pawel@spychalski.info>
 * @see http://www.spychalski.info
 * @category gameplay
 */
abstract class lifeForm extends item {
  
  public $LifeFormID = null;
  
  /**
   * Ile procent szansy na losowe zabicie encjaka
   *
   * @var int
   */
  public $randomKillRatio = 2;
  
  /**
   * Zdrowie
   *
   * @var health
   */
  public $Health = null;
  
  /**
   * Pozycja na mapie
   *
   * @var extendedPosition
   */
  public $Position = null;
  
  /**
   * Wiek
   *
   * @var age
   */
  public $Age = null;
  
  /**
   * Enter description here...
   *
   * @var procreation
   */
  public $Procreation = null;
  
  /**
   * Współczynnik uczący
   *
   * @var float
   */
  public $LearnFactor = 0.1;
  
  public $Neurons = array ();
  
  /**
   * Ile razy musi być więcej jedzenia niż w polu aby móc się rozmożyć FoodPerTurn *
   *
   * @var int
   */
  public $ProcreationFoodMultiplier = 8;
  
  /**
   * Ile forma musi mieć zrowia aby móc się rozmożyć Health->Max / 
   *
   * @var int
   */
  public $ProcreationHealthDivider = 2;
  
  /**
   * Maksymalny początkowy wiek formy życia
   *
   * @var int
   */
  public $MaxAge = 100;
  
  /**
   * Maksymalne początkowe zdrwie
   *
   * @var int
   */
  public $MaxHealth = 100;
  
  public $FoodPerTurn = 5;
  
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
  public $HealthRegeneration = 5;
  
  /**
   * Procentowy współczynnik podejmowania przez formę życia irracjonalnych decyzji
   *
   * @var int
   */
  public $IrraticDecision = 10;
  
  /**
   * Współczynnik zandomizacji wag, wyrażony w 1/1000
   *
   * @var randomization
   */
  public $WeightRandomization = 1;
  
  /*
	 * Domyślnie co ile tur forma życia może się rozmnażać
	 */
  public $ProcreationThreshold = 5;
  
  /*
	 * W jakim wieku forma życia uzyskuje zdolność rozmnażania
	 */
  public $ProcreationAge = 8;

  /**
   * Konstruktor
   *
   * @param unknown_type $positionX
   * @param unknown_type $positionY
   */
  function __construct($positionX = null, $positionY = null) {

    $this->LifeFormID = $this->genID ();
    $this->Position = new position ( $positionX, $positionY );
    $this->Health = new health ( $this->MaxHealth, $this->MaxHealth );
    $this->Procreation = new procreation ( $this->ProcreationAge, 0, $this->ProcreationThreshold );
    $this->Age = new age ( 0, $this->MaxAge );
    $this->initNeuralNetwork ();
    
    $this->WeightRandomization = new randomization ( - 100, 100, 1000000 );
  
  }

  static function pushLearnPattern($params) {

    global $dataArray, $Reactions;
    
    $retVal = '';
    
    //otwórz wzorzec uczenia
    $patternSaver = new itemSaver ( 'learnPattern', $params ['loggedUserID'] );
    
    $learnPattern = array ();
    
    if ($params ['learnMode'] == 'next') {
      $patternSaver->get ( $learnPattern );
    }
    
    $params ['xml'] = str_replace ( "\\", "", $params ['xml'] );
    
    $sXml = new SimpleXmlElement ( $params ['xml'] );
    
    $tPattern = array ();
    
    foreach ( $sXml as $tKey => $tValue ) {
      $tPattern ['stimulus'] [( int ) str_replace ( 's', '', ( string ) $tKey )] = ( int ) $tValue;
    }
    
    $tKeys = array_keys ( $Reactions );
    foreach ( $tKeys as $tReaction ) {
      if ($params ['reaction'] == $tReaction) {
        $tPattern ['reaction'] [$tReaction] = 1;
      } else {
        $tPattern ['reaction'] [$tReaction] = 0;
      }
    }
    
    array_push ( $learnPattern, $tPattern );
    
    $patternSaver->put ( $learnPattern );
    
    return $retVal;
  
  }

  static function sRenderLearnWindow($params) {

    $retVal = '';
    
    $retVal .= '<h1>Okno uczenia</h1>';
    
    $retVal .= '<table>';
    
    $tParams = '';
    
    /**
     * Wyrenderuj zdrowie
     */
    $tHealth = rand ( 1, 3 );
    switch ($tHealth) {
      
      case 1 :
        $tClass = 'learnHigh';
        $tName = 'Wysokie';
        $tParams .= '<learnPattern name="1" value="1">';
        $tParams .= '<learnPattern name="2" value="-1">';
        $tParams .= '<learnPattern name="3" value="-1">';
        break;
      case 2 :
        $tClass = 'learnMed';
        $tName = 'Średnie';
        $tParams .= '<learnPattern name="1" value="-1">';
        $tParams .= '<learnPattern name="2" value="1">';
        $tParams .= '<learnPattern name="3" value="-1">';
        break;
      case 3 :
        $tClass = 'learnLow';
        $tName = 'Niskie';
        $tParams .= '<learnPattern name="1" value="-1">';
        $tParams .= '<learnPattern name="2" value="-1">';
        $tParams .= '<learnPattern name="3" value="1">';
        break;
    }
    
    $retVal .= '<tr><th>Zdrowie: </th><td class="' . $tClass . '">' . $tName . '</td></tr>';
    
    $PopulationCurrent = rand ( 1, 3 );
    switch ($PopulationCurrent) {
      
      case 1 :
        $tPopulationCurrentClass = 'learnHigh';
        $tPopulationCurrentName = 'Wysoka';
        $tParams .= '<learnPattern name="13" value="1">';
        $tParams .= '<learnPattern name="14" value="-1">';
        $tParams .= '<learnPattern name="15" value="-1">';
        break;
      case 2 :
        $tPopulationCurrentClass = 'learnMed';
        $tPopulationCurrentName = 'Średnia';
        $tParams .= '<learnPattern name="13" value="-1">';
        $tParams .= '<learnPattern name="14" value="1">';
        $tParams .= '<learnPattern name="15" value="-1">';
        break;
      case 3 :
        $tPopulationCurrentClass = 'learnLow';
        $tPopulationCurrentName = 'Niska';
        $tParams .= '<learnPattern name="13" value="-1">';
        $tParams .= '<learnPattern name="14" value="-1">';
        $tParams .= '<learnPattern name="15" value="1">';
        break;
    }
    
    $retVal .= '<tr><th>Populacja: </th><td class="' . $tPopulationCurrentClass . '">' . $tPopulationCurrentName . '</td></tr>';
    
    $FoodCurrent = rand ( 1, 3 );
    switch ($FoodCurrent) {
      
      case 1 :
        $tFoodCurrentClass = 'learnHigh';
        $tFoodCurrentName = 'Wysokie';
        $tParams .= '<learnPattern name="7" value="1">';
        $tParams .= '<learnPattern name="8" value="-1">';
        $tParams .= '<learnPattern name="9" value="-1">';
        break;
      case 2 :
        $tFoodCurrentClass = 'learnMed';
        $tFoodCurrentName = 'Średnie';
        $tParams .= '<learnPattern name="7" value="-1">';
        $tParams .= '<learnPattern name="8" value="1">';
        $tParams .= '<learnPattern name="9" value="-1">';
        break;
      case 3 :
        $tFoodCurrentClass = 'learnLow';
        $tFoodCurrentName = 'Niskie';
        $tParams .= '<learnPattern name="7" value="-1">';
        $tParams .= '<learnPattern name="8" value="-1">';
        $tParams .= '<learnPattern name="9" value="1">';
        break;
    }
    
    $FoodN = rand ( 1, 2 );
    switch ($FoodN) {
      
      case 1 :
        $tFoodNClass = 'learnHigh';
        $tFoodNName = 'Wysokie';
        $tParams .= '<learnPattern name="18" value="1">';
        break;
      case 2 :
        $tFoodNClass = 'learnLow';
        $tFoodNName = 'Niskie';
        $tParams .= '<learnPattern name="18" value="-1">';
        break;
    }
    $FoodE = rand ( 1, 2 );
    switch ($FoodE) {
      
      case 1 :
        $tFoodEClass = 'learnHigh';
        $tFoodEName = 'Wysokie';
        $tParams .= '<learnPattern name="20" value="1">';
        break;
      case 2 :
        $tFoodEClass = 'learnLow';
        $tFoodEName = 'Niskie';
        $tParams .= '<learnPattern name="20" value="-1">';
        break;
    }
    $FoodW = rand ( 1, 2 );
    switch ($FoodW) {
      case 1 :
        $tFoodWClass = 'learnHigh';
        $tFoodWName = 'Wysokie';
        $tParams .= '<learnPattern name="22" value="1">';
        break;
      case 2 :
        $tFoodWClass = 'learnLow';
        $tFoodWName = 'Niskie';
        $tParams .= '<learnPattern name="22" value="-1">';
        break;
    }
    $FoodS = rand ( 1, 2 );
    switch ($FoodS) {
      case 1 :
        $tFoodSClass = 'learnHigh';
        $tFoodSName = 'Wysokie';
        $tParams .= '<learnPattern name="24" value="1">';
        break;
      case 2 :
        $tFoodSClass = 'learnLow';
        $tFoodSName = 'Niskie';
        $tParams .= '<learnPattern name="24" value="-1">';
        break;
    }
    
    $retVal .= '<tr><th>Pożywienie</th>';
    $retVal .= '<td>';
    
    $retVal .= '<table>';
    
    $retVal .= '<tr>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '<td class="' . $tFoodNClass . '">' . $tFoodNName . '</td>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '</tr>';
    $retVal .= '<tr>';
    $retVal .= '<td class="' . $tFoodEClass . '">' . $tFoodEName . '</td>';
    $retVal .= '<td class="' . $tFoodCurrentClass . '">' . $tFoodCurrentName . '</td>';
    $retVal .= '<td class="' . $tFoodWClass . '">' . $tFoodWName . '</td>';
    $retVal .= '</tr>';
    $retVal .= '<tr>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '<td class="' . $tFoodSClass . '">' . $tFoodSName . '</td>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '</tr>';
    
    $retVal .= '</table>';
    
    $retVal .= '</td>';
    $retVal .= '</tr>';
    
    $retVal .= '<tr><th>Reakcja</th>';
    $retVal .= '<td>';
    
    $retVal .= '<table class="decision">';
    
    $retVal .= '<tr>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '<th onclick="pushLearnPattern(3)">Północ</th>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '</tr>';
    $retVal .= '<tr>';
    $retVal .= '<th onclick="pushLearnPattern(5)">Zachód</th>';
    $retVal .= '<th onclick="pushLearnPattern(0)">Zostań</th>';
    $retVal .= '<th onclick="pushLearnPattern(4)">Wschód</th>';
    $retVal .= '</tr>';
    $retVal .= '<tr>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '<th onclick="pushLearnPattern(6)">Południe</th>';
    $retVal .= '<td>&nbsp;</td>';
    $retVal .= '</tr>';
    
    $retVal .= '</table>';
    
    $retVal .= '</td>';
    $retVal .= '</tr>';
    
    $retVal .= '</table>';
    
    $retVal .= $tParams;
    
    $retVal .= '<learnMode value="' . $params ['ID'] . '" />';
    
    return $retVal;
  }

  /**
   * generowanie nowego identyfikatora formy życia
   *
   * @return int
   */
  protected function genID() {

    global $dataArray;
    
    $retVal = 0;
    
    if (isset ( $dataArray ['lastUsedID'] )) {
      $retVal = $dataArray ['lastUsedID'] + 1;
    }
    
    $dataArray ['lastUsedID'] = $retVal;
    
    return $retVal;
  }

  /**
   * Wyrenderowanie podsumowania
   *
   * @param array $params
   * @return string
   */
  static function sGetSummary($params) {

    $retVal = '';
    
    try {
      
      global $Reactions;
      
      $lifeForm = null;
      
      $lifeFormSaver = new itemSaver ( 'lifeforms', $params ['loggedUserID'] );
      $lifeFormSaver->get ( $encjakArray );
      
      /*
     * Znajdź formy życia 
     */
      foreach ( $encjakArray as $tEncjak ) {
        if ($tEncjak->LifeFormID == $params ['ID']) {
          $lifeForm = $tEncjak;
          break;
        }
      }
      
      unset ( $encjakArray );
      
      // @todo: informacje o formie życia

      if ($lifeForm == null) {
        throw new Exception ( 'Forma życia nie istnieje' );
      }
      
      if (! method_exists ( $lifeForm, 'computeReaction' )) {
        throw new Exception ( 'Forma życia nie istnieje' );
      }
      
      /*
     * Załaduj szablon
     */
      $template = new psTemplate ( '../templates/lifeFormDetail.html' );
      
      $template->add ( 'SPECIES', get_class ( $lifeForm ) );
      $template->add ( 'PositionX', $lifeForm->Position->X );
      $template->add ( 'PositionY', $lifeForm->Position->Y );
      $template->add ( 'HealthCurrent', $lifeForm->Health->Current );
      $template->add ( 'HealthMax', $lifeForm->Health->Max );
      $template->add ( 'AgeCurrent', $lifeForm->Age->Current );
      $template->add ( 'AgeMax', $lifeForm->Age->Max );
      $template->add ( 'ProcreationChildrenCount', $lifeForm->Procreation->ChildrenCount );
      $template->add ( 'LearnFactor', $lifeForm->LearnFactor );
      if (isset ( $_SESSION ['lifeFormLastAction'] [$lifeForm->LifeFormID] ['reaction'] )) {
        $template->add ( 'lifeFormLastAction', $Reactions [$_SESSION ['lifeFormLastAction'] [$lifeForm->LifeFormID] ['reaction']] ['Name'] );
      } else {
        $template->add ( 'lifeFormLastAction', '-' );
      }
      
      $tReaction = $lifeForm->computeReaction ();
      $tString = '';
      foreach ( $tReaction as $tValue ) {
        $tString .= '<div>';
        $tString .= $tValue->Result->Reaction;
        $tString .= ' : ';
        $tString .= $tValue->Output;
        $tString .= '</div>';
      }
      $template->add ( 'lifeFormNextAction', $tString );
      
      $tString = '';
      global $Stimuluses;
      foreach ( $Stimuluses as $tStimulus ) {
        $tString .= '<div>';
        $tString .= $tStimulus ['Name'] . ' : ';
        if (method_exists ( $lifeForm, $tStimulus ['Source'] )) {
          $tString .= $lifeForm->{$tStimulus ['Source']} ();
        }
        $tString .= '</div>';
      }
      $template->add ( 'currentStimuluses', $tString );
      
      
      $tString = '';
      
      $tString .= controls::renderImgButton ( 'delete', "killLifeForm('" . $params ['ID'] . "')", 'Usuń' ).'<br />';
      $tString .= controls::renderImgButton ( 'add', "executeSimpleGet('lifeForm','sClone','" . $params ['ID'] . "',false);", 'Klonuj' ).'<br />';
      $tString .= controls::renderImgButton ( 'zeroNetwork', "executeSimpleGet('lifeForm','sZeroNeuralNetwork','" . $params ['ID'] . "',false, '#univPanel','modal');", 'Zeruj sieć neuronową' ).'<br />';
      $tString .= controls::renderImgButton ( 'teach', "executeSimpleGet('lifeForm','sLearn','" . $params ['ID'] . "',false, '#univPanel','modal');", 'Ucz według wzorca' ).'<br />';
      $template->add('OPERATIONS',$tString);

      $retVal .= $template;
      
    } catch ( Exception $e ) {
      $retVal = $e->getMessage ();
    }
    
    return $retVal;
  }

  /**
   * Karmienie encjaka
   *
   */
  public function feed() {

    if (parcel::sGetFood ( $this->Position ) > $this->FoodPerTurn) {
      parcel::sDecFood ( $this->Position, $this->FoodPerTurn );
      $this->incHealth ( $this->HealthRegeneration );
    } else {
      $this->decHealth ( $this->HealthRegeneration );
      parcel::sSetFood ( $this->Position, 0 );
    }
  }

  /**
   * @return age
   */
  public function getAge() {

    return $this->Age;
  }

  /**
   * @return health
   */
  public function getHealth() {

    return $this->Health;
  }

  /**
   * @return extendedPosition
   */
  public function getPosition() {

    return $this->Position;
  }

  /**
   * @param age $Age
   */
  public function setAge($current, $max = null) {

    $this->Age->Current = $current;
    if ($max != null) {
      $this->Age->Max = $max;
    }
  }

  public function incAge($amount = 1) {

    $this->Age->Current += $amount;
  }

  public function incHealth($amount = 1) {

    $this->Health->Current += $amount;
    if ($this->Health->Current > $this->Health->Max) {
      $this->Health->Current = $this->Health->Max;
    }
  }

  public function decHealth($amount = 1) {

    $this->Health->Current -= $amount;
  }

  /**
   * @param health $Health
   */
  public function setHealth($current, $max = null) {

    $this->Health->Current = $current;
    if ($max != null) {
      $this->Health->Max = $max;
    }
  }

  /**
   * @param extendedPosition $Position
   */
  public function setPosition($X, $Y) {

    $this->Position->X = $X;
    $this->Position->Y = $Y;
  }

  /**
   * Inicjacja sieci neuronowej encjaka
   *
   * @return boolean
   */
  protected function initNeuralNetwork() {

    global $Reactions;
    
    /*
		 * Zainicjuj neurony
		 */
    foreach ( $Reactions as $tKey => $tValue ) {
      $tNeuron = new neuron ( $tKey, $tValue );
      array_push ( $this->Neurons, $tNeuron );
    }
    
    return true;
  }

  /**
   * Oblicz reakcję
   *
   */
  public function react() {

    try {
      
      global $lifeFormTextField;
      
      /*
       * Oblicz reakcję
       */
      
      $OutputArray = array ();
      
      $OutputArray = $this->computeReaction ();
      
      if (empty ( $OutputArray )) {
        throw new sendException ( $this->LifeFormID, 'Pusta tablica reakcji' );
      }
      
      /*
     * Podejmij akcję
     */
      
      $actionSuccessful = false;
      $tIndex = 0;
      
      while ( ! $actionSuccessful ) {
        //@todo: poprawić autouczenie, uczy się bzdur
        
        if (!isset($OutputArray [$tIndex])) {
          throw new mutation($this->LifeFormID);
        }
        
        if (method_exists ( $this, $OutputArray [$tIndex]->Result->Reaction )) {
          $actionSuccessful = $this->{$OutputArray [$tIndex]->Result->Reaction} ();
        }
        /*
         * Jeśli reakcja zakończyła się sukcesem, dla tej formy życia zapisz stimulusy i reakcję
         */
        if ($actionSuccessful) {
          /*
           * Zapisz wszystkie stimulusy
           */
          global $Stimuluses;
          $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['stimuluses'] = array ();
          foreach ( $Stimuluses as $tKey => $tStimulus ) {
            if (method_exists ( $this, $tStimulus ['Source'] )) {
              $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['stimuluses'] [$tKey] = $this->{$tStimulus ['Source']} ();
            }
          }
          
          //@todo: poprawić wygląd listy form życia

          /*
           * Wyświetl podsumowanie akcji
           */
          $retVal = '<div>';
          $tlfID = str_replace ( '.', '', $this->LifeFormID );
          $retVal .= '<div onclick="$(\'#lfData' . $tlfID . '\').toggle();">Forma życia: ' . $this->LifeFormID . '</div>';
          $retVal .= '<div id="lfData' . $tlfID . '" style="display: none">';
          $retVal .= '<div>Położenie: ' . $this->Position->X . ' / ' . $this->Position->Y . '</div>';
          $retVal .= '<div>Zdrowie: ' . $this->Health->Current . ' / ' . $this->Health->Max . '</div>';
          $retVal .= '<table>';
          
          foreach ( $OutputArray as $tKey => $tValue ) {
            $retVal .= '<tr>';
            
            if ($tKey == $tIndex) {
              $retVal .= '<td style="font-weight: bold;">';
            } else {
              $retVal .= '<td>';
            }
            
            if (!isset($tValue->Result->Reaction)) {
              throw new mutation($this->LifeFormID);
            }
            
            $retVal .= $tValue->Result->Reaction . '</td>';
            $retVal .= '<td>' . $tValue->Output . '</td>';
            $retVal .= '</tr>';
          }
          
          $retVal .= '</table>';
          $retVal .= '</div>';
          $retVal .= '</div>';
          
          $lifeFormTextField .= $retVal;
        }
        
        $tIndex ++;
        
        if ($tIndex == 100)
          break;
      
      }
      
      return $OutputArray [0]->Result;
    } catch ( Exception $e ) {
      return false;
    }
  }

  /**
   * Oblicz reakcję formy życia
   *
   * @return array
   */
  public function computeReaction() {

    $retVal = array ();
    
    try {
      foreach ( $this->Neurons as $tNeuron ) {
        
        /**
         * Rzuć wyjątek mutacji
         */
        if (! method_exists ( $tNeuron, 'computeReaction' )) {
          throw new mutation ( $this->LifeFormID );
        }
        
        $tNeuron->computeReaction ( $this );
        array_push ( $retVal, $tNeuron );
      }
      
      /*
       * W % przypadków podejmij decyzję irrancjonalną
       */
      if (rand ( 1, 100 ) < (100 - $this->IrraticDecision)) {
        usort ( $retVal, 'reactionSort' );
      } else {
        usort ( $retVal, 'reactionRandomSort' );
      }
    } catch ( Exception $e ) {
    
    }
    return $retVal;
  }

  /**
   * Reakcja - zostań
   *
   * @return boolean
   */
  protected function reactionStay() {

    $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['reaction'] = 0;
    return true;
  }

  /**
   * Reakcja - idź na wschód
   *
   * @return boolean
   */
  protected function reactionGoE() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->X ++;
    if ($tPos->X > $worldDimension->X) {
      $tPos->X = 1;
    }
    
    parcel::sDecPopulation ( $this->Position );
    $this->Position->X = $tPos->X;
    $this->decHealth ( $this->HealthToMove );
    parcel::sIncPopulation ( $this->Position );
    $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['reaction'] = 4;
    return true;
  }

  /**
   * Reakcja - idź na północ
   *
   * @return boolean
   */
  protected function reactionGoN() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->Y --;
    if ($tPos->Y < 1) {
      $tPos->Y = $worldDimension->Y;
    }
    
    parcel::sDecPopulation ( $this->Position );
    $this->Position->Y = $tPos->Y;
    parcel::sIncPopulation ( $this->Position );
    $this->decHealth ( $this->HealthToMove );
    $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['reaction'] = 3;
    return true;
  }

  /**
   * Reakcja - idź na zachód
   *
   * @return boolean
   */
  protected function reactionGoW() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->X --;
    if ($tPos->X < 1) {
      $tPos->X = $worldDimension->X;
    }
    
    parcel::sDecPopulation ( $this->Position );
    $this->Position->X = $tPos->X;
    parcel::sIncPopulation ( $this->Position );
    $this->decHealth ( $this->HealthToMove );
    $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['reaction'] = 5;
    return true;
  }

  /**
   * Reakcja - pójdź na południe
   *
   * @return boolena
   */
  protected function reactionGoS() {

    global $worldDimension;
    
    $tPos = new position ( $this->Position->X, $this->Position->Y );
    
    $tPos->Y ++;
    if ($tPos->Y > $worldDimension->Y) {
      $tPos->Y = 1;
    }
    
    parcel::sDecPopulation ( $this->Position );
    $this->Position->Y = $tPos->Y;
    parcel::sIncPopulation ( $this->Position );
    $this->decHealth ( $this->HealthToMove );
    $_SESSION ['lifeFormLastAction'] [$this->LifeFormID] ['reaction'] = 6;
    return true;
  }

  /**
   * Zniszczenie obiektu i zapisanie zmian do bazy danych
   *
   */
  public function destroy() {

    foreach ( $this->Neurons as $tNeuron ) {
      $tNeuron->destroy ();
    }
    parent::destroy ();
  }

  /**
   * Nauka formy życia na podstawie tablicy wejść i wyjść
   *
   * @param array $Reactions
   * @param array $Stimuluses
   * @param boolean $useRandomization
   */
  public function simpleTeach($Reactions, $Stimuluses, $useRandomization = false) {

    $retVal = 0;
    
    try {
      
      foreach ( $this->Neurons as $tNeuron ) {
        
        if (isset ( $Reactions [$tNeuron->Result->ID] )) {
          /*
			  * Ucz ten neuron
			  */
          
          $tNeuronOutput = 0;
          
          /*
				 * Pobierz wszystkie stymulusy neuronu
				 */
          foreach ( $tNeuron->Weights as $tWeight ) {
            
            if (isset ( $Stimuluses [$tWeight->Stimulus->ID] )) {
              $tNeuronOutput += $Stimuluses [$tWeight->Stimulus->ID] * $tWeight->Weight;
            }
          
          }
          
          $tNeuronOutput = $tNeuron->outputFunction ( $tNeuronOutput );
          
          /*
 				 * Oblicz błąd neuronu
 				 */
          $NeuronError = $Reactions [$tNeuron->Result->ID] - $tNeuronOutput;
          
          /*
 				 * Pętla po wagach w celu obliczenia korekty dla każdej z nich
 				 */
          foreach ( $tNeuron->Weights as $tWeight ) {
            
            if (get_class ( $tWeight ) != 'neuronWeight') {
              throw new mutation ( $this->LifeFormID );
            }
            
            if (isset ( $Stimuluses [$tWeight->Stimulus->ID] )) {
              $tKorekta = $this->LearnFactor * $NeuronError * $tNeuron->outputFunctionDerivative ( $tNeuronOutput ) * $Stimuluses [$tWeight->Stimulus->ID];
              
              /*
						 * Dodatkowa randomizacja
						 */
              if ($useRandomization) {
                $tKorekta = $tKorekta * ((rand ( $this->WeightRandomization->Min, $this->WeightRandomization->Max ) / $this->WeightRandomization->Divider) + 1);
              }
              $tWeight->incWeight ( $tKorekta );
            }
          }
        }
      }
      
      /**
       * Po wykonaniu nauki, pobierz błędy
       */
      foreach ( $this->Neurons as $tNeuron ) {
        
        if (isset ( $Reactions [$tNeuron->Result->ID] )) {
          $tNeuronOutput = 0;
          
          foreach ( $tNeuron->Weights as $tWeight ) {
            
            if (isset ( $Stimuluses [$tWeight->Stimulus->ID] )) {
              $tNeuronOutput += $Stimuluses [$tWeight->Stimulus->ID] * $tWeight->Weight;
            }
          
          }
          
          $tNeuronOutput = $tNeuron->outputFunction ( $tNeuronOutput );
          
          $retVal += abs ( $Reactions [$tNeuron->Result->ID] - $tNeuronOutput );
        }
      }
    
    } catch ( Exception $e ) {
    
    }
    return $retVal;
  }

  /**
   * Zabicie formy życia
   *
   * @return boolean
   */
  public function kill() {

    if ($this->Health->Current <= 0 || $this->Age->Current >= $this->Age->Max || rand ( 0, 100 ) < $this->randomKillRatio) {
      return true;
    } else {
      return false;
    }
  
  }

  /**
   * Zmniejszenie liczby tur za którą encjak będzie mógł się rozmnoży
   *
   * @param int $amount
   * @return boolean
   */
  public function decTurnsToProcreate($amount = 1) {

    $this->Procreation->TurnsToProcreate -= $amount;
    if ($this->Procreation->TurnsToProcreate < 0) {
      $this->Procreation->TurnsToProcreate = 0;
    }
    return true;
  }

  public function procreate() {

    try {
      global $encjakArray;
      
      if (get_class ( $this->Procreation ) != 'procreation') {
        throw new mutation ( $this->LifeFormID );
      }
      
      if ($this->Procreation->TurnsToProcreate > 0) {
        $this->decTurnsToProcreate ();
        return false;
      }
      
      if ($this->Age->Current < $this->ProcreationAge) {
        return false;
      }
      
      if (parcel::sGetPopulation ( $this->Position ) < 2) {
        return false;
      }
      
      if (parcel::sGetFood ( $this->Position ) < $this->FoodPerTurn * $this->ProcreationFoodMultiplier) {
        return false;
      }
      
      if ($this->Health->Current <= floor ( $this->Health->Max / $this->ProcreationHealthDivider )) {
        return false;
      }
      
      /**
       * Sprawdz, czy jest w polu inny encjak
       */
      foreach ( $encjakArray as $tEncjak ) {
        
        if ($this->LifeFormID == $tEncjak->LifeFormID) {
          continue;
        }
        
        if (get_class ( $this ) != get_class ( $tEncjak )) {
          continue;
        }
        
        if (get_class ( $tEncjak->Procreation ) != 'procreation') {
          throw new mutation ( $tEncjak->LifeFormID );
        }
        
        if ($tEncjak->Procreation->TurnsToProcreate > 0) {
          continue;
        }
        
        if ($tEncjak->Position->X != $this->Position->X) {
          continue;
        }
        
        if ($tEncjak->Position->Y != $this->Position->Y) {
          continue;
        }
        
        if ($tEncjak->Health->Current <= floor ( $tEncjak->Health->Max / $tEncjak->ProcreationHealthDivider )) {
          continue;
        }
        
        if ($tEncjak->Age->Current < $tEncjak->ProcreationAge) {
          continue;
        }
        
        $this->doProcreate ( $tEncjak );
      
      }
      return true;
    } catch ( Exception $e ) {
      return false;
    }
  }

  /**
   * Randomizacja parametrów życiowych
   *
   * @param mixed $first
   * @param mixed $second
   * @param boolean $float
   * @param mixed $min
   * @param mixed $max
   * @return mixed
   */
  protected function randomizeParameter($first, $second, $float = false, $min = null, $max = null) {

    /*
		 * Współczynniki procentowe
		 * 98% - 
		 * 2% - 
		 */
    
    $tRand = rand ( 1, 100 );
    
    $tAvg = ($first + $second) / 2;
    $tRange = abs ( $second - $first );
    if ($tRand <= 2) {
      $tRange = $tRange * 2;
      if ($tRange == 0) {
        $tRange = $tAvg / 10;
      }
    }
    
    if ($float) {
      $tOut = randFloat ( $tAvg - $tRange, $tAvg + $tRange );
    } else {
      $tAvg = round ( $tAvg );
      $tRange = round ( $tRange );
      $tOut = rand ( $tAvg - $tRange, $tAvg + $tRange );
    }
    
    /*
		 * Obcięcie parametrów
		 */
    if ($min != null && $max != null) {
      
      if ($tOut > $max) {
        $tOut = $max;
      }
      if ($tOut < $min) {
        $tOut = $min;
      }
    }
    
    return $tOut;
  
  }

  /**
   * Rzeczywiste rozmnożenie encjaka
   *
   * @param lifeForm $Second
   */
  protected function doProcreate(lifeForm $Second) {

    try {
      
      global $encjakArray, $statistics;
      
      $this->Procreation->ChildrenCount ++;
      $this->Procreation->TurnsToProcreate = $this->Procreation->Threshold;
      
      $Second->Procreation->ChildrenCount ++;
      $Second->Procreation->TurnsToProcreate = $this->Procreation->Threshold;
      
      $tClass = get_class ( $this );
      $tNew = new $tClass ( $this->Position->X, $this->Position->Y );
      
      $tNew->Health->Max = $this->randomizeParameter ( $this->Health->Max, $Second->Health->Max, false, 30, 200 );
      $tNew->Age->Max = $this->randomizeParameter ( $this->Age->Max, $Second->Age->Max, false, 1, 200 );
      $tNew->Procreation->Threshold = $this->randomizeParameter ( $this->Procreation->Threshold, $Second->Procreation->Threshold, false, 3, 10 );
      $tNew->HealthToMove = $this->randomizeParameter ( $this->HealthToMove, $Second->HealthToMove, false, 6, 14 );
      $tNew->HealthRegeneration = $this->randomizeParameter ( $this->HealthRegeneration, $Second->HealthRegeneration, false, 1, 8 );
      $tNew->IrraticDecision = $this->randomizeParameter ( $this->IrraticDecision, $Second->IrraticDecision, false, 1, 20 );
      
      $tNew->Procreation->TurnsToProcreate = $tNew->Procreation->Threshold;
      $tNew->Health->Current = floor ( $tNew->Health->Max / 3 );
      
      /*
		 * Stwórz tablicę podręczną wag rodziców
		 */
      $tParentWeights = array ();
      foreach ( $this->Neurons as $tNeuron ) {
        
        if (get_class ( $tNeuron ) != 'neuron') {
          throw new mutation ( $this->LifeFormID );
        }
        
        $tParentWeights [0] [$tNeuron->Result->ID] ['neuron'] = $tNeuron;
        
        foreach ( $tNeuron->Weights as $tWeight ) {
          if (get_class ( $tWeight ) != 'neuronWeight') {
            throw new mutation ( $this->LifeFormID );
          }
          $tParentWeights [0] [$tNeuron->Result->ID] ['weight'] [$tWeight->Stimulus->ID] = $tWeight;
        }
      }
      
      foreach ( $Second->Neurons as $tNeuron ) {
        
        if (get_class ( $tNeuron ) != 'neuron') {
          throw new mutation ( $Second->LifeFormID );
        }
        
        $tParentWeights [1] [$tNeuron->Result->ID] ['neuron'] = $tNeuron;
        
        foreach ( $tNeuron->Weights as $tWeight ) {
          if (get_class ( $tWeight ) != 'neuronWeight') {
            throw new mutation ( $Second->LifeFormID );
          }
          $tParentWeights [1] [$tNeuron->Result->ID] ['weight'] [$tWeight->Stimulus->ID] = $tWeight;
        }
      }
      
      /**
       * Zainicjuj wagi nowych neuronów
       */
      foreach ( $tNew->Neurons as $tNeuron ) {
        
        foreach ( $tNeuron->Weights as $tWeight ) {
          if (get_class ( $tParentWeights [0] [$tNeuron->Result->ID] ['weight'] [$tWeight->Stimulus->ID] ) != 'neuronWeight') {
            throw new mutation ( $this->LifeFormID );
          }
          if (get_class ( $tParentWeights [1] [$tNeuron->Result->ID] ['weight'] [$tWeight->Stimulus->ID] ) != 'neuronWeight') {
            throw new mutation ( $Second->LifeFormID );
          }
          $tWeight->setWeight ( $this->randomizeParameter ( $tParentWeights [0] [$tNeuron->Result->ID] ['weight'] [$tWeight->Stimulus->ID]->getWeight (), $tParentWeights [1] [$tNeuron->Result->ID] ['weight'] [$tWeight->Stimulus->ID]->getWeight (), true ) );
        }
      }
      
      array_push ( $encjakArray, $tNew );
      
      parcel::sIncPopulation ( $tNew->Position );
      
      $statistics->incBorn ( $this );
      return true;
    
    } catch ( Exception $e ) {
      return false;
    }
  }

  /**
   * Zabicie pojedynczej formy życia 
   *
   * @param array $params
   */
  static function sKill($params) {

    $parcelSaver = new itemSaver ( 'parcels', $_SESSION ['loggedUserID'] );
    $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
    
    $parcelArray = array ();
    $encjakArray = array ();
    
    $parcelSaver->get ( $parcelArray );
    $lifeFormSaver->get ( $encjakArray );
    
    foreach ( $encjakArray as $tKey => $tEncjak ) {
      
      if ($tEncjak->LifeFormID == $params ['ID']) {
        parcel::sDecPopulation ( $tEncjak->Position, $parcelArray );
        unset ( $encjakArray [$tKey] );
        break;
      }
    
    }
    
    $lifeFormSaver->put ( $encjakArray );
    $parcelSaver->put ( $parcelArray );
  
  }

  /**
   * Klonowanie formy życia
   *
   * @param array $params
   */
  static function sClone($params) {

    $parcelSaver = new itemSaver ( 'parcels', $_SESSION ['loggedUserID'] );
    $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
    
    $parcelArray = array ();
    $encjakArray = array ();
    
    $parcelSaver->get ( $parcelArray );
    $lifeFormSaver->get ( $encjakArray );
    
    foreach ( $encjakArray as $tEncjak ) {
      
      if ($tEncjak->LifeFormID == $params ['ID']) {
        $tNew = clone $tEncjak;
        $tNew->LifeFormID = lifeForm::genID ();
        array_push ( $encjakArray, $tNew );
        parcel::sIncPopulation ( $tEncjak->Position );
        break;
      }
    
    }
    
    $lifeFormSaver->put ( $encjakArray );
    $parcelSaver->put ( $parcelArray );
  
  }

  /**
   * Zbiorcze zabicie umarłych form życia
   *
   * @param array $tArray
   */
  static function sQuickKill(&$tArray) {

    global $statistics;
    
    foreach ( $tArray as $tKey => $tEncjak ) {
      if ($tEncjak->kill ()) {
        $statistics->incDeath ( $tEncjak );
        parcel::sDecPopulation ( $tEncjak->Position );
        unset ( $tArray [$tKey] );
      }
    }
  
  }

  /**
   * zastosowanie wzorca uczenia do wszystkich form życia
   *
   * @param array $params
   * @return string
   */
  static function sLearnAll($params) {

    global $encjakArray;
    
    set_time_limit(360);
    
    $retVal = '';
    
    $totalError = 0;
    
    if (empty ( $encjakArray )) {
      $forceSave = true;
      $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
      $encjakArray = array ();
      $lifeFormSaver->get ( $encjakArray );
    } else {
      $forceSave = false;
    }
    
    $patternSaver = new itemSaver ( 'learnPattern', $params ['loggedUserID'] );
    $learnPattern = array ();
    $patternSaver->get ( $learnPattern );
    
    for($tIndex = 1; $tIndex <= 10; $tIndex ++) {
      
      foreach ( $encjakArray as $tEncjak ) {
        
        foreach ( $learnPattern as $tPattern ) {
          $totalError += $tEncjak->simpleTeach ( $tPattern ['reaction'], $tPattern ['stimulus'], true );
        }
      
      }
    }
    
    if ($forceSave) {
      $lifeFormSaver->put ( $encjakArray );
    }
    
    $retVal = 'Łączny średni błąd uczenia: ' . ($totalError / count ( $encjakArray ) / $params ['ID'] / count ( $learnPattern ));
    
    return $retVal;
  }

  /**
   * Uczenie formy życia na podstawie wzorca
   *
   * @param array $params
   * @return string
   */
  static function sLearn($params) {

    global $encjakArray;
    
    $retVal = '';
    
    try {
      
      $totalError = 0;
      
      if (empty ( $encjakArray )) {
        $forceSave = true;
        $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
        $encjakArray = array ();
        $lifeFormSaver->get ( $encjakArray );
      } else {
        $forceSave = false;
      }
      
      $patternSaver = new itemSaver ( 'learnPattern', $params ['loggedUserID'] );
      $learnPattern = array ();
      $patternSaver->get ( $learnPattern );
      
      foreach ( $encjakArray as $tEncjak ) {
        
        if ($tEncjak->LifeFormID == $params ['ID']) {
          
          for($tIndex = 1; $tIndex <= 10; $tIndex ++) {
            
            foreach ( $learnPattern as $tPattern ) {
              $totalError += $tEncjak->simpleTeach ( $tPattern ['reaction'], $tPattern ['stimulus'], true );
            }
          
          }
          break;
        }
      }
      
      if ($forceSave) {
        $lifeFormSaver->put ( $encjakArray );
      }
      
      $retVal = 'Łączny średni błąd uczenia: ' . ($totalError / 10 / count ( $learnPattern ));
    } catch ( Exception $e ) {
      $retVal = 'Wystąpił nieoczekiwany błąd, uczenie nie zostało przeprowadzone';
    }
    
    return $retVal;
  }

  /**
   * Zerowanie neuronów
   */
  public function zeroNeuralNetwork() {

    try {
      foreach ( $this->Neurons as $tNeuron ) {
        foreach ( $tNeuron->Weights as $tWeight ) {
          $tWeight->Weight = 0;
        }
      }
    } catch ( Exception $e ) {
      return false;
    }
    return true;
  }

  /**
   * Funkcja zerująca neurony formy życia
   *
   * @param array $params
   * @return boolean
   */
  static function sZeroNeuralNetwork($params) {

    if (empty ( $encjakArray )) {
      $forceSave = true;
      $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
      $encjakArray = array ();
      $lifeFormSaver->get ( $encjakArray );
    } else {
      $forceSave = false;
    }
    
    foreach ( $encjakArray as $tEncjak ) {
      if ($tEncjak->LifeFormID == $params ['ID']) {
        $tEncjak->zeroNeuralNetwork ();
        break;
      }
    }
    
    if ($forceSave) {
      $lifeFormSaver->put ( $encjakArray );
    }
    
    return 'Wyzerowano sieć neuronową formy życia';
  }

  /**
   * Funkcja zerowania neuronów wszystkich form życia
   *
   * @param array $params
   * @return boolean
   */
  static function sZeroAllNeuralNetworks($params) {

    global $encjakArray;
    
    if (empty ( $encjakArray )) {
      $forceSave = true;
      $lifeFormSaver = new itemSaver ( 'lifeforms', $_SESSION ['loggedUserID'] );
      $encjakArray = array ();
      $lifeFormSaver->get ( $encjakArray );
    } else {
      $forceSave = false;
    }
    
    foreach ( $encjakArray as $tEncjak ) {
      $tEncjak->zeroNeuralNetwork ();
    }
    
    if ($forceSave) {
      $lifeFormSaver->put ( $encjakArray );
    }
  
  }

}

?>