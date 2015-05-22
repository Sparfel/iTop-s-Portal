<?php
class Portal_Model_DbTable_AdminStyleServices extends Centurion_Db_Table_Abstract
{
    protected $_name = 'portal_service_style';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Portal_Model_DbTable_Row_AdminStyleServices';
    
    protected $_referenceMap = array(
        'avatar'   =>  array(
            'columns'       => 'avatar_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::SET_NULL
        )
    );
    
    protected $_meta = array('verboseName'   => 'style',
                             'verbosePlural' => 'styles');
    
    
    
    public function insService($id,$name,$description,$code,$type,$parent_id,$parent_name)
    {
    	$data = array(
    			'id' => $id,
    			'name' => $name,
    			'description' => $description,
    			'code' => $code,
    			'type' => $type,
    			'parent_id' => $parent_id,
    			'parent_name' => $parent_name,
    			'created_at' => new Zend_Db_Expr('CURRENT_TIMESTAMP')
    	);
    	$this->insert($data);
    }
    
    public function listService($type) {
    	$select = $this->select ();
    	$select->from ( $this )->where('type = \''.$type.'\'');
    	return $this->fetchAll($select);
    }
}

