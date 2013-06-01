<?php

class imageRegistry extends baseRegistry {
  
  protected $itemClass = "image";
  
  protected $allowDetail = false;
  
  protected $selectList = "attachments.*";
  
  protected $tableList = "attachments";
  
  protected $extraList = "";
  
  protected $selectCountField = "attachments.AttachmentID";
  
  protected $defaultSorting = "attachments.FileName";
  
  protected $defaultSortingDirection = 'ASC';
  
  protected $registryIdField = "AttachmentID";
  
  protected $registryTitle = "";

  protected function prepare() {

    $this->extraList = "attachments.Deleted='no' AND attachments.ProductID='{$this->params['id']}'";
    
    $this->useSearchSelects ['dateSelect'] = false;
    
    $this->tableColumns ['#'] = "Lp.";
    $this->tableColumns ['ProductImage'] = "Zdjęcie";
    $this->tableColumns ['__operations__'] = "&nbsp;";
    
    $this->rightsSet ['moduleName'] = "Product";
    $this->rightsSet ['allowAdd'] = false;
    $this->rightsSet ['allowEdit'] = false;
    $this->rightsSet ['allowDelete'] = true;
    $this->rightsSet ['addRight'] = "admin";
    $this->rightsSet ['editRight'] = "admin";
    $this->rightsSet ['deleteRight'] = "admin";
  
  }

}

?>