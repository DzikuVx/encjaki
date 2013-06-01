<?php

class commentRegistry extends baseRegistry {
  
  protected $itemClass = "comment";
  
  protected $allowDetail = true;
  
  protected $selectList = "
    comment.*,
    user.* ";
  
  protected $tableList = "comment LEFT JOIN user ON user.UserID=comment.UserID";
  
  protected $extraList = "comment.Deleted='no'";
  
  protected $selectCountField = "comment.CommentID";
  
  protected $defaultSorting = "comment.CreateTime";
  
  protected $defaultSortingDirection = 'DESC';
  
  protected $registryIdField = "CommentID";
  
  protected $registryTitle = "Rejestr komentarzy";

  protected function renderOperationsColumn($ID, $tResult = null) {
    
    $retVal = '';
    
    if ($tResult->Accepted == 'no' && $this->user->checkRole ( $this->rightsSet ['addRight'] )) {
      $retVal .= controls::renderImgButton ( "add", "document.location='?action=execute&amp;module={$this->itemClass}&amp;method=accept&amp;id={$ID}'", "Akceptuj" );
    }
    
    $retVal .= parent::renderOperationsColumn($ID, $tResult);
    
    return $retVal;
  }
  
  protected function prepare() {

    $this->searchTable ['comment.Text'] = "Treść";
    
    $this->useSearchSelects ['dateSelect'] = false;
    
    $this->tableColumns ['#'] = "Lp.";
    $this->tableColumns ['CreateTime'] = "Data";
    $this->tableColumns ['Text'] = "Treść";
    $this->tableColumns ['Language'] = "Język";
    $this->tableColumns ['__operations__'] = "&nbsp;";
    
    $this->rightsSet ['moduleName'] = "News";
    $this->rightsSet ['allowAdd'] = true;
    $this->rightsSet ['allowEdit'] = false;
    $this->rightsSet ['allowDelete'] = true;
    $this->rightsSet ['addRight'] = "admin";
    $this->rightsSet ['editRight'] = "admin";
    $this->rightsSet ['deleteRight'] = "admin";
  
  }

}

?>