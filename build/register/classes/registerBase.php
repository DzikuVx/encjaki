<?php

abstract class registerBase extends psItem {
  
  /**
   * Obiekt bazy danych
   *
   * @var dataBase
   */
  protected $db = null;
  
  /**
   * Cache współdzielony
   *
   * @var sharedCache
   */
  protected $sCache = null;
  
  /**
   * Cache zwykły
   *
   * @var cache
   */
  protected $cache = null;

  protected $language = 'pl';
  
}