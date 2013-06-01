<?php

class newsRegistry extends baseRegistry {
  
  protected $itemClass = "news";
  
  protected $allowDetail = true;
  
  protected $selectList = "
    news.*,
    news.Language As ThisLanguage,
    user.* ";
  
  protected $tableList = "news LEFT JOIN user ON user.UserID=news.UserID";
  
  protected $extraList = "news.Deleted='no' AND news.Type='normal'";
  
  protected $selectCountField = "news.NewsID";
  
  protected $defaultSorting = "news.CreateTime";
  
  protected $defaultSortingDirection = 'DESC';
  
  protected $registryIdField = "NewsID";
  
  protected $registryTitle = "Rejestr newsów";

  protected function prepare() {

    $this->searchTable ['news.Title'] = "Nazwa";
    
    $this->useSearchSelects ['dateSelect'] = false;
    
    $this->tableColumns ['#'] = "Lp.";
    $this->tableColumns ['CreateTime'] = "Data";
    $this->tableColumns ['Title'] = "Tytuł";
    $this->tableColumns ['__operations__'] = "&nbsp;";
    
    $this->rightsSet ['moduleName'] = "News";
    $this->rightsSet ['allowAdd'] = true;
    $this->rightsSet ['allowEdit'] = true;
    $this->rightsSet ['allowDelete'] = true;
    $this->rightsSet ['addRight'] = "newsman";
    $this->rightsSet ['editRight'] = "newsman";
    $this->rightsSet ['deleteRight'] = "admin";
  
  }

}
?>