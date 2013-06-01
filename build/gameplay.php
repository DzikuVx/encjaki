<?php

session_start ();

require_once 'common.php';
require_once 'config.inc.php';

ob_start ( "ob_gzhandler" );

if (empty ( $_SESSION ['loggedUserID'] )) {
  header ( 'Location: index.php' );
}

$tUserID = $_SESSION ['loggedUserID'];

//@todo sprawdzenie, czy istnieją pliki, czyli czy należy wygenerować nowe formy życia

$statistics = new statistics ( );
$mutationWatcher = new mutationWatcher ( );

$indexTemplate = new psTemplate('templates/mainGameScreen.html');

$indexTemplate->add('loggedUserID',$tUserID);

/*
 * Pobierz główne wskaźniki
 */
$lifeFormTextField = '';

$parcelSaver = new itemSaver ( 'parcels', $tUserID );
$lifeFormSaver = new itemSaver ( 'lifeforms', $tUserID );

/**
 * Pobranie danych dodatkowych
 */
$dataSaver = new itemSaver ( 'data', $tUserID );
$dataSaver->get ( $dataArray );

/**
 * @var parcel
 */
$parcelArray = array ();
$parcelSaver->get ( $parcelArray );

$encjakArray = array ();
$lifeFormSaver->get ( $encjakArray );

/*
 * Wygeneruj pożywienie na mapie
 */
parcel::sGenerateFood ( $parcelArray );

//@todo : czasowa naprawa liczby form życia
parcel::sFixPopulationCount ( $parcelArray, $encjakArray );

/**
 * Pętla operacji na encjakach
 */
foreach ( $encjakArray as $tEncjak ) {
  
  //@todo: włączyć autolearn po naprawieniu
  //  $tEncjak->autoLearn ();
  $tEncjak->react ();
  $tEncjak->feed ();
  $tEncjak->procreate ();
  $tEncjak->incAge ();
}

lifeForm::sQuickKill ( $encjakArray );

$indexTemplate->add('statisticsTable',statistics::sGetStatistics ( $tUserID ));

/**
 * Wyświetl podsumowanie
 */
//@todo: włączyć listę form życia po poprawieniu wyglądu
//echo '<div style="float: left; height: 300px; overflow: auto; width: 270px; border: solid; border-width: 1px; padding: 3px;">' . $lifeFormTextField . '</div>';

/*
 * Prosta Mapa
 */
$parcelMap = '<table class="map">';
for($tIndexY = 1; $tIndexY <= $worldDimension->Y; $tIndexY ++) {
  
  $parcelMap .= '<tr>';
  for($tIndexX = 1; $tIndexX <= $worldDimension->X; $tIndexX ++) {
    $Item = $parcelArray [$tIndexX] [$tIndexY];
    
    $parcelMap .= '<td onclick="getParcelInfo(\'' . $tIndexX . '\',\'' . $tIndexY . '\')">';
    $parcelMap .= $Item->getFood ()->Current . '<br />';
    $parcelMap .= $Item->LifeFormCount;
    $parcelMap .= '</td>';
  }
  $parcelMap .= '</tr>';
}
$parcelMap .= '</table>';
$indexTemplate->add('parcelMap',$parcelMap);
unset($parcelMap);
/*
 * prosta mapa - koniec
 */

$mutationWatcher->clean ( $encjakArray );

/*
 * Zapisz dane form życia
 */
$lifeFormSaver->put ( $encjakArray );

/*
 * Zapisz dane parceli
 */
$parcelSaver->put ( $parcelArray );

$dataSaver->put ( $dataArray );

/*
 * Wygeneruj wykresy
 */
$indexTemplate->add('populationChart', statistics::sGenPopulationChart ( $tUserID ));
$indexTemplate->add('foodProductionChart', statistics::sGenFoodChart ( $tUserID ));
$indexTemplate->add('avaibleFoodChart', statistics::sGenFoodAvaibleChart ( $tUserID ));

//@todo: blokada ekranu po odpaleniu funkcji uczenia

/*
 * Przyciski funkcyjne
 */
$controlButtons = '';
$controlButtons .= controls::renderButton ( 'Pełne pożywienie', "executeSimpleGet('parcel','sSetMaxFood',null,true);" );
$controlButtons .= controls::renderButton ( 'Okno uczenia', "executeSimpleGet('lifeForm','sRenderLearnWindow','next',false,'#univPanel', 'modal');" );
$controlButtons .= controls::renderButton ( 'Nowy Wzorzec', "executeSimpleGet('lifeForm','sRenderLearnWindow','new',false,'#univPanel', 'modal');" );
$controlButtons .= controls::renderButton ( 'Ucz wszystkie', "executeSimpleGet('lifeForm','sLearnAll',10,false,'#univPanel', 'modal');" );
$controlButtons .= controls::renderButton ( 'Zeruj wszystkie sieci', "executeSimpleGet('lifeForm','sZeroAllNeuralNetworks',null,false,null);" );
$indexTemplate->add('controlButtons',$controlButtons);


echo $indexTemplate;

ob_flush ();


/*
 * Generowanie nowego encjaka
 */
//array_push ( $encjakArray, new encjak ( rand ( 1, 10 ), rand ( 1, 10 )) );
//array_push ( $encjakArray, new ratompek ( rand ( 1, 10 ), rand ( 1, 10 )) );
//array_push ( $encjakArray, new omorak ( 3, 9) );
//array_push ( $encjakArray, new krambol ( 3, 9) );
//array_push ( $encjakArray, new krambol ( 3, 9) );
//array_push ( $encjakArray, new krambol ( 3, 9) );
//array_push ( $encjakArray, new krambol ( 3, 9) );
//array_push ( $encjakArray, new ratompek ( 3, 9) );


/**
 * testowe generowanie mapy
 */
/*$tParcels = array ();
parcel::sQuickInit ( $tParcels, 10, 110, 7 );

$sizeY = count ( $tParcels );
$sizeX = count ( $tParcels [$sizeY] );

echo $sizeX . ' ' . $sizeY;

$retVal = '<div><table class="map">';
for($tIndexY = 1; $tIndexY <= $sizeY; $tIndexY ++) {
  
  $retVal .= '<tr>';
  for($tIndexX = 1; $tIndexX <= $sizeX; $tIndexX ++) {
    $Item = $tParcels [$tIndexX] [$tIndexY];
    $retVal .= '<td onclick="getParcelInfo(\'' . $tIndexX . '\',\'' . $tIndexY . '\')">';
    $retVal .= $Item->getFood ()->Current . '<br />';
    $retVal .= $Item->getFood ()->Max . '<br />';
    $retVal .= $Item->getFood ()->Regeneration . '<br />';
    $retVal .= '</td>';
  }
  $retVal .= '</tr>';
}

$retVal .= '</table></div>';

echo $retVal;*/