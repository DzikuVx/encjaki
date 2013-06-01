<?php

class mutationWatcher {

	protected $LifeFormID = array ();

	public function push($LifeFormID) {

		array_push ( $this->LifeFormID, $LifeFormID );
	}

	public function clean(&$tArray) {

		if (! empty ( $this->LifeFormID )) {
			 
			global $config;
			 
			if (!empty($config ['sendRemoteData'])) {
				psDebug::send ( 'Uruchomiono strażnika mutacji' );
			}
			 
			foreach ( $tArray as $tKey => $tItem ) {
				if (in_array ( $tItem->LifeFormID, $this->LifeFormID )) {
					unset ( $tArray [$tKey] );
				}
			}
		}

	}

}