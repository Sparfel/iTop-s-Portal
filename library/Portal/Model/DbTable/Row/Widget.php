<?php
class Portal_Model_DbTable_Row_Widget extends Centurion_Db_Table_Row_Abstract
{
   
  
	public function getName(){
		return $this->name;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getSource(){
		return $this->source;
	}
    
}
