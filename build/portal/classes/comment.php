<?php

class comment extends item {

  public function delete(user $user, $params) {

    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    return controls::displayDialog ( "Potwierdzenie", "Czy usunąć komentarz?", "document.location='?action=execute&amp;module=comment&amp;method=deleteExe&amp;id={$params['id']}'", "document.location='?action=execute&amp;module=news&amp;method=browse'" );
  }

  public function deleteExe(user $user, $params) {

    global $db;
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $db->execute ( "UPDATE comment SET Deleted='yes' WHERE CommentID='" . $params ['id'] . "'" );
    
    return controls::displayConfirmDialog ( "Potwierdzenie", "Usunięto komentarz", "document.location='?action=execute&amp;module=comment&amp;method=browse'" );
  }
  
  public function accept(user $user, $params) {

    global $db;
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $db->execute ( "UPDATE comment SET Accepted='yes' WHERE CommentID='" . $params ['id'] . "'" );
    
    return controls::displayConfirmDialog ( "Potwierdzenie", "Zaakceptowano komentarz", "document.location='?action=execute&amp;module=comment&amp;method=browse'" );
  }

  /**
   * Listener dodawania komentarzy
   *
   */
  static function sAddListener() {

    global $config, $db;
    
    if ($config ['allowNewsComments'] && isset ( $_REQUEST ['addComment'] ) && $_REQUEST ['addComment'] == 'true') {
      
      if ($_SESSION ['loggedUser'] != null) {
        $tUserID = "'" . $_SESSION ['loggedUser'] . "'";
        $tUserName = "null";
        $tUserMail = "null";
      } else {
        $tUserID = "null";
        $tUserName = "'" . $_REQUEST ['Name'] . "'";
        $tUserMail = "'" . $_REQUEST ['Email'] . "'";
      }
      
      /**
       * Dodanie komentarza do newsa
       */
      if (isset ( $_REQUEST ['news'] )) {
        $tQuery = "INSERT INTO comment(Language, NewsID, UserID, UserName, UserMail, CreateTime, Text) VALUES('{$_REQUEST['language']}',{$_REQUEST['news']}, $tUserID, $tUserName, $tUserMail, '" . time () . "','{$_REQUEST['Text']}')";
      }
      
      /*
       * Dodanie komentarza do towaru
       */
      if (isset ( $_REQUEST ['product'] )) {
        $tQuery = "INSERT INTO comment(Language, ProductID, UserID, UserName, UserMail, CreateTime, Text) VALUES('{$_REQUEST['language']}',{$_REQUEST['product']}, $tUserID, $tUserName, $tUserMail, '" . time () . "','{$_REQUEST['Text']}')";
      }
      
      $db->execute ( $tQuery );
    }
  }

  static function sFormat($tResult) {

    $retVal = '';
    
    $retVal .= '<div class="commentBody">';
    $retVal .= '<div class="commentBodyDate">' . dataTypes::getDateTime ( $tResult->CreateTime ) . '</div>';
    
    if ($tResult->UserID != null) {
      $tUser = $tResult->BaseName;
    } else {
      $tUser = $tResult->UserName;
    }
    $retVal .= '<div class="commentBodyAutor">' . $tUser . '</div>';
    $retVal .= '<div class="commentBodyTresc">' . $tResult->Text . '</div>';
    $retVal .= '</div>';
    
    return $retVal;
  }

  static function sGet($NewsID = null, $ProductID = null) {

    global $db, $t, $config;
    
    $retVal = '';
    
    if ($NewsID != null && $ProductID == null) {
      $tQuery = "SELECT comment.*, user.Name AS BaseName FROM comment LEFT JOIN user ON user.UserID=comment.UserID WHERE comment.Accepted='yes' AND comment.NewsID='$NewsID' AND comment.Deleted='no' AND comment.Language='{$_REQUEST['language']}' ORDER BY comment.CreateTime DESC";
    }
    
    if ($NewsID == null && $ProductID != null) {
      $tQuery = "SELECT comment.*, user.Name AS BaseName FROM comment LEFT JOIN user ON user.UserID=comment.UserID WHERE comment.Accepted='yes' AND comment.ProductID='$ProductID' AND comment.Deleted='no' AND comment.Language='{$_REQUEST['language']}' ORDER BY comment.CreateTime DESC";
    }
    
    $tQuery = $db->execute ( $tQuery );
    while ( $tResult = $db->fetch ( $tQuery ) ) {
      $retVal .= comment::sFormat ( $tResult );
    }
    
    if ($retVal != '') {
      $retVal = '<div class="commentHead"><h4>' . $t->get ( 'comments' ) . '</h4></div>' . $retVal;
    }
    
    /**
     * dodawanie nowych komentarzy
     */
    if ($config ['allowNewsComments'] && $_SESSION ['loggedUser'] != null) {
      
      $retVal .= '<div class="commentHead"><h4>' . $t->get ( 'commentAdd' ) . '</h4></div>';
      $retVal .= '<div class="newComment">';
      $retVal .= '<form method="post" action="" name="myForm">';
      $retVal .= '<table cellpadding="3" cellspacing="0"><tbody>';
      
      if ($_SESSION ['loggedUser'] == null) {
        $retVal .= '<tr>';
        $retVal .= '<td style="width: 100px" class="fontB">' . $t->get ( 'dialogName' ) . '</td>';
        $retVal .= '<td><div class="floatL">' . controls::renderInput ( 'text', '', 'Name', 64 ) . '</div></td>';
        $retVal .= '</tr>';
        $retVal .= '<tr>';
        $retVal .= '<td style="width: 100px" class="fontB">' . $t->get ( 'dialogEmail' ) . '</td>';
        $retVal .= '<td><div class="floatL">' . controls::renderInput ( 'text', '', 'Email', 32 ) . '</div></td>';
        $retVal .= '</tr>';
      }
      
      $retVal .= '<tr>';
      $retVal .= '<td colspan="2"><div class="floatL">' . controls::renderInput ( 'textarea', '', 'Text', 2048 ) . '</div></td>';
      $retVal .= '</tr>';
      
      $retVal .= '<tr>';
      $retVal .= '<td colspan="2"><div class="floatL submit">';
      if ($_SESSION ['loggedUser'] == null) {
        $retVal .= controls::renderInput ( 'hidden', $_SESSION ['loggedUser'], 'UserID' );
      }
      $retVal .= controls::renderInput ( 'hidden', $_REQUEST ['language'], 'language' );
      if ($NewsID != null) {
        $retVal .= controls::renderInput ( 'hidden', $_REQUEST ['news'], 'news' );
      }
      if ($ProductID != null) {
        $retVal .= controls::renderInput ( 'hidden', $_REQUEST ['product'], 'product' );
      }
      $retVal .= controls::renderInput ( 'hidden', 'true', 'addComment' );
      $retVal .= controls::renderInput ( 'submit', $t->get ( 'dialogAdd' ), 'submit' ) . '</div>';
      $retVal .= '</td></tr>';
      
      $retVal .= '</tbody></table>';
      $retVal .= '</form>';
      //$retVal .= '</div><div class="clear"></div>';
    

    }
    
    return $retVal;
  }

}

?>