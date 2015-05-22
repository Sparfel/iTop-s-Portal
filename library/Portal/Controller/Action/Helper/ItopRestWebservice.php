<?php
/*
 * Created on 15 mars 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Portal_Controller_Action_Helper_ItopRestWebservice extends Zend_Controller_Action_Helper_Abstract 
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
		if ($identity->is_staff == '1') // Si personnel Syles activé alors on bascule sur iTop de production
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
		$this->_name =$session->pref->_user_name; 
        $this->_first_name = $session->pref->_user_first_name;
		$this->_org_id = $session->pref->_org_id;
		$this->_user_id = $session->pref->_user_id;
	}

	public function callRestWebService($v_query,$v_fields)
	{	
		$result ='Error';
		$query = rawurlencode($v_query);
		$fields = $v_fields;
		
		$format = 'xml';
		$url = $this->_protocol.'://'.
				$this->_username.':'.$this->_password.'@'.$this->_adress.
				'/webservices/export.php?expression='.
				$query.'&format='.
				$format.'&fields='.
				$fields.'&login_mode=basic';
		//Zend_Debug::dump($url);
		try{
			$html = file_get_contents($url);
			$xml = simplexml_load_string($html);

			$result = $xml;
			
		}
		catch(Zend_Rest_Exception $e)
		{
			echo 'La requête n\'a rien ramené.';
			$this->view->resultat = false;
			$this->view->form = $form;
			echo "Message: " . $e->getMessage() . "\n";
		}
		return $result; 
	}
	
	/* On transforme le fichier Xml en tableau
	 * Array [0] => array [Objet1] => array (attribut1 => valeur1,
	 * 										attribut2 => valeur2,
	 * 										..
	 * 										)
	 * 				array [objet2] => array (attribut1 => valeur1,
	 * 										attribut2 => valeur2,
	 * 										..
	 * 										)
	 * 		[1] => array [Objet1] => array (attribut1 => valeur1,
	 * 										attribut2 => valeur2,
	 * 										..
	 * 										)
	 * 				array [objet2] => array (attribut1 => valeur1,
	 * 										attribut2 => valeur2,
	 * 										..
	 * 										)
	 * 						....
	 * on récupère des données d'une requête OQL, chaque ligne du tableau
	 * correspond à une ligne résultat de la requête. Les différents objets
	 * ramenés par la requête sont les clés qui donnent accès à un tableau 
	 * des attributs de chaque objet.
	 */
	function xmlToArray($xml){
		$tab_result = Array();
		$i = 0;
		foreach($xml->children() as $row) {
				
			foreach($row->children() as $child) {
				$role = $child->getName();
				//echo $role;
				//echo '<br>';
				foreach($child as $key => $value) {
					//print_r($child);
					$tab_result[$i][$role][$key]= (string) $value;
					//echo ' '.$value;
				}
				//print_r($tab_result);
			}
			$i++;
		}
		return $tab_result;
	}
	
	/* 
	 * Les différentes fonction appelant des requetes OQL
	 * 
	 */
	
	function getInfoService() {
		$query = "SELECT SF,S,CC,SC FROM ServiceContract AS SC
				JOIN CustomerContract AS CC ON SC.customercontract_id = CC.id
				JOIN Service AS S ON SC.service_id = S.id
				JOIN ServiceFamily AS SF ON S.servicefamily_id = SF.id
				WHERE CC.org_id ='".$this->_org_id."'";
		
	
		$xml = $this->callRestWebService($query,null);
		
		$tab_result = $this->xmlToArray($xml);
		return $tab_result;
	}
	
	
	/*
	 * On a 
	 * 		ServiceFamily
	 * 			|
	 * 		Service
	 * 			|
	 * 		Contrat de Service
	 * 			|
	 * 		Contrat Service Client
	 * 
	 */
	function getCumulService() {
		$tab = $this->getInfoService();
		$i = 0;
		//Zend_Debug::dump($tab);
		//On met en tableau les différents objets
		foreach ($tab as $key => $values)
			{
			$tab_ServiceFamily[$i] = $values['ServiceFamily']['name'];
			$tab_Service[$i] = $values['Service']['name'];
			$tab_CustomerContrat[$i] = $values['CustomerContract']['name'];
			$tab_ServiceContrat[$i] = $values['ServiceContract']['name'];
			$i++;
			}
		//['Name', 'Service', 'Tooltip'],
		// on commence par le sommet :
		$result_string ='';
		$tab_recap_ServiceFamily = array_count_values($tab_ServiceFamily);
		foreach ($tab_recap_ServiceFamily as $key => $values)
		{
			$result_string .='["'.$key.'","","Famille de Service"],'; 
		}
		//Puis les Services
		for ($j=0; $j <$i; $j++)
		{
			$result_string .='["'.$tab_Service[$j].'","'.$tab_ServiceFamily[$j].'","Service"],';
		}
		
		//Puis les contrats Services
		for ($j=0; $j <$i; $j++)
		{
			$result_string .='["'.$tab_ServiceContrat[$j].'","'.$tab_Service[$j].'","Contrat de Service"],';
		}

		//Puis les contrats client
		for ($j=0; $j <$i; $j++)
		{
			if ($j == ($i -1))
				{$result_string .='["'.$tab_CustomerContrat[$j].'","'.$tab_ServiceContrat[$j].'","Contrat de client"]';}	
			else 
				{$result_string .='["'.$tab_CustomerContrat[$j].'","'.$tab_ServiceContrat[$j].'","Contrat de client"],';}
		}
		
		return $result_string;
	}
	
	
	/*
	 * Les différentes fonction appelant des requetes OQL
	*
	*/
	
	function getInfoEltService() {
		$query = "SELECT SS,S,SF,SC 
				FROM ServiceSubcategory AS SS
				JOIN Service AS S ON SS.service_id = S.id
				JOIN ServiceFamily AS SF ON S.servicefamily_id = SF.id
				JOIN lnkCustomerContractToService AS lnkCCTS ON lnkCCTS.service_id = S.id
				JOIN CustomerContract AS SC ON lnkCCTS.customercontract_id = SC.id
				WHERE SC.org_id = '".$this->_org_id."'";
	
		//$field ='SE.id,SE.name';
		
		$xml = $this->callRestWebService($query,null);
		//$tab_result = $xml;
		$tab_result = $this->xmlToArray($xml);
		//Zend_Debug::dump($tab_result);
		return $tab_result;
	}
	
	
	
	/*Use for GoogleOrgChart.*/ 
	/*
	 * On a
	* 		ServiceFamily
	* 			|
	* 		Service
	* 			|
	* 		Service Elément
	*
	*/
	function getCumulEltService() {
		$tab = $this->getInfoEltService();
		$i = 0;
		//Zend_Debug::dump($tab);
		//On met en tableau les différents objets
		foreach ($tab as $key => $values)
		{
			$tab_ServiceFamily[$i] = $values['ServiceFamily']['name'];
			$tab_Service[$i] = $values['Service']['name'];
			$tab_ServiceElement[$i] = $values['ServiceSubcategory']['name'];
			$i++;
		}
		//['Name', 'Service', 'Tooltip'],
		// on commence par le sommet :
		$result_string ='';
		$tab_recap_ServiceFamily = array_count_values($tab_ServiceFamily);
		foreach ($tab_recap_ServiceFamily as $key => $values)
		{
			$result_string .='["'.$key.'","","Famille de Service"],';
		}
		//Puis les Services
		for ($j=0; $j <$i; $j++)
		{
		$result_string .='["'.$tab_Service[$j].'","'.$tab_ServiceFamily[$j].'","Service"],';
		}
	
		//Puis les contrats Services
		for ($j=0; $j <$i; $j++)
		{
		$result_string .='["'.$tab_ServiceElement[$j].'","'.$tab_Service[$j].'","Elément de Service"],';
		}
	
			return $result_string;
	}
	
	function getCumulEltService2() {
		$tab = $this->getInfoEltService();
		$i = 0;
		//Zend_Debug::dump($tab);
		//On met en tableau les différents objets
		foreach ($tab as $key => $values)
		{
			$tab_ServiceFamily[$i]['id'] = $values['Service']['servicefamily_id'];
			$tab_ServiceFamily[$i]['parent_id'] = 'null'; //$values['ServiceFamily']['parent_id'];
			$tab_ServiceFamily[$i]['name'] = $values['ServiceFamily']['name'];
			$tab_ServiceFamily[$i]['description'] = $values['ServiceFamily']['description'];
			
			$tab_Service[$i]['id'] = $values['ServiceSubcategory']['service_id'];
			$tab_Service[$i]['parent_id'] = $values['Service']['servicefamily_id'];
			$tab_Service[$i]['name'] = $values['Service']['name'];
			$tab_Service[$i]['description'] = $values['Service']['description'];
	
			$tab_ServiceElement[$i]['id'] = $i; //$values['ServiceElement']['id'];
			$tab_ServiceElement[$i]['parent_id'] = $values['ServiceSubcategory']['service_id'];
			$tab_ServiceElement[$i]['name'] = $values['ServiceSubcategory']['name'];
			$tab_ServiceElement[$i]['description'] = $values['ServiceSubcategory']['description'];
			
			$i++;
		}
		//['Name', 'Service', 'Tooltip'],
		// on commence par le sommet :
		$result_string ='';
		//$tab_recap_ServiceFamily = array_count_values($tab_ServiceFamily);
		//Zend_Debug::dump($tab_recap_ServiceFamily);
		/*foreach ($tab_recap_ServiceFamily as $key => $values)
		{
		$result_string .='["'.$key.'","","Famille de Service"],<br>';
		}*/
		$tab_result = array();
		$k=0;
		for ($j=0; $j <$i; $j++)
		{
		//$result_string .='{id:'.$tab_ServiceFamily[$j]['id'].', parentId:'.$tab_ServiceFamily[$j]['parent_id'].',name:'.$tab_ServiceFamily[$j]['name'].'},<br>';
		//$tab_result[$k] = '{id:'.$tab_ServiceFamily[$j]['id'].', parentId:'.$tab_ServiceFamily[$j]['parent_id'].',Nom:"'.$tab_ServiceFamily[$j]['name'].'",Description:"'.$tab_ServiceFamily[$j]['description'].'",image:"http://www.syleps.com/bundles/sylepsfront/images/logo.png"},';
			$tab_result[$k] = '{id:'.$tab_ServiceFamily[$j]['id'].', parentId:'.$tab_ServiceFamily[$j]['parent_id'].',Nom:"'.$tab_ServiceFamily[$j]['name'].'",Description:"'.$tab_ServiceFamily[$j]['description'].'"},';
		$k++;
		}
		
		
		//Puis les Services
		for ($j=0; $j <$i; $j++)
		{
			//$result_string .='["'.$tab_Service[$j].'","'.$tab_ServiceFamily[$j].'","Service"],<br>';
			//$result_string .='{id:'.$tab_Service[$j]['id'].', parentId:'.$tab_Service[$j]['parent_id'].',name:'.$tab_Service[$j]['name'].'},<br>';
			//$tab_result[$k] ='{id:'.$tab_Service[$j]['id'].', parentId:'.$tab_Service[$j]['parent_id'].',Nom:"'.$tab_Service[$j]['name'].'",Description:"'.$tab_Service[$j]['description'].'",image:"http://www.syleps.com/bundles/sylepsfront/images/logo.png"},';
			$tab_result[$k] ='{id:'.$tab_Service[$j]['id'].', parentId:'.$tab_Service[$j]['parent_id'].',Nom:"'.$tab_Service[$j]['name'].'",Description:"'.$tab_Service[$j]['description'].'"},';
			$k++;
		}
		
		//Puis les contrats Services
		for ($j=0; $j <$i; $j++)
			{
			//$result_string .='["'.$tab_ServiceElement[$j].'","'.$tab_Service[$j].'","Elément de Service"],<br>';
			//$result_string .='{id:'.$tab_ServiceElement[$j]['id'].', parentId:'.$tab_ServiceElement[$j]['parent_id'].',name:'.$tab_ServiceElement[$j]['name'].'},<br>';
			//$tab_result[$k] ='{id:'.$tab_ServiceElement[$j]['id'].', parentId:'.$tab_ServiceElement[$j]['parent_id'].',Nom:"'.$tab_ServiceElement[$j]['name'].'",Description:"'.str_replace(CHR(13).CHR(10),"",$tab_ServiceElement[$j]['description']).'",image:"http://www.syleps.com/bundles/sylepsfront/images/logo.png"},';
			$tab_result[$k] ='{id:'.$tab_ServiceElement[$j]['id'].', parentId:'.$tab_ServiceElement[$j]['parent_id'].',Nom:"'.$tab_ServiceElement[$j]['name'].'",Description:"'.str_replace(CHR(13).CHR(10),"",$tab_ServiceElement[$j]['description']).'"},';
			$k++;
		}
		
		$tab = array_unique($tab_result);
		
		foreach ($tab as $res) {
			$result_string .=$res;
		} 
		
		return $result_string;
	}
	
	
	/* 
	 * Localisation des appelants
	 */
		public function getPerLocationRequestTab($org_id) {
			//$query = "SELECT L,U FROM Location AS L JOIN Person AS P ON P.site_id=L.id JOIN UserRequest AS U ON U.caller_id=P.id WHERE P.org_id ='".$org_id."'";
			$query = "SELECT UserRequest WHERE org_id ='".$org_id."'";
			//echo $query;
			$xml = $this->callRestWebService($query,null);
			//Zend_Debug::dump($xml);
			$tab_result = $this->xmlToArray($xml);
			//Zend_Debug::dump($tab_result);
			/*$i=0;
			foreach ($xml as $key=>$value)
			{
				$tab_result[$i] = $value;
				$i++;
			} 
			
			 array array_count_values ( array $array )
			Retourne un tableau contenant les valeurs du tableau array comme clés et leur fréquence comme valeurs. 
			Zend_Debug::dump($tab_result);*/
			//print_r($query);
			return $tab_result;
	
	}
	
	
	//OQL Requests about the Services
	
	/*
	 * Fonction utiliser dans l'écran d'accueil des services.
	*/
	public function getItopServices($org_id) {
		
			$query = "SELECT S,lnkCCTS,SC,SF FROM CustomerContract AS SC 
					JOIN lnkCustomerContractToService AS lnkCCTS
					ON lnkCCTS.customercontract_id = SC.id
					JOIN Service AS S
					ON lnkCCTS.service_id = S.id
					JOIN ServiceFamily AS SF
					ON S.servicefamily_id = SF.id
					WHERE SC.org_id = '".$this->_org_id."'";
		
			$xml = $this->callRestWebService($query,null);
		
			$tab_result = $this->xmlToArray($xml);
			//Zend_Debug::dump($tab_result);
			return $tab_result;
		
		
	}
	
	function getListUser() {
		$query = "SELECT U,P,O
					FROM UserLocal AS U
					JOIN Person AS P
					ON U.contactid = P.id
					JOIN Organization AS O
					ON P.org_id = O.id
					WHERE P.status = 'active'";
	
	
		$xml = $this->callRestWebService($query,null);
	
		$tab_result = $this->xmlToArray($xml);
		//Zend_Debug::dump($tab_result);
		return $tab_result;
	
	}
	
}

?>
