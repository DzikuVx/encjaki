<?php

class baseStat {
  
  public static $colors = array (array (0, 114, 187 ), array (121, 163, 65 ), array (255, 202, 8 ), array (247, 146, 30 ), array (239, 28, 37 ), array (181, 84, 163 ), array (142, 145, 144 ), array (210, 105, 30 ), 

  array (50, 144, 207 ), array (151, 193, 165 ), array (205, 182, 8 ), array (255, 255, 0 ), array (255, 177, 177 ), array (255, 177, 255 ), array (14, 14, 14 ), array (230, 165, 70 ), 

  array (70, 174, 247 ), array (171, 213, 185 ), array (165, 152, 8 ), 

  array (235, 0, 235 ) );
  
  public $chartWidth = 800;
  public $chartHeight = 400;
  public $plugin = '';
  public $use3D = true;

  public $chartType = 'lines';

  function __construct() {

    require_once dirname ( __FILE__ ).'/../vendor/OFC/php-ofc-library/open-flash-chart-object.php';
    require_once dirname ( __FILE__ ).'/../vendor/OFC/php-ofc-library/open-flash-chart.php';
  
  }

  function chartHTML() {

    if (! file_exists (  $_SERVER ['DOCUMENT_ROOT'] . dirname ( $_SERVER ['REQUEST_URI'] ) . "/userData/" . $this->plugin . "." . $_SESSION ['loggedUserID'] . ".ofc" ))
      return '';
    
    $src = 'http://' . $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ['SERVER_PORT'] . dirname ( $_SERVER ['REQUEST_URI'] );
    $src = preg_replace ( '|ajax$|', '', $src );
    $src .= "/userData/" . $this->plugin . "." . $_SESSION ['loggedUserID'] . ".ofc";
    
    $base = '';
    $base .= 'http';
    $base .= '://';
    $base .= $_SERVER ['SERVER_NAME'];
    $base .= ':' . $_SERVER ['SERVER_PORT'];
    $base .= substr ( str_replace ( '\\', '/', __FILE__ ), strlen ( $_SERVER ['DOCUMENT_ROOT'] ), - strlen ( '/classes/baseStat.php' ) );
    
    return open_flash_chart_object_str ( $this->chartWidth, $this->chartHeight, $src, false, $base . '/vendor/OFC/' );
  }

  function Color($i) {

    $str = sprintf ( '%02X%02X%02X', self::$colors [$i] [0], self::$colors [$i] [1], self::$colors [$i] [2] );
    return '#' . $str;
  }

  function genChart($params = null) {

    if (count ( $this->data ) > 0) {
      $min = null;
      $max = null;
      
      $names = array ();
      foreach ( $this->data as $czas => $val ) {
        if ($this->use3D) {
          unset ( $val ['AXIS_X'] );
          foreach ( array_keys ( $val ) as $key ) {
            $names [$key] = $key;
          }
        }
      }
      
      $chart = new open_flash_chart ( );
      
      $chart->set_bg_colour ( '#FFFFFF' );
      
      switch (( string ) $this->chartType) {
        case "pie" :
          
          $data = array ();
          $names = array ();
          
          foreach ( array_keys ( $this->data ) as $czas ) {
            $vals = $this->data [$czas];
            unset ( $vals ['AXIS_X'] );
            foreach ( $vals as $y => $val ) {
              $name = '';
              $name = $this->data [$czas] ['AXIS_X'] . ' ' . $name;
              $data [] = new pie_value ( ( float ) $val, $name );
              $names [] = $name;
            }
          }
          
          $g = new pie ( );
          $g->set_start_angle ( 35 );
          $g->set_animate ( true );
          
          $g->set_values ( $data, $names );
          $g->set_tooltip ( '#label#<br>#val# (#percent#)' );
          
          $chart->add_element ( $g );
          break;
        
        case "lines" :
        case "bars" :
          $data = array ();
          $bars = array ();
          $iksy = array ();
          
          $barKind = '';
          //$barKind = 'bar_sketch';
          if (( string ) $this->chartType == 'lines') {
            $barKind = 'line';
          }
          if (( string ) $this->chartType == 'bars') {
            $barKind = 'bar_glass';
          }
          
          if ($this->use3D) { // 3d
            

            $revert = array ();
            $cnt = 0;
            foreach ( $this->columns as $key => $column ) {
              if ($key == 'AXIS_X')
                continue;
              $revert [$key] = $cnt ++;
              switch ($barKind) {
                case 'bar_sketch' :
                  $bars [$revert [$key]] = new $barKind ( 75, 4, $this->Color ( $cnt - 1 ), '#000000' );
                  break;
                
                case 'bar_glass' :
                  $bars [$revert [$key]] = new $barKind ( 75, $this->Color ( $cnt - 1 ), '#000000' );
                  break;
                
                case 'line' :
                  $bars [$revert [$key]] = new $barKind ( 2, $this->Color ( $cnt - 1 ) );
                  break;
                
                default :
                  $bars [$revert [$key]] = new $barKind ( 75, $this->Color ( $cnt - 1 ) );
                  break;
              }
              
              $bars [$revert [$key]]->set_tooltip ( $column ['title'] . '<br>#val#' );
              $bars [$revert [$key]]->set_colour ( $this->Color ( $cnt - 1 ) );
              $bars [$revert [$key]]->set_key ( $column ['title'], 10 );
            }
            
            foreach ( array_keys ( $this->data ) as $czas ) {
              $iksy [] = $this->data [$czas] ['AXIS_X'];
              
              $val = $this->data [$czas];
              $tmp_data = array ();
              foreach ( array_keys ( $this->columns ) as $key ) {
                if ($key == 'AXIS_X')
                  continue;
                $ile = 0;
                if (isset ( $val [$key] ))
                  $ile = ( float ) $val [$key];
                if (is_null ( $min ) || ($ile < $min))
                  $min = $ile;
                if (is_null ( $max ) || ($ile > $max))
                  $max = $ile;
                
                $tmp_data [] = $ile;
                $bars [$revert [$key]]->values [] = $ile;
              }
            }
            
            $x = new x_axis ( );
            $x->set_labels_from_array ( $iksy );
            $chart->set_x_axis ( $x );
            $y = new y_axis ( );
            
            $chart->set_y_axis ( $y );
          
          } else {
            
            switch ($barKind) {
              case 'bar_sketch' :
                $bars [0] = new $barKind ( 75, 4, $this->Color ( 0 ), '#000000' );
                break;
              
              case 'bar_glass' :
                $lineClass = 'bar_glass_value';
                $bars [0] = new $barKind ( 75, $this->Color ( 0 ), '#000000' );
                break;
              
              case 'line' :
                $lineClass = 'dot_value';
                $bars [0] = new line_dot ( 2, $this->Color ( 0 ) );
                break;
              
              default :
                $bars [0] = new $barKind ( 75, $this->Color ( 0 ) );
                break;
            }
            
            $axis_y = '1';
            foreach ( $this->columns as $columnName => $column ) {
              if (isset ( $column ['type'] ) && ($column ['type'] == 'numeric')) {
                $axis_y = $columnName;
                break;
              }
            }
            
            $bars [0]->set_key ( $this->columns [$axis_y] ['title'], 10 );
            $data = array ();
            foreach ( $this->data as $czas => $val ) {
              $czas = $val ['AXIS_X'];
              @$ile = ( float ) $val [$axis_y];
              if (is_null ( $min ) || ($ile < $min))
                $min = $ile;
              if (is_null ( $max ) || ($ile > $max))
                $max = $ile;
              
              $value = new $lineClass ( ( float ) $ile, $this->Color ( 0 ) );
              $value->set_tooltip ( $this->columns [$axis_y] ['title'] . ' (' . $czas . ') = ' . $ile );
              $data [] = $value;
              $iksy [] = $czas;
            }
            $bars [0]->set_tooltip ( '#label#<br>#val#' );
            $bars [0]->set_values ( $data );
            
            $x = new x_axis ( );
            $x->set_labels_from_array ( $iksy );
            $chart->set_x_axis ( $x );
          }
          
          foreach ( $bars as $bar ) {
            $chart->add_element ( $bar );
          }
          
          $y = new y_axis ( );
          $y->set_range ( $min, $max, $max / 2 );
          
          if (isset ( $params ['remove_y_axis'] ) && $params ['remove_y_axis'] = true) {
            $y->set_labels ( array () );
          }
          
          $chart->set_y_axis ( $y );
          
          break;
      }
      file_put_contents ( $_SERVER ['DOCUMENT_ROOT'] . dirname ( $_SERVER ['REQUEST_URI'] ) . "/userData/" . $this->plugin . "." . $_SESSION ['loggedUserID'] . ".ofc", $chart->toPrettyString () );
    } else {
      @unlink ( $_SERVER ['DOCUMENT_ROOT'] . dirname ( $_SERVER ['REQUEST_URI'] ) . "/userData/" . $this->plugin . "." . $_SESSION ['loggedUserID'] . ".ofc" );
    }
  }

}
