<?php

class linkRegistry extends baseRegistry {
  protected $itemClass = "link";
  
  protected $allowDetail = false;
  
  protected $selectList = "
    link.* ";
  
  protected $tableList = "link";
  protected $extraList = "link.Deleted='no'";
  protected $selectCountField = "link.LinkID";
  
  protected $defaultSorting = "link.Name";
  
  protected $defaultSortingDirection = 'ASC';
  
  protected $registryIdField = "LinkID";
  
  protected $registryTitle = "Rejestr linków";

  protected function prepare() {

    $this->searchTable ['link.Name'] = "Nazwa";
    $this->searchTable ['link.Link'] = "Adres";
    
    $this->useSearchSelects ['dateSelect'] = false;
    
    $this->tableColumns ['#'] = "Lp.";
    $this->tableColumns ['Name'] = "Nazwa";
    $this->tableColumns ['Link'] = "Adres";
    $this->tableColumns ['Language'] = "Język";
    $this->tableColumns ['__operations__'] = "&nbsp;";
    
    $this->rightsSet ['moduleName'] = "Link";
    $this->rightsSet ['allowAdd'] = true;
    $this->rightsSet ['allowEdit'] = true;
    $this->rightsSet ['allowDelete'] = true;
    $this->rightsSet ['addRight'] = "admin";
    $this->rightsSet ['editRight'] = "admin";
    $this->rightsSet ['deleteRight'] = "admin";
  
  }
}

?>