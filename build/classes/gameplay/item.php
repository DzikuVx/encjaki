<?php

abstract class item {

  /**
   * Metoda globalna zapewniająca synchronizację
   */
  public function destroy() {

    unset ( $this );
  }

  protected function save() {

    return true;
  }

}
?>