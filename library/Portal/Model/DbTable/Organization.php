<?php
class Portal_Model_DbTable_Organization extends Centurion_Db_Table_Abstract
{
    protected $_name = 'portal_organization';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Portal_Model_DbTable_Row_Organization';
    
    protected $_meta = array('verboseName'   => 'organization',
                             'verbosePlural' => 'organizations');
    
    protected $_dependentTables = array(
    		'alert_organizations'    =>  'Portal_Model_DbTable_AlertOrganization'
    		);
    
    protected $_manyDependentTables = array(
    		'alerts'        =>  array(
    				'refTableClass'     =>  'Portal_Model_DbTable_Alert',
    				'intersectionTable' =>  'Portal_Model_DbTable_AlertOrganization',
    				'columns'   =>  array(
    						'local'         =>  'organization_id',
    						'foreign'       =>  'alert_id'
    				)
    		)
    );
    
    
    
    public function insOrganization($id,$name)
    {
    	$data = array(
    			'id' => $id,
    			'name' => $name,
    			'created_at' => new Zend_Db_Expr('CURRENT_TIMESTAMP')
    	);
    	try {
    		$this->insert($data);
    	} catch (Exception $e) {null;
    	}
    	
    }
    
    
}

