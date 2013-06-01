<?php

class specialNewsRegistry extends newsRegistry {
  
  protected $extraList = "news.Deleted='no' AND news.Type!='normal'";
  
  protected $itemClass = "specialNews";

  protected function prepare() {

    $this->searchTable ['news.Title'] = "Nazwa";
    
    $this->useSearchSelects ['dateSelect'] = false;
    
    $this->tableColumns ['#'] = "Lp.";
    $this->tableColumns ['CreateTime'] = "Data";
    $this->tableColumns ['Title'] = "Tytuł";
    $this->tableColumns ['Type'] = "Typ";
    $this->tableColumns ['__operations__'] = "&nbsp;";
    
    $this->rightsSet ['moduleName'] = "News";
    $this->rightsSet ['allowAdd'] = false;
    $this->rightsSet ['allowEdit'] = true;
    $this->rightsSet ['allowDelete'] = false;
    $this->rightsSet ['addRight'] = "admin";
    $this->rightsSet ['editRight'] = "admin";
    $this->rightsSet ['deleteRight'] = "admin";
  
  }

}

?>