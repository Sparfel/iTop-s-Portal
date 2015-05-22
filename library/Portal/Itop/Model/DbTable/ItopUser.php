<?php
 class Portal_Itop_Model_DbTable_ItopUser extends Centurion_Db_Table_Abstract

{
	protected $_name = 'portal_itop_user';
	
	protected $_primary = array('id');
	
	protected $_rowClass = 'Portal_Itop_Model_DbTable_Row_ItopUser';
	
	
	
	protected $_meta = array('verboseName'   => 'id',
			'verbosePlural' => 'ids');
	
	protected $_referenceMap = array(
			'group'   =>  array(
					'columns'       => 'group_id',
					'refColumns'    => 'id',
					'refTableClass' => 'Auth_Model_DbTable_Group'
					)
			);
	
/*	protected $_dependentTables = array(
			'group'        =>  array(
		            'refTableClass'     =>  'Auth_Model_DbTable_Group', 
		            'columns'   =>  array(
		                'local'         =>  'user_id',
		                'foreign'       =>  'group_id'
		            )
		        )
			
			);
*/	
	

	
	public function insUser($login,$first_name,$last_name,$email,$group_id,$is_local,$org_id,$org_name)
	{
		$data = array(
				'login' => $login,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'group_id' => $group_id,
				'is_local' => $is_local,
				'org_id' => $org_id,
				'org_name' => $org_name,
				'created_at' => new Zend_Db_Expr('CURRENT_TIMESTAMP')
		);
		$this->insert($data);
	}
	
	public function truncate(){
		$db = $this->getAdapter();
		$db->query('truncate Table '.$this->_name);
	}
	
	
	
}