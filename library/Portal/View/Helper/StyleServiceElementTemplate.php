<?php 
class Portal_View_Helper_StyleServiceElementTemplate extends Zend_View_Helper_Abstract
{
	//Helper pour rendre le code plus léger dans la vue
	// Permet de gérer les cas ou le style n'est pas défini pour l'élément de service
	

	public function StyleServiceElementTemplate($Tabstyle, $ServiceElementTemplateId)
	{
		if (isset($Tabstyle[$ServiceElementTemplateId]))
		{
			return $Tabstyle[$ServiceElementTemplateId];
		}
		else {
			return 'undefined';
		}
			
	}
	
	
}