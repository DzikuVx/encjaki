<?php

class controls {

  /**
   * Enter description here...
   *
   * @param string $name
   * @param string $value
   * @param array $values
   * @param array $opts
   * @return unknown
   */
  static function renderSelect($name, $value = "", $values = array(), $opts = array()) {

    $retVal = "";
    
    $retVal .= "<select name='" . htmlspecialchars ( $name ) . "' id='" . $opts ['id'] . "' >";
    foreach ( $values as $k => $v ) {
      $sel = ($k == $value) ? "selected='selected'" : '';
      $retVal .= "<option $sel value='" . htmlspecialchars ( $k ) . "' >" . htmlspecialchars ( $v ) . "</option>";
    }
    $retVal .= "</select>";
    
    return $retVal;
  }

  /**
   * Wyrenderowanie pola formularza
   *
   * @param string $type
   * @param string $value
   * @param string $name
   * @param string $id
   * @param int $size
   * @param string $class
   * @param string $style
   * @return string
   */
  static public function renderInput($type, $value = "", $name = "", $id = "", $size = 30, $class = "", $style = "", $readonly = false) {
    //@todo ostylować
    $retVal = "";
    
    $originalValue = $value;
    
    if ($readonly) {
      $readonly = 'readonly';
    }else {
      $readonly = '';
    }
    
    /*
		 * Sformatuj parametr name
		 */
    if ($name != "") {
      $name = "name=\"" . $name . "\"";
    } else {
      $name = "";
    }
    
    /*
		 * Sformatuj parametr id
		 */
    if ($id != "") {
      $id = "id=\"" . $id . "\"";
    } else {
      $id = "";
    }
    
    /*
		 * Sformatuj parametr class
		 */
    if ($class != "") {
      $class = "class=\"" . $class . "\"";
    } else {
      $class = "";
    }
    
    /*
		 * Sformatuj parametr style
		 */
    if ($style != "") {
      $style = "style=\"" . $style . "\"";
    } else {
      $style = "";
    }
    
    /*
     * Sformatuj parametr value
     */
    if ($value != "") {
      $value = "value=\"" . $value . "\"";
    } else {
      $value = "";
    }
    
    if ($size > 60) {
      $tSize = "size=\"60\"";
    } else {
      $tSize = "size=\"" . $size . "\"";
    }
    
    /*
		 * Wyrenderuj input
		 */
    switch ($type) {
      case "text" :
        $retVal .= "<input $readonly type=\"text\" $tSize $name $id $value $class $style onkeyup=\"javascript:return mask(this.value,this," . $size . ",7)\" onblur=\"javascript:return mask(this.value,this," . $size . ",7)\" />\n";
        break;
      
      case "password" :
        $retVal .= "<input type=\"password\" $tSize $name $id $value $class $style onkeyup=\"javascript:return mask(this.value,this," . $size . ",7)\" onblur=\"javascript:return mask(this.value,this," . $size . ",7)\" />\n";
        break;
      
      case "number" :
        $retVal .= "<input $readonly type=\"text\" $tSize $name $id $value $class $style onkeyup=\"javascript:return mask(this.value,this," . $size . ",'digit')\" onblur=\"javascript:return mask(this.value,this," . $size . ",'digit')\" />\n";
        break;
      
      case "decimal" :
        $retVal .= "<input $readonly type=\"text\" $tSize $name $id $value $class $style onkeyup=\"javascript:return mask(this.value,this," . $size . ",'digit_dot')\" onblur=\"javascript:return mask(this.value,this," . $size . ",'digit_dot')\" />\n";
        break;
      
      case "checkbox" :
        if ($originalValue === true || $originalValue == 'yes') {
          $checked = "checked";
        } else {
          $checked = "";
        }
        $retVal .= "<input $readonly type=\"checkbox\" value=\"1\" $name $id $class $style $checked  />\n";
        break;
      
      case "assigner" :
        $retVal .= "<input type=\"checkbox\" $value $name $id $class $style />\n";
        break;
      
      case "hidden" :
        $retVal .= "<input type=\"hidden\" $value $name $id />\n";
        break;
      
      case "submit" :
        $retVal .= "<input type=\"submit\" $value $name $id />\n";
        break;
      
      case "textarea" :
        $retVal .= "<textarea $name $id $class $style onkeyup=\"javascript:return mask(this.value,this," . $size . ",7)\" onblur=\"javascript:return mask(this.value,this," . $size . ",7)\" cols=\"36\" rows=\"8\">" . $originalValue . "</textarea>\n";
        
        break;
      
      case "html" :
        $retVal .= "<textarea $name $id $class $style cols=\"70\" rows=\"4\">" . $originalValue . "</textarea>\n";
        break;
    
    }
    
    return $retVal;
  }

  /**
   * Wyświetlenie dialogu o błędzie
   *
   * @param string $dialogText
   * @param string $returnLink
   * @return string
   */
  static function displayErrorDialog($dialogText, $returnLink = null) {

    //@todo: dodać style do register.css
    $retVal = "<div style=\"text-align: center;\">";
    $retVal .= "<center>";
    $retVal .= "<div class=\"errorBox\" style=\"margin: 40px;\">";
    $retVal .= "<div class=\"errorTitle\">Błąd</div>";
    $retVal .= "<div class=\"errorText\">{$dialogText}</div>";
    if ($returnLink != null) {
      $retVal .= "<div style=\"text-align: center;\">" . controls::renderButton ( "Zamknij", $returnLink ) . "</div>";
    }
    $retVal .= "</div>";
    $retVal .= "</center>";
    $retVal .= "</div>";
    
    return $retVal;
  }

  /**
   * Wyświetlenie dialogu potwierdzającego
   *
   * @param string $dialogTitle
   * @param string $dialogText
   * @param string $returnLink
   * @return string
   */
  static function displayConfirmDialog($dialogTitle, $dialogText, $returnLink = null, $style = "width: 350px; margin-top: 100px;") {

    //@todo ostylować
    $retVal = "<div style=\"text-align: center;\">";
    $retVal .= "<center>";
    $retVal .= "<div class=\"confirmBox\" style=\"" . $style . "\">";
    $retVal .= "<div class=\"confirmTitle\">{$dialogTitle}</div>";
    $retVal .= "<div class=\"confirmText\">{$dialogText}</div>";
    if ($returnLink != null) {
      $retVal .= "<div style=\"text-align: center;\">" . controls::renderButton ( "Dalej", $returnLink ) . "</div>";
    }
    $retVal .= "</div>";
    $retVal .= "</center>";
    $retVal .= "</div>";
    
    return $retVal;
  }

  /**
   * Wyświetelenie dialogu Tak/Nie
   *
   * @param string $dialogTitle
   * @param string $dialogText
   * @param string $yesLink
   * @param string $noLink
   * @return string
   */
  static function displayDialog($dialogTitle, $dialogText, $yesLink = null, $noLink = null) {
    //@todo ostylować
    $retVal = "<div style=\"text-align: center;\">";
    $retVal .= "<center>";
    $retVal .= "<div class=\"confirmBox\" style=\"width: 350px; margin-top: 100px;\">";
    $retVal .= "<div class=\"confirmTitle\">{$dialogTitle}</div>";
    $retVal .= "<div class=\"confirmText\">{$dialogText}</div>";
    $retVal .= "<div style=\"text-align: center;\">";
    if ($yesLink != null) {
      $retVal .= controls::renderButton ( "Tak", $yesLink );
    }
    if ($noLink != null) {
      $retVal .= controls::renderButton ( "Nie", $noLink );
    }
    $retVal .= "</div>";
    $retVal .= "</div>";
    $retVal .= "</center>";
    $retVal .= "</div>";
    
    return $retVal;
  }

  /**
   * Funkcja renderująca przycisk z funkcją obsługi JS
   * @param $name - nazwa przycisku
   * @param $onclick - zdarzenie onclick
   * @param $style - opcjonalne wartości stylu dla elementu
   * @param $class - klasa CSS, domyślnie formButton
   * @return kod HTML
   */
  static function renderButton($name, $onclick = null, $style = null, $class = null) {

    //@todo ostylować
    if ($style != null) {
      $style = "style=\"" . $style . "\"";
    } else {
      $style = "";
    }
    
    if ($onclick != null) {
      $onclick = "onclick=\"" . $onclick . "\"";
    } else {
      $onclick = "";
    }
    
    if ($class == null) {
      $class = "formButton";
    }
    
    return "<input $style class=\"$class\" type=\"button\" value=\"$name\" $onclick />";
  }

  static function renderSubmitButton($name, $style = null, $class = null) {
    //@todo ostylować
    if ($style != null) {
      $style = "style=\"" . $style . "\"";
    } else {
      $style = "";
    }
    
    if ($class == null) {
      $class = "formButton";
    }
    
    return "<input $style class=\"$class\" type=\"submit\" value=\"$name\" />";
  }

  /**
   * Funkcja renderująca przycisk typu IMG
   * @param $type - typ przycisku: info/edit/delete/... etc.
   * @param $onclick - zdarzenie onclick
   * @param $name - opis przycisku
   * @param $class - klasa CSS, domyślnie img_link
   * @return kod HTML
   */
  static function renderImgButton($type, $onclick, $name, $class = "link") {

    switch ($type) {
      case "info" :
        $imgAddr = "gfx/info.gif";
        break;
      
      case "add" :
        $imgAddr = "gfx/add.gif";
        break;
      
      case "edit" :
        $imgAddr = "gfx/edit.gif";
        break;
      
      case "teach" :
        $imgAddr = "gfx/teach.png";
        break;
      
      case "zeroNetwork" :
        $imgAddr = "gfx/zeroNetwork.png";
        break;
      
      case "delete" :
        $imgAddr = "gfx/del2.gif";
        break;
      
      case "all" :
        $imgAddr = "gfx/all.png";
        break;
      
      case "none" :
        $imgAddr = "gfx/none.png";
        break;
      
      case "yes" :
        $imgAddr = "gfx/yes.png";
        break;
      
      case "no" :
        $imgAddr = "gfx/no.png";
        break;
      
      case "up" :
        $imgAddr = "gfx/up3.gif";
        break;
      
      case "down" :
        $imgAddr = "gfx/down3.gif";
        break;
      
      case "left" :
        $imgAddr = "gfx/left.gif";
        break;
      
      case "right" :
        $imgAddr = "gfx/right.gif";
        break;
      
      case "popupOpen" :
        $imgAddr = "gfx/strzala3.gif";
        break;
    }
    return "<a href=\"#\" title=\"$name\" class=\"pasek_link\"><img src=\"$imgAddr\" class=\"$class\" onclick=\"$onclick\" /></a>";
  }

}
?>