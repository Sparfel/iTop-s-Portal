<?php 
class Portal_View_Helper_ShowServices extends Zend_View_Helper_Abstract
{
	//Helper pour rendre le code plus léger dans la vue
	//We get the Services Styles in the database.
	
	// in the view : $this->ShowServices($this->ServicesList,$this->tab_tri)
	public function ShowServices($TabService, //List of Services defined in iTop 
								$ServicePreference, // if exists, it the order to show the service in the page, saved in database.
								$type) // type de Service : Service ou ServiceSubcategory
	{
		
		if (is_array($ServicePreference)) {
		$ServicePreference = $this->VerifServices($TabService, //List of Services defined in iTop 
								$ServicePreference); // if exists, it the order to show the service in the page, saved in database.
		}
		//Loading Style from database
		
		$DbStyle = new Portal_Model_DbTable_AdminStyleServices();
		$ListStyle = $DbStyle->listService($type);
		/*Zend_Debug::dump($ListStyle);
		echo '<hr>';
		Zend_Debug::dump($DbStyle->fetchAll());*/
		$AStyle = Array(); // Array of  Service's style
		$script = '<style>';
		
		//Class determination, depends on initial element : Service, ServiceSubcategory or ServiceSubcategoryTemplate
		// For $id, see the $spanID parameter in Controller (ControllerHelper) given to the fonction savePrefHomeServicesScript()
		// it allows to move and backup into the database the services positions in the page.
		switch ($type) {
			case 'Service' : 
				$id = 'AllServices';
				$class = 'product-list-itop';
				break;
			case 'ServiceSubcategory' :
				$id = 'AllServicesElement';
				$class = 'product-list-Elt-Srv-itop';
				break;
			case 'ServiceSubcategoryTemplate' :
				$id = 'AllServicesElementTemplate';
				$class = 'product-list-Frm-Elt-Srv-itop';
				break;
			default :
				$id = 'AllServices';
				$class = 'product-list-itop';
				break;
		}
		
		foreach($ListStyle as $style) {
			$AStyle[$style->getId()] = $style;
			$img =  $style->getImageService();
			if (is_null($img)) {
				$imglnk = './images/undefined.png';
			}
			else {
				$imglnk = $img->getStaticUrl();
			} 
			
			//Zend_Debug::dump($img);
			// First the style for each service
			$script .= '.'.$class.' .'.$style->getCode().' a:after {
													background:url(\''.$imglnk.'\') no-repeat;
													top:20px;
													right:-60px;
													filter: grayscale(100%);
												    -webkit-filter: grayscale(100%);
												    -moz-filter: grayscale(100%);
												    -ms-filter: grayscale(100%);
												    -o-filter: grayscale(100%); } '.chr(13)
						.'.'.$class.' .'.$style->getCode().' a:hover:after { 
													background:url(\''.$imglnk.'\') no-repeat; right:-60px;
													filter: grayscale(0%);
												    -webkit-filter: grayscale(0%);
												    -moz-filter: grayscale(0%);
												    -ms-filter: grayscale(0%);
												    -o-filter: grayscale(0%); } '.chr(13)
						.'ul.'.$class.' li a:hover > hgroup h3.'.$style->getCode().', 
							ul.list li h4.title.'.$style->getCode().', 
							.twocolumns .right-col .product-menu h2.title.'.$style->getCode().' { color:'.$style->getColor().'; }'.chr(13);
		
			/*
			$script .= '.product-list-itop .'.$style->getCode().' a:after {
													background:url(\''.$imglnk.'\') no-repeat;
													top:20px;
													right:-60px;
													filter: grayscale(100%);
												    -webkit-filter: grayscale(100%);
												    -moz-filter: grayscale(100%);
												    -ms-filter: grayscale(100%);
												    -o-filter: grayscale(100%); }
						.product-list-itop .'.$style->getCode().' a:hover:after {
													background:url(\''.$imglnk.'\') no-repeat; right:-60px;
													filter: grayscale(0%);
												    -webkit-filter: grayscale(0%);
												    -moz-filter: grayscale(0%);
												    -ms-filter: grayscale(0%);
												    -o-filter: grayscale(0%); }
						ul.product-list-itop li a:hover > hgroup h3.'.$style->getCode().',
											ul.list li h4.title.'.$style->getCode().',
											.twocolumns .right-col .product-menu h2.title.'.$style->getCode().' { color:'.$style->getColor().'; }';
			*/
			
		} 
		$script .= '</style>';
		
		// The list of services.
		$nb_services = count($TabService);
		if ($nb_services > 0)
			{ $i = 1;
			$script .= '<span id="'.$id.'">'.chr(13);
			if (is_array($ServicePreference)){ //Services positions are saved in database for the user, see parameter HOME_SERVICES in table portal_user_preference
				$no_col = 1;
				foreach ($ServicePreference as $tab_col) {
					$script .= '<div class="col'.$no_col.'"><ul class="'.$class.'">'.chr(13);
					foreach ($tab_col as $idservice) {
						if ($idservice != '') // Y a t-il un identifiant service ? (cas du param se terminant par ',' ou |
						{
							$service = $TabService[$idservice];
							//print_r($service);
							//echo '<li class="'.$this->StyleList[$service->getId()].'" id="'.$service->getId().'">'.chr(13);
							$script .= '<li class="'.$this->StyleServiceClass($AStyle,$service->getId()).'" id="'.$service->getId().'">'.chr(13);
							$script .= '<a href="'.$this->view->url(array('controller' => 'catalogue', 'action' => 'index', 'module' => 'home',$type=>$service->getId()),null, true).'">'.chr(13);
							$script .= '<hgroup>'.chr(13);
							$script .= '<h3 class="'.$this->StyleServiceClass($AStyle,$service->getId()).'">'.$service->getName().'<span>'.$service->getName().'</span></h3>'.chr(13);
							$script .= '</hgroup>'.chr(13);
							$script .= '<p>'.$service->getDescription().'</p>'.chr(13);
							$script .= '</a>'.chr(13).'</li>'.chr(13);
						}
					}
					$script .= '</ul>'.chr(13).'</div>'.chr(13);
					$no_col++;
				}
			}
			else { // No services position saved in preference table in database.
				//Zend_Debug::dump(count($this->ServicesList));
				foreach ($TabService as $service)
				{
					if ($i == 1) {$script .= '<div class="col1"><ul class="'.$class.'">'.chr(13);}
					$script .= '<li class="'.$this->StyleServiceClass($AStyle,$service->getId()).'" id="'.$service->getId().'">'.chr(13);
					$script .= '<a href="'.$this->view->url(array('controller' => 'catalogue', 'action' => 'index', 'module' => 'home',$type=>$service->getId()),null, true).'">'.chr(13);
					$script .= '<hgroup>'.chr(13);
					$script .= '<h3 class="'.$this->StyleServiceClass($AStyle,$service->getId()).'">'.$service->getName().'<span>'.$service->getName().'</span></h3>'.chr(13);
					$script .= '</hgroup>'.chr(13);
					$script .= '<p>'.$service->getDescription().'</p>'.chr(13);
					$script .= '</a>'.chr(13).'</li>'.chr(13);
					$i++;
					if (($i >= $nb_services/2  ) AND ($i < ($nb_services/2)+1 )) {$script .= '</ul>'.chr(13).'</div>'.chr(13).'<div class="col2">'.chr(13).'<ul class="'.$class.'">'.chr(13);}
					if ($i > $nb_services) { $script .= '</ul>'.chr(13).'</div>'.chr(13);}
				}
			}
			$script .= '</span>'.chr(13);
			}
			
			return $script;
	}
		
		
	private function StyleServiceClass($AStyle, $ServiceId)
	{
		/* If we don'nt have an Image, the style is incomplete => style will be undefined*/
		if (isset($AStyle[$ServiceId]) AND !(is_null($AStyle[$ServiceId]->getImageService())))
		{
			return $AStyle[$ServiceId]->getCode();
		}
		else {
			return 'undefined';
		}
			
	}
	
	private function StyleServiceColor($AStyle, $ServiceId)
	{
		if (isset($AStyle[$ServiceId]))
		{
			return $AStyle[$ServiceId]->getColor();
		}
		else {
			return 'undefined';
		}
			
	}
	
	private function StyleServiceImg($AStyle, $ServiceId)
	{
		if (isset($AStyle[$ServiceId]))
		{
			return $AStyle[$ServiceId]->getImageService();
		}
		else {
			return 'undefined';
		}
			
	}
	
	
	/*
	 * Compare the saved Array of Service's position with the Services 
	 * if a new Service exists and is not saved in preference Array, we 
	 * add him to the top of the list
	 */
	private function VerifServices($TabService, //List of Services defined in iTop 
								$ServicePreference) // if exists, it the order to show the service in the page, saved in database.
	{
		//Keys of $TabService are the Services Id (or Services Subcategory's Id)
		foreach ($TabService as $key => $values) {
			if (!(in_array( $key,$ServicePreference[0]) OR in_array( $key, $ServicePreference[1]))){
				array_unshift($ServicePreference[0], strval($key));
				echo 'on ajoute le service !!';
			}
		}
 		//Zend_Debug::dump($ServicePreference);
		return $ServicePreference;
	}
	
	
}
	
	
