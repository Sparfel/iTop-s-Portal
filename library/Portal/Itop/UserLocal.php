<?php
class Portal_iTop_UserLocal {

	protected $_restWS;
	//Gestion des User iTop => user autre que Syleps car les users Syleps sont gérés via le Ldap
	protected $Default_group = 3;
	
	public function __construct() {
		$this->_restWS = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopRestWebservice');
		
	}

	public function synchronize(){
		
		$this->importAll();
	}
	
	public function importAll(){
		$itopuser = new Portal_iTop_Model_DbTable_ItopUser();
		//On vide la table et on va la recharger
		$itopuser->truncate();
		
		//récupération via le Webservice des userLocal dans iTop
		$itopdatas = $this->_restWS->getListUser();
		foreach ($itopdatas as $person) {
			//Zend_Debug::dump($person);
			$first_name = $person['Person']['first_name'];
			$last_name =  $person['Person']['name'];
			$email =  $person['Person']['email'];
			$login =  $person['UserLocal']['login'];
			$org_id = $person['Person']['org_id'];
			$org_name = $person['Organization']['name'];
			
			//On vérifie si le compte en question est déjà créé ou pas.
			//TODO
			$user = new Auth_Model_DbTable_User();
			$result = $user->findBy('username', $login);
			if (count($result)> 0 )
			{	//on retrouve le login parmi les user => on marque le compte comme déjà créé
				$is_local = 1;
				//On va ensuite récupérer son groupe utilisateur.
				$userRow = $result->current();
				$belong = new Auth_Model_DbTable_Belong();
				$belongRowset = $belong->findBy('user_id', $userRow->id);
				$belongRow = $belongRowset->current();
				$group = $belongRow->group_id;
			}
			else {
				$is_local = 0;
				$group = $this->Default_group;
			}
			//Insertion du compte dans la table des imports User iTop
			$itopuser->insUser($login,$first_name,$last_name,$email,$group, $is_local,$org_id,$org_name);
		}
		
	}	
	
	public function deleteAll(){
		$itopuser = new Portal_iTop_Model_DbTable_ItopUser();
		//On vide complètement la table (on va la recharger avec un import)
		$itopuser->truncate();
	}
	
	public function importNewUser(){
		
	}
	
	public function createAccount($rowset){
		if (null===$rowset) {
			return;
		}
		
		foreach ($rowset as $key => $row) {
			//On ne crée les comptes que pour ls user qui n'en ont pas !
			if ($row->is_local == 0){
				// On crée ensuite l'enregistrement le user local (table auth_user)
				$user = new Auth_Model_DbTable_User();
				//Détermination du password par défaut
				$username = $row->login;
				$salt = $this->getSalt($username);
				$password =  $this->setPassword($username,$salt,$row->first_name.'1234','sha1');
				$data = array ('username' => $username,
						'first_name' => $row->first_name,
						'last_name' => $row->last_name,
						'email' => $row->email,
						'is_active' => 1,
						'is_staff' => 1,
						'password' => $password,
						'salt' => $salt,
						'algorithm' =>'sha1'
				);
				$pk = $user->insert($data);
				 
				//On gère ensuite les autorisations
				//Zend_Debug::dump($pk);
				$belong = new Auth_Model_DbTable_Belong();
				$data = array('user_id' => $pk,
						'group_id' => $this->Default_group);
				$belong->insert($data);
				//Puis le profile
				$profile = new User_Model_DbTable_Profile();
				$data = array('user_id' => $pk,
						'nickname' => $username);
				$profile->insert($data);
				//Maj des données origines dans la table des imports => le compte a été créé
				$row->group_id = $this->Default_group; // Groupe Syleps par défaut
				$row->is_local = 1;
				$row->save();
			}
		}
	}
	
	
	protected function getSalt($username){
		return  md5(rand(100000, 999999). $username);
	}
	
	public function setPassword($username,$salt,$password,$algorithm)
	{
		$algorithmAsStr = is_array($algorithm) ? $algorithm[0] . '::' . $algorithm[1] : $algorithm;
	
		if (!is_callable($algorithm)) {
			throw new Centurion_Exception(sprintf('The algorithm callable "%s" is not callable.', $algorithmAsStr));
		}
	
		$algorithm = $algorithmAsStr;
	
		return call_user_func_array($algorithm, array($salt . $password));
	}
	
	
	
}