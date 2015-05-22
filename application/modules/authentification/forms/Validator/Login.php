<?php
/**
 * Centurion
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@centurion-project.org so we can send you a copy immediately.
 *
 * @category    Centurion
 * @package     Centurion_Form
 * @subpackage  Validator
 * @copyright   Copyright (c) 2008-2011 Octave & Octave (http://www.octaveoctave.com)
 * @license     http://centurion-project.org/license/new-bsd     New BSD License
 * @version     $Id$
 */

/**
 * @category    Centurion
 * @package     Centurion_Form
 * @subpackage  Validator
 * @copyright   Copyright (c) 2008-2011 Octave & Octave (http://www.octaveoctave.com)
 * @license     http://centurion-project.org/license/new-bsd     New BSD License
 * @author      Florent Messa <florent.messa@gmail.com>
 * @TODO: this is not a form validator. It's a global validator.
 
 * @Modified by Emmanuel Lozachmeur to use Ldap and iTop Connection
 *
 */
class Authentification_Form_Validator_Login extends Zend_Validate_Abstract
{
    const NOT_MATCH = 'notmatch';
    const DB_INVALID = 'databaseinvalid';
    const ITOP_UNKNOWN = 'itopunknown';
    const LDAP_ERROR = 'ldaperror';
    const LDAP_BUT_NOT_MATCH = 'ldapoknotmatch';
    const PB_LOCAL_CFG = 'profilinfomissing';

    /**
     * Db Adapter.
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_dbAdapter = null;

    /**
     * Table name.
     *
     * @var string
     */
    protected $_tableName = null;

    /**
     * Login column.
     *
     * @var string
     */
    protected $_loginColumn = null;

    /**
     * Password column.
     *
     * @var string
     */
    protected $_passwordColumn = null;

    /**
     * Salting mechanism.
     *
     * @var string
     */
    protected $_saltingMechanism = null;

    /**
     * Name of the alternative checked column.
     *
     * @var string
     */
    protected $_checkColumn = null;

    /**
     * Auth adapter name.
     *
     * @var string
     */
    protected $_authAdapter = 'Zend_Auth_Adapter_DbTable';
   
    
    /**
     * Using Ldap ?
     *
     * @var string
     */
    protected $_useLdap = null;
    
    /**
     * We Use Ldap => password tested by Ldap and not by the application. 
     *
     * @var string
     */
    protected $_passByLdap = null;

    /**
     * Array of validation failure messages
     *
     * @var array
     */
    protected $_messageTemplates = array(
         self::NOT_MATCH  => 'Invalid Credential',
         self::DB_INVALID => 'Database could not find a valid record, check your params',
    	 self::ITOP_UNKNOWN => 'This User is unknown in iTop\'s Database. Contact the Webmaster',
    	 self::LDAP_ERROR => 'Problem of connection with Ldap.',
    	 self::LDAP_BUT_NOT_MATCH =>'Not account user available.',
		 self::PB_LOCAL_CFG =>'Local Configuration is incomplete. Contact the Webmaster'
    );

    /**
     * Constructor.
     *
     * @param array $params Params
     * @return void
     */
    public function __construct(array $params)
    {
    	//Zend_Debug::dump($params);
        foreach ($params as $paramName => $paramValue) {
            $paramName = '_' . $paramName;
            if (property_exists($this, $paramName)) {
                $this->{$paramName} = $paramValue;
            }
        }
    }

    
    /**
     * Allow to activ or not the by pass with Ldap
     *
     */
    protected function setLdapUse($useLdap)
    {
    	$this->_useLdap = $useLdap;
    	return $this;
    }
    
    
    
    /**
     * Returns true if and only if $value passes all validations in the chain
     *
     * Validators are run in the order in which they were added to the chain (FIFO).
     *
     * @param  mixed $value
     * @return boolean
     * @TODO: check that required parameter are set
     */
    public function isValid($value, $context = null,$ldap = null )
    {
        /*$adapter = new $this->_authAdapter($this->_dbAdapter,
                                           $this->_tableName,
                                           $this->_loginColumn,
                                           $this->_passwordColumn,
                                           $this->_saltingMechanism);*/
    	//Zend_Debug::dump($ldap);
    	
    	//Ici la validation Ldap !
    	//$this->_passByLdap = true;
    	$ldapIdentity = false;
    	$adapterpb = new Zend_ProgressBar_Adapter_JsPush();
    	$progressBar = new Zend_ProgressBar($adapterpb, 0,100);
    	
    	if ($this->_useLdap) {
    		//On utilise l'annuaire Ldap pour la connexion.
    		//echo 'on est ici';
    		//Authentification_LoginController::progressbarAction(1);
    		
    		$message = 'Connexion Ldap et vérification identité.';
    		$progressBar->update(20,$message);
    		
    		$AuthLdap = new Portal_Auth_Adapter_Ldap();
    		$ldapIdentity = $AuthLdap->WhoIs($context['login']);
    		if ($ldapIdentity == Portal_Auth_Adapter_Ldap::LDAP_UNKNOWN) {
    			// Personne inconnue de Ldap, on va tenter la connexion sur compte local
    			$this->_passByLdap = false;
    			$message = 'Compte Ldap inconnu, connexion locale.';
    			$progressBar->update(30,$message);
    		}
    		else if ($ldapIdentity == Portal_Auth_Adapter_Ldap::LDAP_KNOWN) {
    			// Personne connue dans l'annuaire, on teste la connexion
    			//Authentification_LoginController::progressbarAction(2);
    			$message = 'Connexion compte Ldap.';
    			$progressBar->update(40,$message);
    			
    			$this->_passByLdap = $AuthLdap->IsAllowed($context['password']);
    			if (!$this->_passByLdap) {
    				//Mauvais mot de passe Ldap, on bloque.
    				$this->_error(self::NOT_MATCH);
    				$message = 'Mot de passe invalide.';
    				$progressBar->update(0,$message);
    				return false;
    			}
    		}
    		else if ($ldapIdentity == Portal_Auth_Adapter_Ldap::LDAP_ERROR) {
    			//Problème avec le Ldap.
    			//$this->_error(self::LDAP_ERROR);
    			//return false;
    			$this->_passByLdap = false;
    		}
    		else {// On ne doit pas passer par ici, mais au cas ou ...
	   			//On laisse la connexion sur compte local possible
    			$this->_passByLdap = false;
    		} 
    			
    		//echo $context['login'].'/'.$context['password'];
    	}
    	//Authentification_LoginController::progressbarAction(3);
    	$message = 'Connexion compte local.';
    	$progressBar->update(60,$message);
    	
        $adapter = new Portal_Auth_Adapter_DbTable($this->_dbAdapter,
        		$this->_tableName,
        		$this->_loginColumn,
        		$this->_passwordColumn,
        		$this->_saltingMechanism,
        		$this->_passByLdap);
                                           
        $adapter->setIdentity($context['login']);
        $adapter->setCredential($value);

        if (null !== $this->_checkColumn) {
            $adapter->getDbSelect()->where($this->_checkColumn);
        }

        try {
        	$message = 'Authentification compte local.';
        	$progressBar->update(70,$message);
        	
            $result = Centurion_Auth::getInstance()->authenticate($adapter);
            $message = 'Authentification compte local effectuée.';
            $progressBar->update(75,$message);
            
        } catch (Zend_Auth_Exception $e) {
            $this->_error(self::DB_INVALID);
            $message = 'Problème de connexion locale.';
            $progressBar->update(0,$message);
            return false;
        }

        if ($result->isValid()) {
            Centurion_Signal::factory('pre_login')->send(null, $adapter);
            //Authentification_LoginController::progressbarAction(4);
            $message = 'Récupération profil utilisateur.';
            $progressBar->update(80,$message);
            
            //Ici que cela prend le plus de temps.
            $result = $adapter->getResultRowObject(null);
            Centurion_Auth::getInstance()->clearIdentity();
            $message = 'Ecriture informations Utilisateur.';
            $progressBar->update(85,$message);
            Centurion_Auth::getInstance()->getStorage()->write($result);
            //Comment accélérer cela ? Semble rapide dans le cron 
            
			if ( is_null($result->first_name) or  is_null($result->last_name))
			{
				// L'identification est OK mais le compte n'est pas suffisament
				// renseigné pour assurer l'unicité du Contact iTop associé.
				// On rejette alors la demande de connexion et on effectue un clearIdentity
				Centurion_Auth::getInstance()->clearIdentity();
				$this->_error(self::PB_LOCAL_CFG);
				$message = 'Configuration Profil local incomplète.';
				$progressBar->update(0,$message);
				return false;
			}
            
            //On gère la connexion avec iTop ici
            //$pref= new Portal_Preference_Preference($result->id,$result->email);
			$message = 'Connexion au Helpdesk.';
			$progressBar->update(90,$message);
            $pref= new Portal_Preference_Preference($result->id,$result->email,$result->first_name,$result->last_name);
            if (is_null($pref->_user_id))
            {
            	Centurion_Auth::getInstance()->clearIdentity();
            	$this->_error(self::ITOP_UNKNOWN);
            	$message = 'Utilisateur Helpdesk inconnu.';
            	$progressBar->update(0,$message);
            	return false;
            }
            else 
            {
            	$session = new Zend_Session_Namespace('Zend_Auth');
            	$session->pref = $pref;
            }
            
            //Zend_Session::writeClose(false);

            Centurion_Signal::factory('post_login')->send(null, $result);
            //Authentification_LoginController::progressbarAction(5);
            $progressBar->finish();
            //$message = 'Complet';
            
            return true;
        }

        if ($ldapIdentity) {
        	//Si on est ici, cela signifie que le Ldap a certifié l'identité mais
        	//que la connexion a échoué => pas de compte local déclarer = pas de config utilisateur
        	$this->_error(self::LDAP_BUT_NOT_MATCH);
        	$message = 'Pas de compte local trouvé.';
        	$progressBar->update(0,$message);
        	return false;
        }
        
        $this->_error(self::NOT_MATCH);
        $message = 'Mot de passe invalide.';
        $progressBar->update(0,$message);
        return false;
    }
}
