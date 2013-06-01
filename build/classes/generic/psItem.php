<?php

//@todo okomentować
abstract class psItem {
  
  protected $data = null;

  public function getData() {

    return $this->data;
  }

  public function __construct($id = null) {

    if (! empty ( $id )) {
      $this->load ( $id );
    }
  }

}

?>