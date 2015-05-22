<?php 
class Portal_View_Helper_StyleService extends Zend_View_Helper_Abstract
{
	//Helper pour rendre le code plus léger dans la vue
	// Permet de gérer les cas ou le style n'est pas défini pour le service
	

	public function StyleService($Tabstyle, $ServiceId)
	{
		if (isset($Tabstyle[$ServiceId]))
		{
			return $Tabstyle[$ServiceId];
			//return $this->undefined();
		}
		else {
			//return 'undefined';
			return $this->undefined();
		}
			
	}
	
	
	private function undefined(){
		return 'undefined';
	} 
	
	
}