<?php
class Portal_Model_DbTable_Row_Alert extends Centurion_Db_Table_Row_Abstract
{
    public function __toString()
    {
       return $this->name;
    }
    	
    public function getId(){
    	return $this->id;
    }
    
    public function getStart(){
    	return $this->start_date;
    }
    
    public function getEnd(){
    	return $this->end_date;
    }
    
}
