<?php
class Portal_Model_DbTable_Row_Organization extends Centurion_Db_Table_Row_Abstract
{
    public function __toString()
    {
       return $this->name;
    }
    	
    public function getId(){
    	return $this->id;
    }
    
    public function getPhoneCode(){
    	return $this->phonecode;
    }
}
