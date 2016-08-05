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
					),
			'organization'   =>  array(
					'columns'       => 'org_id',
					'refColumns'    => 'id',
					'refTableClass' => 'Portal_Model_DbTable_Organization'
			)
			
			);
	
/*	protected $_dependentTables = array(
			'group'        =>  array(
		            'refTableClass'     =>  'Auth_Model_DbTable_Group', 
		            'columns'   =>  array(
		                'local'         =>  'group_id',
		                'foreign'       =>  'group_id'
		            )
		        )
			
			);

*/

	
	public function insUser($login,$first_name,$last_name,$email,$group_id,$is_local,$org_id,$org_name,$itop_id)
	{
		//Fisrt, we insert the Organization if not exists
		$this->insOrg($org_id,$org_name);
		$data = array(
				'itop_id'=> $itop_id,
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
	
	protected function insOrg($org_id,$org_name){
		$Organization = new Portal_Model_DbTable_Organization();
		$Organization->insOrganization($org_id,$org_name);
	}
	
	public function truncate(){
		$db = $this->getAdapter();
		$db->query('truncate Table '.$this->_name);
	}
	
	
	
}