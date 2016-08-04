<?php
class Portal_Model_DbTable_Alert extends Centurion_Db_Table_Abstract
{
    protected $_name = 'portal_alert';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Portal_Model_DbTable_Row_Alert';
    
    
    
    protected $_meta = array('verboseName'   => 'alert',
                             'verbosePlural' => 'alerts');
    
    protected $_dependentTables = array(
    		'alert_organizations'    =>  'Portal_Model_DbTable_AlertOrganization'
    );
    
    protected $_manyDependentTables = array(
    		'organizations'        =>  array(
    				'refTableClass'     =>  'Portal_Model_DbTable_Organization',
    				'intersectionTable' =>  'Portal_Model_DbTable_AlertOrganization',
    				'columns'   =>  array(
    						'local'         =>  'alert_id',
    						'foreign'       =>  'organization_id'
    				)
    		)
    );
    
    
    
    public function insAlert($id,$name,$text,$start_date,$end_date)
    {
    	$data = array(
    			'id' => $id,
    			'name' => $name,
    			'text' => $text,
    			'start_date' => $start_date,
    			'end_date' => $end_date
    	);
    	try {
    		$this->insert($data);
    	} catch (Exception $e) {null;
    	}
    	
    }
    
    public function getMyAlerts($org_id){
    	$select = $this->select ();
    	$statement = $select->setIntegrityCheck(false)
    						->from($this)
    						->join('portal_alert_organization',
    								'portal_alert_organization.alert_id = portal_alert.id'
    								)
    						->where('is_active=\'1\' AND portal_alert_organization.organization_id =\''.$org_id.'\'' )
    						->order(array('type asc', 'priority asc'));
    	//Zend_Debug::dump($statement);
    	return 	$statement->fetchAll();
    }
    
    
}

