<?php

class userRegistry extends baseRegistry {
  
  protected $itemClass = "user";
  
  protected $allowDetail = true;
  
  protected $selectList = "
    user.* ";
  
  protected $tableList = "user";
  protected $extraList = "user.Deleted='no'";
  protected $selectCountField = "user.UserID";
  
  protected $defaultSorting = "user.Login";
  
  protected $defaultSortingDirection = 'ASC';
  
  protected $registryIdField = "UserID";
  
  protected $registryTitle = "Rejestr użytkowników";

  protected function prepare() {

    $this->searchTable ['comment.Login'] = "Login";
    $this->searchTable ['comment.Name'] = "Nazwa";
    $this->searchTable ['comment.Email'] = "e-mail";
    
    $this->useSearchSelects ['dateSelect'] = false;
    
    $this->tableColumns ['#'] = "Lp.";
    $this->tableColumns ['Login'] = "Login";
    $this->tableColumns ['Name'] = "Nazwa";
    $this->tableColumns ['Email'] = "e-amil";
    $this->tableColumns ['Role'] = "Rola";
    $this->tableColumns ['Language'] = "Język";
    $this->tableColumns ['__operations__'] = "&nbsp;";
    
    $this->rightsSet ['moduleName'] = "User";
    $this->rightsSet ['allowAdd'] = false;
    $this->rightsSet ['allowEdit'] = true;
    $this->rightsSet ['allowDelete'] = true;
    $this->rightsSet ['addRight'] = "admin";
    $this->rightsSet ['editRight'] = "admin";
    $this->rightsSet ['deleteRight'] = "admin";
  
  }
}

?>