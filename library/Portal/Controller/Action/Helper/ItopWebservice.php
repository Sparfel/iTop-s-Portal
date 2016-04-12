<?php
/*
 * Created on 15 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Portal_Controller_Action_Helper_ItopWebservice extends Zend_Controller_Action_Helper_Abstract 
{

	protected $_protocole;
	protected $_adresse;
	protected $_username;
    protected $_password;
	protected $_url;
	protected $bDebug;
	protected $_name;
	protected $_first_name;
	
	
	public function __construct()
    {
    	
    	
        //on récupère les paramètre d'url pour le webservice d'iTop
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
		
		$identity = Centurion_Auth::getInstance()->getIdentity();
		if ($identity->is_staff == '1') // Si personnel Syleps activé alors on bascule sur iTop de production
		{
			$this->_protocol = $config->itop1->url->protocol;
			$this->_adress = $config->itop1->url->adress;
			$this->_username = $config->itop1->webservice->user;
			$this->_password = $config->itop1->webservice->pwd;
		} 
		else 
		{
			$this->_protocol = $config->itop2->url->protocol;
			$this->_adress = $config->itop2->url->adress;
			$this->_username = $config->itop2->webservice->user;
	        $this->_password = $config->itop2->webservice->pwd;
		}
        
        $this->_url = $this->_protocol.'://'.$this->_adress.'/webservices/rest.php?version=1.0';
		$this->bDebug = $config->phpSettings->display_errors;
		
		$session = new Zend_Session_Namespace('Zend_Auth');
		if (isset( $session->pref->_user_id)) { // Si on a un ID iTop, on mémorise ces infos issue de iTop 
			$this->_name =$session->pref->_user_name;
			$this->_first_name = $session->pref->_user_first_name;
			$this->_org_id = $session->pref->_org_id;
			$this->_user_id = $session->pref->_user_id;
		}
		
		
	}

	protected function CallWebService($aData)
	{
		$aPostData = array(
			'auth_user' => $this->_username,
			'auth_pwd' => $this->_password,
			'json_data' => json_encode($aData),
		);
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData));
	
	    curl_setopt($curl, CURLOPT_URL, $this->_url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    
	    
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    
	    if ($this->bDebug)
	    {
	    	curl_setopt($curl, CURLOPT_VERBOSE, true);
	    }
	    
	    $sResult = curl_exec($curl);
	    $aResult = @json_decode($sResult, true /* bAssoc */);
		if ($aResult == null)
		{
			$aResult = null;
			if ($this->bDebug)
			{
				echo "Error: the return value from the web service could not be decoded:\n$sResult\n===================\n.";
			}
		}
		return $aResult;
		
	}
	
	public function listOperations()
	{
		$aData = array(
			'operation' => 'list_operations'
			/*'activity_id' => $iActivityId,
			'org_id' => $iOrgId,
			'activity_label' => $sActivityLabel,
			'ticket_id' => $iTicketId,*/
		);
	
		/*$aData = array(
   				'operation'=> 'core/get',
   				'class'=> 'Person',
   				'key'=> 1,
   				'output_fields' => '*',
				);
			*/
		return $this->CallWebService($aData);
	}
	
	// Création d'un ticket basique
	public function CreateRequest($title,$description, $type)
	{
		if (!(isset($type))) { $type = 'service_request';}
		$aData = array(
				'operation'=>'core/create',
   				'comment'=>'Création via Portail Syleps par '.$this->_first_name.' '.$this->_name,
   				'class'=>'UserRequest',
   				'output_fields'=>'id, friendlyname',
   				'fields'=>array(
      						'org_id'=>$this->_org_id, //'SELECT Organization WHERE name = "SYLEPS"',
						    'caller_id'=>array(
						    			'name'=>$this->_name,
         								'first_name'=>$this->_first_name,
         								),					    
						    
      		    			'title'=>$title,
      						'description'=>$description,
   							'origin'=>'portal',
   							'request_type'=>$type
      						),
      			);     
      	//echo json_encode($aData); 
    	return $this->CallWebService( $aData); 
	}
	
	// Création d'une demande de service
	public function CreateServiceRequest($title,$description,$service,$serviceElement)
	{
		$aData = array(
				'operation'=>'core/create',
				'comment'=>'Création via Portail Syleps par '.$this->_first_name.' '.$this->_name,
				'class'=>'UserRequest',
				'output_fields'=>'id, friendlyname',
				'fields'=>array(
						'org_id'=>$this->_org_id, //'SELECT Organization WHERE name = "SYLEPS"',
						'caller_id'=>array(
								'name'=>$this->_name,
								'first_name'=>$this->_first_name,
						),
		
						'title'=>$title,
						'description'=>$description,
						'request_type'=>'service_request',
						'service_id'=>$service->_id,
						'servicesubcategory_id'=>$serviceElement->_id,
						'origin'=>'portal'
				),
		);
		//echo json_encode($aData);
		//Zend_Debug::dump($aData);
		$result = $this->CallWebService( $aData);
		//Zend_Debug::dump($result);
		return $result;
	}
	
	
	public function UpdateRequest($id,$Opref,$newentry)
	{
		date_default_timezone_set('Europe/Paris');
		$date = new Zend_Date();
		$aData = array(
				'operation'=>'core/update',
				'comment'=>'Modification du ticket via le portail par '.$Opref->_user_first_name.' '.$Opref->_user_name,
				'class'=>'UserRequest',
				'key' => $id,
				'output_fields'=>'id, ref,friendlyname',
				'fields'=>array(
						'public_log' => array(
										'add_item' => array ('date'=> $date->get('YYYY-MM-dd HH:mm:ss'),
																'user_login' => $Opref->_user_login_iTop_account,
																'user_id' => $Opref->_user_id_iTop_account, // Attention ! Id of Itop User and NOT Id of Person
																'message'=>$newentry
															)
										)
						),
		);
		//Zend_Debug::dump($aData);
		$result = $this->CallWebService( $aData);
		//Zend_Debug::dump($result);
		return $result;
	}
	
	public function AddAttachment($name,$data,$item_class,$item_id,$type,$org_id)
	{
		$aData = array(
				'operation'=>'core/create',
				'comment'=>'Ajout de pièce jointe',
				'class'=>'Attachment',
				'output_fields'=>'expire',
				'fields'=>array(
						'item_org_id'=>$org_id,
						'item_class'=>$item_class,
						'item_id'=>$item_id,
						'contents'=>array('data'=>$data,
											'mimetype'=>$type,
											'filename'=>$name),
				),
		);
		//echo json_encode($aData);
		return $this->CallWebService( $aData);
	}
	
	/*Ajout des ExtraData, données complémentaire au ticket, envoyer via les RequestTemplate
	* $tab_data est un tableau du style
	* array(code_du champ => sa_valeur,
	* 		code_du champ => sa_valeur)
	*/
	public function AddExtraData($template_id,$request_id,$tab_data)
	{
		$aData = array(
				'operation'=>'core/create',
   				'comment'=>'Création via Portail Syleps',
   				'class'=>'TemplateExtraData',
   				'output_fields'=>'template_id, friendlyname',
   				'fields'=>array(
      						'template_id'=>$template_id,
      		    			'obj_class'=>'UserRequest',
							'obj_key'=>$request_id,
      						'data'=> serialize($tab_data)							
      						),
		
		);
		$result =  $this->CallWebService($aData);
		return $result;
	}
	
	
	
		
	public function getTicket($id,$org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE ref = "'.$id.'"AND org_id = "'.$org_id.'"', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
				'output_fields' => 'id,ref,title,description,start_date,finalclass,status,priority,org_name,resolution_code,site_name,
									resolution_date,request_type,service_name,caller_id_friendlyname,agent_id_friendlyname,last_update,public_log'
				/* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
				);
		return $this->CallWebService( $aData);
	}
	
	public function getInfoTicket($id,$org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE id = "'.$id.'" AND org_id = "'.$org_id.'"', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
				'output_fields' => 'id,ref,title,description,start_date,finalclass,status,priority,org_name,resolution_code,solution,
									resolution_date,request_type,service_name,caller_id_friendlyname,agent_id_friendlyname,last_update,public_log,pending_reason'
				/* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
		);
		//Zend_Debug::dump($aData);
		$results = $this->CallWebService($aData);
		//Zend_Debug::dump($results);
		// Un seul ticket à chaque fois, donc pas besoin de retorner un tableau à plusieurs dimensions.
		if (count($results['objects'])>0)
			{foreach ($results['objects'] as $result) {
				$tab_result = $result['fields'];
				}
			}
		else $tab_result = array();
		//Zend_Debug::dump($tab_result);
		return $tab_result;
	}
	
	// Get the Id with the Ref, usefull to make link to the portal into the iTop's notifications.
	public function getTicketId($ref,$org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE ref = "'.$ref.'" AND org_id = "'.$org_id.'"', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
				'output_fields' => 'id'
		);
		//Zend_Debug::dump($aData);
		$results = $this->CallWebService($aData);
		//Zend_Debug::dump($results);
		// Un seul ticket à chaque fois, donc pas besoin de retorner un tableau à plusieurs dimensions.
		if (count($results['objects'])>0)
		{foreach ($results['objects'] as $result) {
			$tab_result = $result['fields'];
		}
		}
		else $tab_result = array();
		//Zend_Debug::dump($tab_result);
		return $tab_result;
	}
	
	
	// Récupération des pièces jointes d'un ticket
	// Attention ici $id est l'ID et non Ref, 1234 au lieu de R-001234
	public function getAttachment($id,$org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Attachment',
				'key' => 'SELECT Attachment WHERE item_id !="" AND 
							item_id = "'.$id.'" 
							AND item_class = "UserRequest"
							AND item_org_id = "'.$org_id.'"', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
				'output_fields' => 'id,temp_id,expire,item_class,item_id,item_org_id,contents,friendlyname'
				/* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
		);
		$results = $this->CallWebService($aData);
		//Zend_Debug::dump($aData);
		$i = 0;
		if (count($results['objects'])>0)
			{foreach ($results['objects'] as $result) {
				$tab_result[$i] = $result['fields'];
				$i++;
				}
			}
		else $tab_result = array();
		//Zend_Debug::dump($tab_result);
		return $tab_result;
		
	}
	
	// Récupération de la pièce jointe à partir de son ID unique 
	// Renvoi un Objet de type Portal_Request_Attachment

	public function getAttachmentPerId($id,$org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Attachment',
				'key' => 'SELECT Attachment WHERE id = "'.$id.'"
							AND item_class = "UserRequest"
							AND item_org_id = "'.$org_id.'"', /*pour éviter au petits malins de modifier l\'url et de voir les ticket des voisins*/
				'output_fields' => 'id,temp_id,expire,item_class,item_id,item_org_id,contents,friendlyname'
				/* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
		);
		$results = $this->CallWebService($aData);
		//Zend_Debug::dump($aData);
		//Zend_Debug::dump($results);
		$i = 0;
		if (count($results['objects'])>0)
			{foreach ($results['objects'] as $result) {
				$Oattach = new Portal_Request_Attachment($result['fields']['id'], 
														$result['fields']['temp_id'], 
														$result['fields']['item_class'],
														$result['fields']['item_id'], 
														$result['fields']['item_org_id'], 
														$result['fields']['friendlyname'], 
														$result['fields']['contents']['mimetype'], 
														$result['fields']['contents']['filename'],
														$result['fields']['contents']['data']);
				$i++;
			}
		}
		else $Oattach = null;
		//Zend_Debug::dump($Oattach);
		return $Oattach;
	
	}
	
	
	
	
	public function getInfoContact($email){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Person',
				'key' => 'SELECT Person WHERE email = "'.$email.'"',
				'output_fields' => 'id,name,first_name,phone,org_id,org_name,employee_number'
				
		);
		return $this->CallWebService( $aData);
	}
	
	public function getInfoContactFull($email,$first_name,$last_name){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Person',
				'key' => 'SELECT Person WHERE email = "'.$email.'" AND first_name ="'.$first_name.'" AND name ="'.$last_name.'"',
				'output_fields' => 'id,name,first_name,phone,org_id,org_name,employee_number'
	
		);
		//Zend_Debug::dump($aData);
		//Zend_Debug::dump($this->CallWebService( $aData));
		return $this->CallWebService( $aData);
	}
	
	public function getInfoUser($contactid){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserLocal',
				'key' => 'SELECT User WHERE contactid = "'.$contactid.'"',
				'output_fields' => 'id,profile_list,allowed_org_list,login,password'
	
		);
		return $this->CallWebService( $aData);
	}
	
	public function getLocation($org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Location',
				'key' => 'SELECT Location WHERE org_id = "'.$org_id.'"', 
				'output_fields' => 'name'
				/* itop syleps -->'output_fields' => 'public_log,private_log,description'*/
		);
		$results = $this->CallWebService( $aData);
		$i = 1;//on conserve le 0 pour la valeur 'All'
		$tab_location_list = array();
		foreach ($results['objects'] as $result) {
			$tab_location_list[$i] = $result['fields']['name'];
			$i++;
		}
		return $tab_location_list;
	}
	
	
	public function getListOpenedRequest($org_id,$OPref){
		//$filter est un tableau contenant les filtres pour la clause Wher
		$where = '';
		if ($OPref->_userFilter == 'true') 
			{// on filtre sur le caller_id
				$where .= ' AND caller_id = "'.$this->_user_id.'"';
			}
		//Zend_Debug::dump($OPref->_AyearFilter);
		if (is_array($OPref->_AyearFilter) and count($OPref->_AyearFilter)>0) 
			{
				$where .= ' AND (';
				$i = 0;
				foreach ($OPref->_AyearFilter as $year)
				{	
					if (!($year=='All')) 
						{$where .= '(start_date >= "'.$year.'-01-01" AND start_date <= "'.$year.'-12-31")';
						if ($i < count($OPref->_AyearFilter) -1 ) { $where .= " OR ";}
						}
					else {
							$where .=true;
							break;
						} // on sort direct car All doit ramener toutes les années
					$i++;	
					
				}
				$where .= ')';
			}  
		//Zend_Debug::dump($OPref->_AlocationFilter);
		// No location in request with iTop original version
		/*if (is_array($OPref->_AlocationFilter) and count($OPref->_AlocationFilter)>0)
			{
				$where .= ' AND (';
				$i = 0;
				foreach ($OPref->_AlocationFilter as $location)
				{	
					if (!($location=='All')) 
						{$where .= '(site_name = "'.$location.'")';
						if ($i < count($OPref->_AlocationFilter) -1 ) { $where .= " OR ";}
						// Si pas de site défini, on liste tout de même le ticket
						else  { $where .= " OR (site_id = '')";}
						}
					else {
						$where .=true;
						break;
						} // on sort direct car All doit ramener tous les sites
					//Zend_Debug::dump($i .' et '.count($filter->locationFilter));
					$i++;
				}
				$where .= ')';
				
			}*/
		
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE org_id = "'.$org_id.'" '.$where.' 
							AND (status = \'new\' OR status = \'assigned\' OR status = \'qualified\' OR status = \'pending\' OR status = \'resolved\')',
				'output_fields' => 'id,ref,title,description,start_date,finalclass,status,priority,
									resolution_date,request_type,service_name,caller_id_friendlyname,agent_id_friendlyname,last_update'
		);
		//Zend_Debug::dump($aData);
		$results = $this->CallWebService($aData);
		//Zend_Debug::dump($results);
		$i = 0;
		if (count($results['objects'])>0)
			{foreach ($results['objects'] as $result) {
				$tab_result[$i] = $result['fields'];
				$i++;
				}
		}
		else $tab_result = array();
		//echo translate('Title');
		//Zend_Debug::dump($tab_result);
		return $tab_result;
	}

	public function getListClosedRequest($org_id,$OPref,$AsearchCriteria){
		//$filter est un tableau contenant les filtres pour la clause Where
		//Zend_Debug::dump($Opref);
		$where = '';
		if ($OPref->_userFilter == 'true')
		{// on filtre sur le caller_id
		$where .= ' AND caller_id = "'.$this->_user_id.'"';
		}
		//Zend_Debug::dump($OPref->_AyearFilter);
		if (is_array($OPref->_AyearFilter) and count($OPref->_AyearFilter)>0)
		{
			$where .= ' AND (';
			$i = 0;
			foreach ($OPref->_AyearFilter as $year)
			{
				if (!($year=='All'))
				{$where .= '(start_date >= "'.$year.'-01-01" AND start_date <= "'.$year.'-12-31")';
				if ($i < count($OPref->_AyearFilter) -1 ) { $where .= " OR ";}
				}
				else {
					$where .=true;
					break;
				} // on sort direct car All doit ramener toutes les années
				$i++;
					
			}
			$where .= ')';
		}
		//Zend_Debug::dump($OPref->_AlocationFilter);
		if (is_array($OPref->_AlocationFilter) and count($OPref->_AlocationFilter)>0)
		{
			$where .= ' AND (';
			$i = 0;
			foreach ($OPref->_AlocationFilter as $location)
			{
				if (!($location=='All'))
					{$where .= '(site_name = "'.$location.'")';
					if ($i < count($OPref->_AlocationFilter) -1 ) { $where .= " OR ";}
					// Si pas de site défini, on liste tout de même le ticket
					else  { $where .= " OR (site_id = '')";}
				}
				else {
					$where .=true;
					break;
				} // on sort direct car All doit ramener tous les sites
				//Zend_Debug::dump($i .' et '.count($filter->locationFilter));
				$i++;
			}
			$where .= ')';
		
		}
		
		//Gestion des critères de recherches
		if (is_array($AsearchCriteria) and count($AsearchCriteria)>0)
		{
			$i = 0;
			foreach ($AsearchCriteria as $key=>$value)
			{
				if (strlen($value)>0){ // une valeur existe, on la teste :
					if ($i == 0) {
						$where .= ' AND (';
						$where .= '('. $key .' LIKE "%'.$value.'%") ';
					}
					else {
						$where .= ' OR ('. $key .' LIKE "%'.$value.'%") ';
					}
					$i++;
				}
			}
			if ($i > 0) { $where .= ')';}
			//Zend_Debug::dump($where);
		}
		
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE org_id = "'.$org_id.'" '.$where.'
							 AND (status = \'closed\')',
				'output_fields' => 'id,ref,title,description,start_date,finalclass,status,priority,
									resolution_date,request_type,service_name,caller_id_friendlyname,agent_id_friendlyname,last_update'
	
		);
		$results = $this->CallWebService($aData);
		
		//Zend_Debug::dump($results);
		$i = 0;
		if (count($results['objects'])>0)
		{foreach ($results['objects'] as $result) {
			$tab_result[$i] = $result['fields'];
			$i++;
		}
		}
		else $tab_result = array();
		return $tab_result;
	}
	
	public function getListServiceContract($org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'ServiceContract',
				'key' => 'SELECT SC FROM ServiceContract AS SC WHERE SC.org_id = "'.$org_id.'"',
				'output_fields' => 'name,description'
	
		);
		return $this->CallWebService( $aData);
	}
	
	public function getListServiceElement($org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'ServiceSubcategory',
				'key' => 'SELECT SS FROM
							CustomerContract AS CC
							JOIN lnkCustomerContractToService AS lnkCCTS
							ON lnkCCTS.customercontract_id = CC.id
							JOIN Service AS S
							ON lnkCCTS.service_id = S.id
							JOIN ServiceSubcategory AS SS 
							ON SS.service_id = S.id
							WHERE CC.org_id = "'.$org_id.'"
							AND SS.status = "Production"',
				'output_fields' => 'id,service_id,name,description'
	
		);
		return $this->CallWebService( $aData);
	}
	
	public function getListProviderContract($org_id){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'ProviderContract',
				'key' => 'SELECT ProviderContract
							WHERE org_id = "'.$org_id.'"
							AND status = "production"',
				'output_fields' => 'name,description,provider_name'
		);
		return $this->CallWebService( $aData);
	}
	
	// on effectue un cumul des tickets par mois écoulés sur les 12 derniers mois.
	public function getPerMonthRequest($org_id,$local,$nb_month){
		$lastyear = Zend_Date::now($local);
		$lastyear->setDay(1); // first day of the month
		$lastyear->sub($nb_month -1,Zend_Date::MONTH);
		$start_date = $lastyear->toString('Y-M-d');
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE org_id = "'.$org_id.'" AND start_date >="'.$start_date.'"',
				//'key' => 'SELECT UserRequest WHERE start_date >="'.$start_date.'"',
				'output_fields' => 'id,start_date'
	
		);
		//Zend_Debug::dump($aData);
		$results = $this->CallWebService( $aData);
		//Zend_Debug::dump($results);
		$tab_result = array();
		if (count($results['objects'])>0){
			$i = 0;
			foreach ($results['objects'] as $result) {
				$tab_result[$i] = $result['fields'];
				$i++;
			}
		}
		//Zend_Debug::dump($tab_result);
		return $tab_result;
	}
	
	// on effectue un cumul des tickets par ans écoulés.
	public function getPerYearRequest($org_id,$local){
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE org_id = "'.$org_id.'"',
				'output_fields' => 'id,start_date'
	
		);
		$tab_request = $this->CallWebService( $aData);
		$result_year = array();
		$i= 0;
		foreach ($tab_request['objects'] as $result) {
			$tab_result[$i] = $result['fields'];
			$i++;
		}
		foreach($tab_result as $result) {
			//$result est un tableau array(start_date , id)
			//if ($i>0) {echo ',';}
			//echo '[\''.$result['start_date'].'\','.$result['id'].']';
			// On met la date du ticket au format Date.
			//print_r($result);
			$date = new Zend_Date($result['start_date'], "YYYY-MM-DD HH:mm:ss", $local);
			// On éclate cette Date en tableau
			$date_array = $date->toArray();
			// On met la date au format String YYYY
			$result_year[$date_array['year']]=$date_array['year'];
			
		}
		
		return array_keys(array_count_values($result_year));
		
	}
	
	public function getPerLocationRequest($org_id){
		/* On ne peut pas faire de requete complexe via le Webservice ??
		 * 
		 $aData = array(
				'operation'=> 'core/get',
				'class' => 'Location',
				'key' => 'SELECT L,U FROM 
							Location AS L 
							JOIN Person AS P ON P.location_id = L.id
							JOIN UserRequest AS U ON U.caller_id = P.id
							WHERE P.org_id = "'.$org_id.'" ',
				'output_fields' => 'id,start_date'
		
		);
		return $this->CallWebService( $aData);
		*/
	}
	
	public function getCountRequest($org_id){
		$aData = array(
					'operation'=> 'core/get',
					'class' => 'UserRequest',
					'key' => 'SELECT UserRequest WHERE org_id = "'.$org_id.'"',
					'output_fields' => 'ref,resolution_code,service_name,caller_id_friendlyname'
					);
		return $this->CallWebService( $aData);
	}
	
	public function getCountRequestPerUser($org_id,$caller_id){
			$aData = array(
					'operation'=> 'core/get',
					'class' => 'UserRequest',
					'key' => 'SELECT UserRequest WHERE org_id = "'.$org_id.'" AND caller_id ="'.$caller_id.'"',
					'output_fields' => 'ref,resolution_code,service_name,caller_id_friendlyname'
			);
		
		return $this->CallWebService( $aData);
	}
	
	public function getUserRequest($org_id)
	{
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'UserRequest',
				'key' => 'SELECT UserRequest WHERE org_id = "'.$org_id.'"',
				'output_fields' => 'ref,resolution_code,service_name,caller_id_friendlyname,start_date'
		
		);
		return $this->CallWebService( $aData);
		
	}

	/*
	 * Requete OQL pour lister les différents services ... 
	 * SELECT CC , S, SF
		FROM Service AS S
		JOIN lnkCustomerContractToService AS LCCTS ON LCCTS.service_id = S.id 
		JOIN CustomerContract AS CC ON LCCTS.customercontract_id = CC.id
		JOIN ServiceFamily AS SF ON S.servicefamily_id = SF.id
	 */
	
	public function getInfoService($org_id)
	{
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'ServiceFamily',
				'key' => 'SELECT CC , S, SF	FROM Service AS S JOIN lnkCustomerContractToService AS LCCTS ON LCCTS.service_id = S.id	JOIN CustomerContract AS CC ON LCCTS.customercontract_id = CC.id JOIN ServiceFamily AS SF ON S.servicefamily_id = SF.id WHERE S.org_id ="'.$org_id.'"',
				'output_fields' => 'name'
	
		);
		return $this->CallWebService( $aData);
	
	}
	
	/*
	 * Cette Fonction permet d'appeler le Webservice générique qui 
	 * permet des Requête OQL avec jointure.
	 * Prend en paramètre 
	 * 1) la requête
	 * 2) un tableau listant l'objet et l'attribut que l'on souhaite ramener
	 * Array [0] => Array (object => 'Objet1',
	 * 						attribute => 'attribut1')
	 * 		 [1] => Array (object => 'Objet1',
	 * 						attribute => 'attribut2')
	 * 		 [2] => Array (object => 'Objet2',
	 * 						attribute => 'attribut1')
	 *  		... 
	 * 
	 * Retourne un tableau
	 * Array [0] => Array ('Objet1.attribut1' => valeur1,
	 * 						'Objet1.attribut2' => valeur2,
	 * 						'Objet2.attribut1' => valeur3)
	 * 		 [1] => Array ('Objet1.attribut1' => valeur4,
	 * 						'Objet1.attribut2' => valeur5,
	 * 						'Objet2.attribut1' => valeur6)
	 * ....
	 */
	
	public function callRestWebService($v_query,$tab_fields,$v_fields)
	{
		$client= new Zend_Rest_Client();
		//on rÃ©cupÃ¨re les paramètres d'url pour le webservice d'iTop
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
		$protocole = $config->itop->url->protocole;
		$adresse = $config->itop->url->adresse;
		$username = $config->itop->webservice->user;
		$password = $config->itop->webservice->pwd;
		//echo $v_query;
		//echo '<br>';
		$query = rawurlencode($v_query);
		
		$fields = '';
		
		$format = 'xml';
		$url = $protocole.'://'.$username.':'.$password.'@'.$adresse.'/webservices/export.php?expression='.$query.'&format='.$format.'&fields='.$fields.'&login_mode=basic';
		Zend_Debug::dump($url);
		try{
			$html = file_get_contents($url);
			$xml = simplexml_load_string($html);
			//print_r($xml);
			//$this->view->xml = array(); //$xml;
			//foreach ($this->xml->Row->UserRequest AS $result)
			$i = 0;
			
			while ($i < count($xml))
			{
				//$lib_city =  $xml->Row[$i]->Location->city.chr(13);
				//$tab_temp[$i]=$lib_city;
				$tab_temp[$i]=$xml->Row[$i];
				$i++;
			}
			/* Tableau tab_temp[]
			 Array ([0] => New York
			 		[1] => Lorient
			 		[2] => Lorient
			 		[3] => Lorient
			 )
			*/
			
			//$v_object = 'Location';
			//$v_attribute = 'city';
			
			//print_r($tab_temp);
			//echo '<br>';
			//print_r(array_keys($tab_temp));
			//echo '<hr>';
			//print_r($tab_fields);
			$i = 0;
			while ($i < count($tab_temp)){
				//print_r($tab_temp[$i]->$v_object->$v_attribute);
				$j=0;
				while($j < count($tab_fields)){
					//print_r($tab_temp[$i]);
					$v_object = $tab_fields[$j]['object'];
					$v_attribute = $tab_fields[$j]['attribute'];
					//echo $v_object.'.'.$v_attribute.'='.$tab_temp[$i]->$v_object->$v_attribute;
					//echo '<br>';
					$tab_result[$i][$v_object.'.'.$v_attribute]= $tab_temp[$i]->$v_object->$v_attribute;
					$j++;
				}
				
				$i++;
			}
		return ($tab_result);			
			
		
		}
		catch(Zend_Rest_Exception $e)
		{
			echo 'La requête n\'a rien ramené.';
			$this->view->resultat = false;
			$this->view->form = $form;
			echo "Message: " . $e->getMessage() . "\n";
		}
	}
	
	
	// Résolution d'un ticket
	public function resolveRequest($id,$Opref,$newentry)
	{
		if (strlen($newentry)> 0){
			date_default_timezone_set('Europe/Paris');
			$date = new Zend_Date();
			$aData = array(
					'operation'=> 'core/apply_stimulus',
					'comment' => 'Résolution par '.$Opref->_user_first_name.' '.$Opref->_user_name,
					'class' => 'UserRequest',
					'key'=> $id,
					'stimulus'=> 'ev_resolve',
					'output_fields'=> 'friendlyname, title, status, contact_list',
	   				'fields'=> array
					   (
					      'resolution_code'=> 'solved_by_customer',
					   	  'solution' => 'Résolu par le client',
					   	'public_log' => array(
					   				'add_item' => array ('date'=> $date->get('YYYY-MM-dd HH:mm:ss'),
					   						'user_login' => $Opref->_user_login_iTop_account,
					   						'user_id' => $Opref->_user_id_iTop_account, // Attention ! correspond à l'identifiant User iTop et non le contact
					   						'message'=>$newentry
					   				)
					   		)
					   )
				);
		}
		else {
			$aData = array(
					'operation'=> 'core/apply_stimulus',
					'comment' => 'Résolution par '.$Opref->_user_first_name.' '.$Opref->_user_name,
					'class' => 'UserRequest',
					'key'=> $id,
					'stimulus'=> 'ev_resolve',
					'output_fields'=> 'friendlyname, title, status, contact_list',
					'fields'=> array
					(
							'resolution_code'=> 'solved_by_customer',
							'solution' => 'Résolu par le client'
					)
			);
		}
		return $this->CallWebService( $aData);
	}
	
	// Fermeture d'un ticket
	public function closeRequest($id,$Opref,$newentry)
	{
		if (strlen($newentry)> 0){
			date_default_timezone_set('Europe/Paris');
			$date = new Zend_Date();
			$aData = array(
				'operation'=> 'core/apply_stimulus',
				'comment' => 'Fermeture par '.$Opref->_user_first_name.' '.$Opref->_user_name,
				'class' => 'UserRequest',
				'key'=> $id,
				'stimulus'=> 'ev_close',
				'output_fields'=> 'friendlyname, title, status',
				'fields'=>array(
						'public_log' => array(
								'add_item' => array ('date'=> $date->get('YYYY-MM-dd HH:mm:ss'),
										'user_login' => $Opref->_user_login_iTop_account,
										'user_id' => $Opref->_user_id_iTop_account, // Attention ! correspond à l'identifiant User iTop et non le contact
										'message'=>$newentry
								)
						)
				)
				);
		}
		else {
			$aData = array(
					'operation'=> 'core/apply_stimulus',
					'comment' => 'Fermeture par '.$Opref->_user_first_name.' '.$Opref->_user_name,
					'class' => 'UserRequest',
					'key'=> $id,
					'stimulus'=> 'ev_close',
					'output_fields'=> 'friendlyname, title, status',
					'fields'=>'status'
					);
		}
		//Zend_Debug::dump($aData);
		$result = $this->CallWebService( $aData);
		//Zend_Debug::dump($result);
		return $result;
	}
	
	// Fermeture d'un ticket
	public function reopenRequest($id,$Opref,$newentry)
	{
		if (strlen($newentry)> 0){
			date_default_timezone_set('Europe/Paris');
			$date = new Zend_Date();
			$aData = array(
					'operation'=> 'core/apply_stimulus',
					'comment' => 'Ré-ouverture par '.$Opref->_user_first_name.' '.$Opref->_user_name,
					'class' => 'UserRequest',
					'key'=> $id,
					'stimulus'=> 'ev_reopen',
					'output_fields'=> 'friendlyname, title, status, contact_list',
					'fields'=>array(
							'public_log' => array(
											'add_item' => array ('date'=> $date->get('YYYY-MM-dd HH:mm:ss'),
																	'user_login' => $Opref->_user_login_iTop_account,
																	'user_id' => $Opref->_user_id_iTop_account, // Attention ! correspond à l'identifiant User iTop et non le contact
																	'message'=>$newentry
																)
										)
							)
			);
		}
		else {
			$aData = array(
					'operation'=> 'core/apply_stimulus',
					'comment' => 'Ré-ouverture par '.$Opref->_user_first_name.' '.$Opref->_user_name,
					'class' => 'UserRequest',
					'key'=> $id,
					'stimulus'=> 'ev_reopen',
					'output_fields'=> 'friendlyname, title, status, contact_list',
					'fields'=>null
			);
		}
		return $this->CallWebService( $aData);
	}
	
	
	//OQL Requests about the Services
	
	/*
	 * Fonction utiliser dans l'écran d'accueil des services.
	*/
	//Récupération des éléments de services
	public function getServiceSubcategory($service_id) {
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'ServiceSubcategory',
				'key' => 'SELECT ServiceSubcategory WHERE service_id = "'.$service_id.'"',
				'output_fields' => 'id,name,friendlyname,description,status,request_type'
		);
		$results = $this->CallWebService( $aData);
		$tab_SrvElt_list = array();
		if (count($results['objects'])>0){
			$i = 0;
			foreach ($results['objects'] as $result) {
				$tab_SrvElt_list[$i]['id'] = $result['fields']['id'];
				$tab_SrvElt_list[$i]['name'] = $result['fields']['name'];
				$tab_SrvElt_list[$i]['friendlyname'] = $result['fields']['friendlyname'];
				$tab_SrvElt_list[$i]['description'] = $result['fields']['description'];
				$tab_SrvElt_list[$i]['status'] = $result['fields']['status'];
				$tab_SrvElt_list[$i]['request_type'] = $result['fields']['request_type'];
				$i++;
			}
		}
		//Zend_Debug::dump($tab_SrvElt_list);
		//sort($tab_SrvElt_list);
		return $tab_SrvElt_list;
	}
	
	// Contact Request
	/* List member otf Team Support*/
	public function getTeamSupport($org_id) {
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Person',
				//'key' => 'SELECT lnkTeamToContact WHERE team_name = "'.$support.'"',
				'key' => 'SELECT P  FROM
							Organization AS O
							JOIN DeliveryModel AS DM
							ON O.deliverymodel_id = DM.id
							JOIN lnkDeliveryModelToContact AS lnkDMTC
							ON lnkDMTC.deliverymodel_id = DM.id
							JOIN Team AS T
							ON lnkDMTC.contact_id = T.id
							JOIN lnkPersonToTeam AS lnkPTT
							ON lnkPTT.team_id = T.id
							JOIN Person AS P
							ON lnkPTT.person_id = P.id
							WHERE  O.id = "'.$org_id.'" AND P.status = "active" ',
				'output_fields' => 'name,first_name,email, phone, function'
		);
		$results = $this->CallWebService( $aData);
		//Zend_Debug::dump($results);
		$tab_Person_list = array();
		if (count($results['objects'])>0){
			$i = 0;
			foreach ($results['objects'] as $result) {
				$tab_Person_list[$i]['name'] = $result['fields']['name'];
				$tab_Person_list[$i]['first_name'] = $result['fields']['first_name'];
				$tab_Person_list[$i]['phone'] = $result['fields']['phone'];
				$tab_Person_list[$i]['email'] = $result['fields']['email'];
				$tab_Person_list[$i]['description'] = $result['fields']['function'];
				$i++;
			}
		}
		//sort($tab_Person_list);
		foreach ($tab_Person_list as $key => $row) {
			$description[$key]  = $row['description'];
			$name[$key] = $row['name'];
			$first_name[$key] = $row['first_name'];
		}
		array_multisort($description, SORT_ASC,
		$first_name, SORT_ASC,
		$name, SORT_ASC,
		$tab_Person_list);
		return $tab_Person_list;
	}
	
	
	/* Lister les équipes par support*/
	public function getTeamLeaderSupport($org_id) {
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Person',
				//'key' => 'SELECT lnkTeamToContact WHERE team_name = "'.$support.'"',
				'key' => 'SELECT P  FROM
							Organization AS O
							JOIN DeliveryModel AS DM
							ON O.deliverymodel_id = DM.id
							JOIN lnkDeliveryModelToContact AS lnkDMTC
							ON lnkDMTC.deliverymodel_id = DM.id
							JOIN Team AS T
							ON lnkDMTC.contact_id = T.id
							JOIN lnkPersonToTeam AS lnkPTT
							ON lnkPTT.team_id = T.id
							JOIN Person AS P
							ON lnkPTT.person_id = P.id
							WHERE  O.id = "'.$org_id.'" AND P.status = "active" AND lnkPTT.role_name= "Manager"',
				'output_fields' => 'name,first_name,email, phone, function'
		);
		$results = $this->CallWebService( $aData);
		//Zend_Debug::dump($results);
		$tab_Person_list = array();
		if (count($results['objects'])>0){
			$i = 0;
			foreach ($results['objects'] as $result) {
				$tab_Person_list[$i]['name'] = $result['fields']['name'];
				$tab_Person_list[$i]['first_name'] = $result['fields']['first_name'];
				$tab_Person_list[$i]['phone'] = $result['fields']['phone'];
				$tab_Person_list[$i]['email'] = $result['fields']['email'];
				$tab_Person_list[$i]['description'] = $result['fields']['function'];
				$i++;
			}
		}
		//sort($tab_Person_list);
		foreach ($tab_Person_list as $key => $row) {
			$description[$key]  = $row['description'];
			$name[$key] = $row['name'];
			$first_name[$key] = $row['first_name'];
		}
		array_multisort($description, SORT_ASC,
		$first_name, SORT_ASC,
		$name, SORT_ASC,
		$tab_Person_list);
		return $tab_Person_list;
	}
	
	//Admin Services into Portal
	public function getAllServices() {
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'Service',
				'key' => 'SELECT Service ',
				'output_fields' => 'id,name,description,friendlyname,servicefamily_id,servicefamily_name,status'
		);
		$results = $this->CallWebService($aData);
		$tab_Srv_list = array();
		if (count($results['objects'])>0){
			$i = 0;
			foreach ($results['objects'] as $result) {
				$tab_Srv_list[$i]['id'] = $result['fields']['id'];
				$tab_Srv_list[$i]['name'] = $result['fields']['name'];
				$tab_Srv_list[$i]['friendlyname'] = $result['fields']['friendlyname'];
				$tab_Srv_list[$i]['description'] = $result['fields']['description'];
				$tab_Srv_list[$i]['status'] = $result['fields']['status'];
				$tab_Srv_list[$i]['servicefamily_id'] = $result['fields']['servicefamily_id'];
				$tab_Srv_list[$i]['servicefamily_name'] = $result['fields']['servicefamily_name'];
				$i++;
			}
		}
		//sort($tab_SrvElt_list);
		return $tab_Srv_list;
	}
	
	public function getAllServiceSubcategory() {
		$aData = array(
				'operation'=> 'core/get',
				'class' => 'ServiceSubcategory',
				'key' => 'SELECT ServiceSubcategory',
				'output_fields' => 'id,name,friendlyname,description,status,request_type'
		);
		$results = $this->CallWebService($aData);
		$tab_SrvElt_list = array();
		if (count($results['objects'])>0){
			$i = 0;
			foreach ($results['objects'] as $result) {
				$tab_SrvElt_list[$i]['id'] = $result['fields']['id'];
				$tab_SrvElt_list[$i]['name'] = $result['fields']['name'];
				$tab_SrvElt_list[$i]['friendlyname'] = $result['fields']['friendlyname'];
				$tab_SrvElt_list[$i]['description'] = $result['fields']['description'];
				$tab_SrvElt_list[$i]['status'] = $result['fields']['status'];
				$tab_SrvElt_list[$i]['request_type'] = $result['fields']['request_type'];
				$i++;
			}
		}
		//Zend_Debug::dump($tab_SrvElt_list);
		//sort($tab_SrvElt_list);
		return $tab_SrvElt_list;
	}
	
}

?>
