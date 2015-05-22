<?php
class Portal_Model_DbTable_Row_AdminStyleServices extends Centurion_Db_Table_Row_Abstract
{
    public function __toString()
    {
      //  return $this->user->username;
    }

    public function getAvatar()
    {
        return $this->px;
    }
    
    public function getImageService() {
    	$Media = new Media_Model_DbTable_File();
    	$Img = $Media->find($this->avatar_id);
    	$imgrow = $Img->current();
    	return $imgrow; //->getStaticUrl();
    }
    
    public function getCode(){
    	return $this->code;
    }
    
    public function getColor(){
    	return $this->color;
    }
    	
    public function getId(){
    	return $this->id;
    }
}
