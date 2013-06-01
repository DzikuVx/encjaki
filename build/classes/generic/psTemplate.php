<?php

/**
 * Simple template class
 *
 * @author Pawel Spychalski <pawel@spychalski.info>
 * @link http://www.spychalski.info
 * @category universal
 * @version 0.6
 * @todo template caching
 */
class psTemplate {

  /**
   * Template file path
   *
   * @var string
   */
  protected $fileName;

  /**
   * Template string
   *
   * @var string
   */
  protected $template;

  protected $cacheModule = null;
  protected $cacheProperty = null;
  
  /**
   * Construct function
   *
   * @param string $fileName
   * @param string $cacheModule
   * @param string $cacheProperty
   * @return boolean
   */
  public function __construct($fileName, $cacheModule = null, $cacheProperty = null) {

    $this->fileName = $fileName;
    $this->load ();
    
    $this->cacheModule = $cacheModule;
    $this->cacheProperty = $cacheProperty;
    
    return true;
  }

  /**
   * Template load
   *
   */
  protected function load() {

    try {
      if (file_exists ( $this->fileName )) {

        $tFile = fopen ( $this->fileName, 'r' );

        flock ( $tFile, LOCK_SH );

        $this->template = fread ( $tFile, filesize ( $this->fileName ) );

        flock ( $tFile, LOCK_UN );
        fclose ( $tFile );

      } else {
        throw new Exception ( 'Brak pliku' );
      }
    } catch ( Exception $e ) {
      throw new Exception ( 'Błąd otwarcia szablonu' );
    }
  }

  /**
   * Template reload
   *
   */
  public function reset() {

    $this->load ();
  }

  /**
   * Adding new position to template
   *
   * @param mixed $key
   * @param string $value
   * @return boolean
   */
  public function add($key, $value = null) {

    try {

      if ($value !== null) {
        $this->template = str_replace ( '{' . $key . '}', $value, $this->template );
      } else {
        foreach ( $key as $tKey => $tValue ) {
          $this->add ( $tKey, $tValue );
        }
      }
    } catch ( Exception $e ) {
      return false;
    }

    return true;
  }

  /**
   * Conditional block removal
   *
   * @param string $key
   */
  public function remove($key) {

    $this->template = preg_replace ( '!({C:' . $key . '}.*{/C:' . $key . '})!ms', '', $this->template );
  }

  /**
   * Template render
   *
   * @return string
   */
  public function get() {

    $this->template = preg_replace_callback ( '!({T:[^}]*})!', array ($this, 'translationReplacer' ), $this->template );

    $this->template = preg_replace ( '!({C:[^}]*})!', '', $this->template );
    $this->template = preg_replace ( '!({/C:[^}]*})!', '', $this->template );

    return $this->template;
  }

  /**
   * Template translation parsing
   *
   * @param array $matches
   * @return string
   */
  protected function translationReplacer($matches) {

    global $t;

    $retval = $matches [1];
    $retval = mb_substr ( $retval, 3, - 1 );

    $retval = $t->get ( $retval );

    return $retval;
  }

  /**
   * __toString magic function
   *
   * @return string
   */
  public function __toString() {

    return $this->get ();
  }

}