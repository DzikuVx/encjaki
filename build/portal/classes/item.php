<?php

class item {
  
  protected $displayComments = false;
  
  protected $modified = false;
  
  protected $dataObject = null;

  public function dummy() {

    return true;
  }

  public function browse($user, $params) {

    $className = get_class ( $this ) . "Registry";
    
    $item = new $className ( );
    $retVal = $item->browse ( $user, $params );
    
    return $retVal;
  }

  protected function renderTitle($text) {

    return "<div class=\"registryTitle\">{$text}</div>";
  }

  protected function openForm() {

    $retVal = "<form method='post' action='' name='myForm'>";
    
    return $retVal;
  }

  protected function closeForm() {

    $retVal = "</form>";
    
    return $retVal;
  }

}

?>