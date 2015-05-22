<?php
 class Portal_Ldap_Model_DbTable_LdapUser extends Centurion_Db_Table_Abstract

{
	protected $_name = 'portal_ldap_user';
	
	protected $_primary = array('id');
	
	protected $_rowClass = 'Portal_Ldap_Model_DbTable_Row_LdapUser';
	
	
	
	protected $_meta = array('verboseName'   => 'user',
			'verbosePlural' => 'users');
	
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
	

	
	public function insUser($sn,$first_name,$last_name,$email,$email2,$group_id,$is_local)
	{
		$data = array(
				'sn' => $sn,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'email2' => $email2,
				'group_id' => $group_id,
				'is_local' => $is_local
		//'created_at' => new Zend_Db_Expr('CURRENT_TIMESTAMP')
		);
		$this->insert($data);
	}
	
	public function truncate(){
		$db = $this->getAdapter();
		$db->query('truncate Table '.$this->_name);
	}
	
	
	/*public function savePref($user_id,$pref,$value){
		$row = $this->find($user_id,$pref);
		if ($row->count() > 0)
			{
				$this->updPref($user_id,$pref,$value);
			}
		else {
				$this->insPref($user_id,$pref,$value);
			}
	}
	
	
	public function insPref($user_id,$pref,$value)
	{
		$data = array(
					'user_id' => $user_id,
					'preference_name' => $pref,
					'preference_value'=>$value,
					'created_at' => new Zend_Db_Expr('CURRENT_TIMESTAMP')				
				);
		$this->insert($data);
	}
	
	
	public function updPref($user_id,$pref,$value)
	{
		$data = array('preference_value'=>$value,
					'updated_at' => new Zend_Db_Expr('CURRENT_TIMESTAMP'));
		//$row = $this->getTask($script);
		$where = 'user_id = "'.$user_id.'" AND preference_name = "'.$pref.'"';
		$this->update($data , $where );
	}
	
	public function getPref($user_id,$pref) {
		$select = $this->select ();
		$select->from ( $this )->where('user_id=\''.$user_id.'\' AND preference_name = \''.$pref.'\'');
		$tab_result = $this->fetchAll($select)->current();
		if (count($tab_result)>0){
			$str_pref = $tab_result->getPrefValue();
			//print_r($str_pref);
			$tab_col = explode('|',$str_pref);
			$i = 0;
			foreach ($tab_col as $col) {
				$tab_display[$i] = explode(',',$col);
				$i++;
				
			}
			return $tab_display;
		}
		else return null;
		
	}
	*/
	
}