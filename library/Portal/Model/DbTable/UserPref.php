<?php
 class Portal_Model_DbTable_UserPref extends Centurion_Db_Table_Abstract

{
	protected $_name = 'portal_user_preference';
	
	protected $_primary = array('user_id','preference_name');
	
	protected $_rowClass = 'Portal_Model_DbTable_Row_UserPref';
	
	
	public function savePref($user_id,$pref,$value){
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
			$tab_col = explode('|',$str_pref);
			$i = 0;
			foreach ($tab_col as $col) {
				$tab_display[$i] = explode(',',$col);
				$i++;
			}
			//Zend_Debug::dump($tab_display);
			if (count($tab_col)==1 AND count($tab_display)==1) {return $str_pref;}
			else {return $tab_display;}
		}
		else return null;
		
	}
	
	
}