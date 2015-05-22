<?php
class Portal_Model_DbTable_Row_UserPref extends Centurion_Db_Table_Row_Abstract
{
   
  public function getPrefValue() {
  	return $this->preference_value;
  }
   
    
}
