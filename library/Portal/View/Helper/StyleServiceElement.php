<?php 
class Portal_View_Helper_StyleServiceElement extends Zend_View_Helper_Abstract
{
	//Helper pour rendre le code plus léger dans la vue
	// Permet de gérer les cas ou le style n'est pas défini pour l'élément de service
	

	public function StyleServiceElement($Tabstyle, $ServiceElementId)
	{
		if (isset($Tabstyle[$ServiceElementId]))
		{
			return $Tabstyle[$ServiceElementId];
		}
		else {
			return 'undefined';
		}
			
	}
	
	
}