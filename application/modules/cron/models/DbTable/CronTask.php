<?php
class Cron_Model_DbTable_CronTask extends Centurion_Db_Table_Abstract
{
    protected $_name = 'cron_task';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Cron_Model_DbTable_Row_CronTask';
    
    /*protected $_referenceMap = array(
        'user'   =>  array(
            'columns'       => 'user_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Auth_Model_DbTable_User',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
        'avatar'   =>  array(
            'columns'       => 'avatar_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::SET_NULL
        )
    );
    
    protected $_meta = array('verboseName'   => 'profile',
                             'verbosePlural' => 'profiles');
   */
    
    
    public function getactiveTask()
    {
    	$select = $this->select()->where('is_active = ?', 1);
    	return $this->fetchAll($select);;
    }
}
