<?php

/**
 * Klasa menu
 *
 */
class leftMenu {

  /**
   * Wygenerowanie menu
   *
   * @return string
   */
  function populate(user $user) {

    $retVal = "<div class='boxTitle'>Menu</div>";
    
    $retVal .= "<div class='menuLevel1'>Newsy</div>";
    $retVal .= "<div class='menuContainer'>";
    $retVal .= "<div class='menuLevel2' onclick=\"document.location='?action=execute&amp;module=news&amp;method=add'\">Nowy</div>";
    $retVal .= "<div class='menuLevel2' onclick=\"document.location='?action=execute&amp;module=news&amp;method=browse'\">Przeglądaj</div>";
    $retVal .= "<div class='menuLevel2' onclick=\"document.location='?action=execute&amp;module=specialNews&amp;method=browse'\">Dane</div>";
    $retVal .= "</div>";
    
    if ($user->checkRole ( 'admin' )) {
      
      $retVal .= "<div class='menuLevel1'>Komentarze</div>";
      $retVal .= "<div class='menuContainer'>";
      $retVal .= "<div class='menuLevel2' onclick=\"document.location='?action=execute&amp;module=comment&amp;method=browse'\">Przeglądaj</div>";
      $retVal .= "</div>";
      
      $retVal .= "<div class='menuLevel1'>Linki</div>";
      $retVal .= "<div class='menuContainer'>";
      $retVal .= "<div class='menuLevel2' onclick=\"document.location='?action=execute&amp;module=link&amp;method=browse'\">Przeglądaj</div>";
      $retVal .= "</div>";
      
      $retVal .= "<div class='menuLevel1'>Użytkownicy</div>";
      $retVal .= "<div class='menuContainer'>";
      $retVal .= "<div class='menuLevel2' onclick=\"document.location='?action=execute&amp;module=user&amp;method=browse'\">Przeglądaj</div>";
      $retVal .= "</div>";
      
    }
    
    $retVal .= "<div class='menuLevel1' onclick=\"document.location='?action=userLogout';\">Wyloguj</div>";
    
    return $retVal;
  }

}
?>