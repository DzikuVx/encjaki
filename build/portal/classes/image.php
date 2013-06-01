<?php

class image extends item {

  /**
   * Enter description here...
   *
   * @param unknown_type $filename
   * @param unknown_type $new_filename
   * @param unknown_type $new_width
   * @param unknown_type $new_height
   */
  static function sResize($filename, $new_filename, $new_width, $new_height) {

    list ( $width, $height ) = getimagesize ( $filename );
    $file_type = strtoupper ( preg_replace ( "!^.*\\.!", "", $filename ) );
    switch ($file_type) {
      case 'JPG' :
      case 'JPEG' :
        $image = imagecreatefromjpeg ( $filename );
        break;
      case 'PNG' :
        $image = imagecreatefrompng ( $filename );
        break;
      case 'GIF' :
        $image = imagecreatefromgif ( $filename );
        break;
    }
    
    if ($width > $height) {
      if ($width > $new_width) {
        $scalex = $new_width / $width;
        $new_width = $width * $scalex;
        if ($height * $scalex > $new_height) {
          $scaley = $new_height / ($height * $scalex);
          $new_width = $new_width * $scaley;
          $new_height = $height * $scalex * $scaley;
        } else
          $new_height = $height * $scalex;
      } else {
        if ($height > $new_height) {
          $scaley = $new_height / $height;
          $new_height = $height * $scaley;
          $new_width = $width * $scaley;
        } else {
          $new_height = $height;
          $new_width = $width;
        }
      }
    }
    if ($height > $width) {
      if ($height > $new_height) {
        $scaley = $new_height / $height;
        $new_height = $height * $scaley;
        if ($width * $scaley > $new_width) {
          $scalex = $new_width / ($width * $scaley);
          $new_height = $new_height * $scalex;
          $new_width = $width * $scaley * $scalex;
        } else
          $new_width = $width * $scaley;
      } else {
        if ($width > $new_width) {
          $scalex = $new_width / $width;
          $new_height = $height * $scalex;
          $new_width = $width * $scalex;
        } else {
          $new_height = $height;
          $new_width = $width;
        }
      }
    }
    
    $image_p = imagecreatetruecolor ( $new_width, $new_height );
    
    imagecopyresampled ( $image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
    
    //    unlink ( $filename );
    $file_type = strtoupper ( preg_replace ( "!^.*\\.!", "", $new_filename ) );
    switch ($file_type) {
      case 'JPG' :
        imagejpeg ( $image_p, $new_filename, 100 );
        break;
      case 'PNG' :
        imagepng ( $image_p, $new_filename, 100 );
        break;
      case 'GIF' :
        imagegif ( $image_p, $new_filename );
        break;
    }
  
  }

  public function delete(user $user, $params) {

    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    return controls::displayDialog ( "Potwierdzenie", "Czy usunąć zdjęcie?", "document.location='?action=execute&amp;module=image&amp;method=deleteExe&amp;id={$params['id']}'", "document.location='?action=execute&amp;module=product&amp;method=browse'" );
  }

  public function deleteExe(user $user, $params) {

    global $db;
    
    /*
     * Sprawdz prawa dostępu
     */
    if (! $user->checkRole ( 'admin' )) {
      return controls::displayErrorDialog ( 'Brak praw dostępu' );
    }
    
    $db->execute ( "UPDATE attachments SET Deleted='yes' WHERE AttachmentID='" . $params ['id'] . "'" );
    
    return controls::displayConfirmDialog ( "Potwierdzenie", "Usunięto zdjęcie", "document.location='?action=execute&amp;module=product&amp;method=browse'" );
  }

  static function sGetProduct($ProductID) {

    global $db, $cache;
    
    $retVal = '<div class="productGallery">';
    
    $tQuery = "SELECT * FROM attachments WHERE Deleted='no' AND ProductID='$ProductID'";
    $tQuery = $db->execute ( $tQuery );
    while ( $tResult = $db->fetch ( $tQuery ) ) {
      
      $retVal .= "<a href='images/{$tResult->FileName}' lightboxed='true' title='" . product::sGetName ( $tResult->ProductID ) . "'><img alt='' title='' src='images/thumbs/{$tResult->FileName}' /></a>";
    
    }
    
    $retVal .= '</div>';
    
    return $retVal;
  
  }

}

?>