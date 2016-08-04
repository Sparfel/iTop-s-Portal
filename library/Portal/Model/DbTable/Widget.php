<?php
 class Portal_Model_DbTable_Widget extends Centurion_Db_Table_Abstract

{
	protected $_name = 'portal_widget';
	
	protected $_primary = array('id');
	
	protected $_rowClass = 'Portal_Model_DbTable_Row_Widget';
	
	
	public function listAll(){
		return $this->fetchAll();
		
	}
	
	public function GetWidget($id) {
		$select = $this->select ();
		//$select->from ( $this )->where('id=\''.$id.'\' AND activ = \'1\'');
		$select->from ( $this )->where('id=\''.$id.'\'');
		return $this->fetchAll ($select );
	}
	
	/*Get all activ widget for a dashboard*/
	public function GetWidgets($dashboard) {
		$select = $this->select ();
		//$select->from ( $this )->where('id=\''.$id.'\' AND activ = \'1\'');
		$select->from ( $this )->where('dashboard=\''.$dashboard.'\' AND is_active = \'1\'')->order('order ASC');
		return $this->fetchAll ($select );
	}
	
	/*public function insPref($user_id,$pref,$value)
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
	*/
	
	public function GetActiveWidget(){
		$select = $this->select ();
		$statement = $select->from ( $this )->where('is_active=\'1\'');
		$result = array();
		$i = 0;
		foreach ($statement->fetchAll() as $row){
			//Zend_Debug::dump($row->getName());
			$result[$i] = $row->getName();
			$i++;
		}
		return $result;
	}
	
}