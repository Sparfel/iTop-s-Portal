<?php
class Portal_Auth_Adapter_Ldap 
{
	/* Adresse hébergeant l'annuaire Ldap*/
	protected $_host;
	
	/*Username du style 'CN=Emmanuel LOZACMHEUR,OU=People,DC=sydel,DC=fr'*/
	protected $_username;
	
	/* suffixe : OU=People,DC=sydel,DC=fr*/
	protected $_suffix;
	
	/*true or false*/
	protected $_bindRequiresDn;
	
	/*nom de domaine : sydel.fr par exemple*/
	protected $_accountDomainName;
	
	/*BaseDN : DC=sydel,DC=fr par exemple*/
	protected $_baseDn;
	
	/*cn : Common Name dans Ldap*/
	protected $_cn;
	
	/*Objet correspondant au info de l'annuaire Ldap suite à une recherche.*/
	public $_inetOrgPerson;
	
	/*Message de retour du Ldap*/
	public $_message;
	
	/*Constante de retour.*/
	const LDAP_UNKNOWN = 'UNKNOWN';
	const LDAP_KNOWN = 'KNOWN';
	const LDAP_ERROR = 'ERROR';
	
	
	//Interrogation anonyme de l'annuaire
	//pour connaire le CN d'un SN donné 
	//TODO ou avec email
	
	/**
	 * Constructor
	 *
	 * Récupération des info du Ldap dans le fichier de configuration 
	 * @return void
	 */
	public function __construct()
	{
		$config = new  Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
		$this->_host = $config->ldap->server1->host;
		$this->_bindRequiresDn = $config->ldap->server1->bindRequiresDn;
		$this->_accountDomainName = $config->ldap->server1->accountDomainName;
		$this->_baseDn = $config->ldap->server1->baseDn;
		$this->_suffix = $config->ldap->server1->suffix;
	}

	
	/*
	 * Anonymous query in Ldap
	 * On demande à Ldap qui se cache derriere ce login
	 * on demande qui est 'eloz' et on récupèrera 'Emmanuel LOZachmeur' par exemple 
	 */
	public function WhoIs($login){
		// on va interroger de façon anoynyme l'annuaire Ldap
		$options = array( 'host'              => $this->_host);
		try {
			$ldap = new Zend_Ldap($options);
			$ldap->bind();
			//On donne le critère d'interrogation
			$filter=Zend_Ldap_Filter::equals('objectClass','inetOrgPerson')
										->addAnd(Zend_Ldap_Filter::equals('sn', $login));
			//on effectue l'interrogation
			$this->_inetOrgPerson = $ldap->search($filter, 
									$this->_suffix,
									Zend_Ldap::SEARCH_SCOPE_ONE);
			//Récupération des CN 
			$tab_cn= $this->_inetOrgPerson->getFirst();
			//On prend le premier
			$this->_cn = $tab_cn['cn'][0];
			
			// Vu avec BDAV : Il faut utiliser le DN qui est retourné à l'étape 1 qui 
			// garantit l'unicité (cf. http://fr.wikipedia.org/wiki/Lightweight_Directory_Access_Protocol#Structure_de_l.27annuaire)
			$this->_baseDn = $tab_cn['dn'];
			//Zend_Debug::dump($this->_baseDn);
			if (is_null($this->_cn)) {return self::LDAP_UNKNOWN;}
			else {return self::LDAP_KNOWN;}
			
		} catch (Exception $e) {
			return $e->getMessage();
			//return self::LDAP_ERROR;
		}
	}
	
	//Tentative de connexion au Ldap avec l'identité trouvé et le password saisi
	// Si succès OK, sinon ERROR avec la cause.
	/*
	 * Tentative de connexion identifiée à partir des informations de login
	 * Rend true si OK, false sinon
	 * On part du principe que le login est précedemment défini par un WhoIs()
	 */
	
	public function IsAllowed($password) {
		
		try {
		//On prend l'identité de l'objet.
		$cn = $this->_cn;
		//on tente alors une connexion de cet utilisateur sur l'annuaire
		$options = array( 'host'              => $this->_host,
						'username'          => 'CN='.$cn.','.$this->_suffix,
						'password'          => $password,
						'bindRequiresDn'    =>  $this->_bindRequiresDn,
						'accountDomainName' => $this->_accountDomainName,
						'baseDn'            => $this->_baseDn);
		$ldap = new Zend_Ldap($options);
		$ldap_cnx = $ldap->bind();
		//echo $ldap_cnx->getBoundUser();
		//Zend_Debug::dump($ldap->bind());
		return true;
		}
		catch (Zend_Ldap_Exception $e) {
			//echo $e->getMessage().'<br>';
			//echo $e->getErrorCode().'<br>';
			$this->_message = $e->getMessage();
			return false;
		}
		
	}
	
	
	public function listAllPeople() {
		// on va interroger de façon anoynyme l'annuaire Ldap
		$options = array( 'host'              => $this->_host);
		$options = array( 'host'              => $this->_host,
				'username'          => 'CN=admin,'.$this->_suffix,
				'password'          => 'h9ab5T',
				'bindRequiresDn'    =>  $this->_bindRequiresDn,
				'accountDomainName' => $this->_accountDomainName,
				'baseDn'            => $this->_baseDn);
		try {
			$ldap = new Zend_Ldap($options);
			$ldap->bind();
			//On donne le critère d'interrogation
			$filter=Zend_Ldap_Filter::equals('objectClass','inetOrgPerson');
			//->addAnd(Zend_Ldap_Filter::contains('sn', 'h'));
			//on effectue l'interrogation
			$this->_inetOrgPerson = $ldap->search($filter,
					$this->_suffix,
					Zend_Ldap::SEARCH_SCOPE_ONE);
			
			$nbEntry = $this->_inetOrgPerson->count();
			
			$result = array();
			$this->_inetOrgPerson->getFirst();
			$i = 0;
			while ($this->_inetOrgPerson->valid())
			{
				$result[$i] = $this->_inetOrgPerson->current();
				$this->_inetOrgPerson->next();
				$i++;
			}
			//Zend_Debug::dump($result);
			Return $result;
				
		} catch (Exception $e) {
			return $e->getMessage();
			//return self::LDAP_ERROR;
		}	
	}
	
}