<?php
class Cron_Model_DbTable_Row_CronTask extends Centurion_Db_Table_Row_Abstract
{
    public function __toString()
    {
        return $this->name;
    }

    public function setExecution(){
    	$this->last_execution = new Zend_Db_Expr('CURRENT_TIMESTAMP');
    }
 
}
