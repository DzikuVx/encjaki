<?php

class language extends item {

  public function edit(user $user, $params) {

    global $config;
    
    $retVal = '';
    
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $retVal .= $this->renderTitle ( "Języki" );
    $retVal .= $this->openForm ();
    
    $retVal .= "<table border='0' width='100%'>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Polski: </td>";
    if (! empty ( $config ['languages'] ['pl'] )) {
      $tVal = true;
    } else {
      $tVal = false;
    }
    $retVal .= "<td>" . controls::renderInput ( 'checkbox', $tVal, 'langPL' ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Angielski: </td>";
    if (! empty ( $config ['languages'] ['en'] )) {
      $tVal = true;
    } else {
      $tVal = false;
    }
    $retVal .= "<td>" . controls::renderInput ( 'checkbox', $tVal, 'langEN' ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "<tr>";
    $retVal .= "<td style='width: 150px' class='header'>Niemiecki: </td>";
    if (! empty ( $config ['languages'] ['de'] )) {
      $tVal = true;
    } else {
      $tVal = false;
    }
    $retVal .= "<td>" . controls::renderInput ( 'checkbox', $tVal, 'langDE' ) . "</td>";
    $retVal .= "</tr>";
    
    $retVal .= "</table>";
    $retVal .= controls::renderInput ( 'hidden', 'execute', 'action' );
    $retVal .= controls::renderInput ( 'hidden', get_class ( $this ), 'module' );
    $retVal .= controls::renderInput ( 'hidden', 'editExe', 'method' );
    $retVal .= "<div style='text-align: center;'>";
    $retVal .= controls::renderSubmitButton ( 'Zapisz', '', 'wymupdate formButton' );
    $retVal .= controls::renderButton ( "Anuluj", "document.location='?action=execute&amp;module=news&amp;method=browse'" );
    $retVal .= "</div>";
    
    $retVal .= $this->closeForm ();
    
    return $retVal;
  }

public function editExe(user $user, $params) {

    global $db;
    
    $retVal = "";
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $tString = '<?php ';
    
    $tString .= '$config ["languages"] = array ();';
    
    if (isset($params['langPL'])) {
      $tString .= '$config ["languages"]["pl"] = "polski"; ';
    }
    if (isset($params['langEN'])) {
      $tString .= '$config ["languages"]["en"] = "english"; ';
    }
    if (isset($params['langDE'])) {
      $tString .= '$config ["languages"]["de"] = "german"; ';
    }
    
    $tString .= ' ?>';
    
    $tFile = fopen('languages.ini.php','w');
    flock($tFile, LOCK_EX);

    fputs($tFile, $tString);
    
    flock($tFile, LOCK_UN);
    fclose($tFile);
    
    /*
     * Wygenreruj output
     */
    $retVal .= controls::displayConfirmDialog ( "Potwierdzenie", "Zapisano ustawienia", "document.location='?action=execute&amp;module=" . get_class ( $this ) . "&amp;method=edit'" );
    
    return $retVal;
  
  }
  
}

?>