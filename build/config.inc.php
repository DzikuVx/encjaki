<?php

$Stimuluses = array ();

/*
 * Zdrowie
 */
$Stimuluses [1] ['Name'] = 'HealthHigh';
$Stimuluses [1] ['Source'] = 'getHealthHigh';
$Stimuluses [2] ['Name'] = 'HealthMed';
$Stimuluses [2] ['Source'] = 'getHealthMed';
$Stimuluses [3] ['Name'] = 'HealthLow';
$Stimuluses [3] ['Source'] = 'getHealthLow';

/*
 * Pożywienie
 */
$Stimuluses [7] ['Name'] = 'FoodHigh';
$Stimuluses [7] ['Source'] = 'getFoodHigh'; //jest
$Stimuluses [8] ['Name'] = 'FoodMed';
$Stimuluses [8] ['Source'] = 'getFoodMed'; //jest
$Stimuluses [9] ['Name'] = 'FoodLow';
$Stimuluses [9] ['Source'] = 'getFoodLow'; //jest


/*
 * Populacja
 */
$Stimuluses [13] ['Name'] = 'PopulationHigh';
$Stimuluses [13] ['Source'] = 'getPopulationHigh';
$Stimuluses [14] ['Name'] = 'PopulationMed';
$Stimuluses [14] ['Source'] = 'getPopulationMed';
$Stimuluses [15] ['Name'] = 'PopulationLow';
$Stimuluses [15] ['Source'] = 'getPopulationLow';

$Stimuluses [18] ['Name'] = 'MoreFoodN';
$Stimuluses [18] ['Source'] = 'getMoreFoodN';

$Stimuluses [20] ['Name'] = 'MoreFoodE';
$Stimuluses [20] ['Source'] = 'getMoreFoodE';

$Stimuluses [22] ['Name'] = 'MoreFoodW';
$Stimuluses [22] ['Source'] = 'getMoreFoodW';

$Stimuluses [24] ['Name'] = 'MoreFoodS';
$Stimuluses [24] ['Source'] = 'getMoreFoodS';

$Reactions = array ();
$Reactions [0] ['Name'] = 'Stay';
$Reactions [0] ['Reaction'] = 'reactionStay';
$Reactions [3] ['Name'] = 'GoN';
$Reactions [3] ['Reaction'] = 'reactionGoN';
$Reactions [4] ['Name'] = 'GoE';
$Reactions [4] ['Reaction'] = 'reactionGoE';
$Reactions [5] ['Name'] = 'GoW';
$Reactions [5] ['Reaction'] = 'reactionGoW';
$Reactions [6] ['Name'] = 'GoS';
$Reactions [6] ['Reaction'] = 'reactionGoS';

$worldDimension = new stdClass();
$worldDimension->X = 10;
$worldDimension->Y = 10;

/**
 * Losowy float
 *
 * @param float $min
 * @param float $max
 * @return float
 */
function randFloat($min, $max) {

	return ($min + lcg_value () * (abs ( $max - $min )));
}

/**
 * Funckja znajdująca najwyższą wartość wyjścia
 *
 * @param neuron $a
 * @param neuron $b
 * @return int
 */
function reactionSort($a, $b) {

	if ($a->Output == $b->Output) {
		return 0;
	}
	return ($a->Output > $b->Output) ? - 1 : 1;
}

function reactionRandomSort($a, $b) {

	$tA = rand ( 0, 2 );
	$tB = rand ( 0, 2 );
	
	if ($tA == $tB) {
		return 0;
	}
	return ($tA > $tB) ? - 1 : 1;
}

$config ['sendRemoteData'] = false;

itemSaver::$mongo['server'] = '10.0.0.190';
itemSaver::$mongo['port'] = 27017;
itemSaver::$mongo['dbName'] = 'Encjaki';