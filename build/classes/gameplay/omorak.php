<?php

/**
 * Omorak żywi się ratompkami
 * Klasa bazowa dla wszystkich mięsożerców
 *
 */
class omorak extends encjak {

	public $LearnFactor = 0.2;
	public $FoodPerTurn = 0;
	public $HealthToMove = 2;
	public $HealthRegeneration = 6;
	public $IrraticDecision = 5;
	public $ProcreationThreshold = 8;
	public $ProcreationAge = 16;
	public $MaxAge = 150;
	public $MaxHealth = 150;
	public $ProcreationFoodMultiplier = 0;
	public $randomKillRatio = 1;
	public $foodClassName = 'ratompek';

	/**
	 * Mnożnik aktualnego zdrowia poniżej którego rozpoczyna karmienie
	 *
	 * @var float
	 */
	public $FeedHealthThreshold = 0.80;

	public function feed() {

		global $encjakArray, $parcelArray;

		/*
		 * Sprawdz, czy zdrowie omoraka spadło do zakładaną wartość
		*/
		if ($this->getHealth ()->Current < $this->getHealth ()->Max * $this->FeedHealthThreshold) {
			/*
			 * Rozpocznij próbę karmienia się ratompkami
			*/

			/*
			 * Pobierz pierwszego ratopmka w polu
			*/
			$tPrey = null;
			foreach ( $encjakArray as $tEncjak ) {
				if (get_class ( $tEncjak ) == $this->foodClassName && $tEncjak->getHealth ()->Current > 0 && $tEncjak->Position->X == $this->Position->X && $tEncjak->Position->Y == $this->Position->Y) {
					$tPrey = $tEncjak;
					break;
				}
			}

			if ($tPrey != null) {
				$this->incHealth ( $tPrey->getHealth ()->Current );
				$tPrey->setHealth ( 0 );

				global $config;

				if (!empty($config ['sendRemoteData'])) {
					psDebug::send ( $this->foodClassName . ' killed by ' . get_class ( $this ) . ' at ' . $this->Position->X . '/' . $this->Position->Y );
				}

			} else {
				$this->decHealth ( $this->HealthRegeneration );
			}
		} else {

			/**
			 * Jeśli nie karmił się, zmiejsz jego zdrowie
			 */
			$this->decHealth ( $this->HealthRegeneration );
		}

	}

	protected function compareFood(position $current, position $target) {

		$retVal = 0;

		if (parcel::sGetLifeFormHealth ( $target, $this->foodClassName ) > parcel::sGetLifeFormHealth ( $current, $this->foodClassName )) {
			$retVal += 0.5;
		}

		if (parcel::sGetLifeFormHealth ( $target, $this->foodClassName ) >= 66) {
			$retVal += 0.5;
		}

		if ($retVal == 0) {
			$retVal = - 1;
		}

		return $retVal;
	}

	public function getFoodHigh() {

		//@todo: decyzja o ilości pożywienia na podstawie partametrów formy życia
		if (parcel::sGetLifeFormHealth ( $this->Position, $this->foodClassName ) > 66) {
			return 1;
		} else {
			return - 1;
		}
	}

	public function getFoodMed() {

		if (parcel::sGetLifeFormHealth ( $this->Position, $this->foodClassName ) <= 66 && parcel::sGetLifeFormHealth ( $this->Position, $this->foodClassName ) >= 33) {
			return 1;
		} else {
			return - 1;
		}
	}

	public function getFoodLow() {

		if (parcel::sGetLifeFormHealth ( $this->Position, $this->foodClassName ) < 33) {
			return 1;
		} else {
			return - 1;
		}
	}

}

?>