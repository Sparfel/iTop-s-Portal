<?php
//Authentification spécifique Ldap, Selon le cas, on bypass la comparaison du mot de passe
// car celui-ci a été validé par Ldap en amont, on récupère alors juste les données utilisateur (profile ...)
// Si Ldap n'a pas validé, on tente une vérif localement à l'application.
// Permet une double gestion et donc de s'affranchir si nécessaire du Ldap.

class Portal_Auth_Adapter_DbTable extends Centurion_Auth_Adapter_DbTable
{
	/*Utilisation de l'annuaire Ldap*/
	protected $_passByLdap = false;
	
	
	/**
	 * __construct() - Sets configuration options
	 *
	 * @param  Zend_Db_Adapter_Abstract $zendDb
	 * @param  string                   $tableName
	 * @param  string                   $identityColumn
	 * @param  string                   $credentialColumn
	 * @param  string                   $credentialTreatment
	 * @param  boolean					$passByLdapSyleps
	 * @return void
	 */
	public function __construct(Zend_Db_Adapter_Abstract $zendDb, $tableName = null, $identityColumn = null,
			$credentialColumn = null, $credentialTreatment = null, $passByLdap = null)
	{
		$this->_zendDb = $zendDb;
	
		if (null !== $tableName) {
			$this->setTableName($tableName);
		}
	
		if (null !== $identityColumn) {
			$this->setIdentityColumn($identityColumn);
		}
	
		if (null !== $credentialColumn) {
			$this->setCredentialColumn($credentialColumn);
		}
	
		if (null !== $credentialTreatment) {
			$this->setCredentialTreatment($credentialTreatment);
		}
		
		if (null !== $passByLdap) {
			$this->setLdapBypass($passByLdap);
		}
	}
	
	public function setLdapBypass($passByLdap)
	{
		$this->_passByLdap = $passByLdap;
		return $this;
	}
	
	
	protected function _authenticateCreateSelect()
	{
		// build credential expression
		if (empty($this->_credentialTreatment) || (strpos($this->_credentialTreatment, '?') === false)) {
			$this->_credentialTreatment = '?';
		}
		//Zend_Debug::dump($this->_passByLdapSyleps);
		
		//if (is_null($this->_passByLdapSyleps)) {$this->_passByLdapSyleps = 0;};
		$credentialExpression = new Zend_Db_Expr(
				'(CASE '
				//. 'WHEN COALESCE('.$this->_passByLdap.',0)=1 THEN 1 '
				. 'WHEN '.$this->_zendDb->quoteInto('? = 1',$this->_passByLdap).' THEN 1 '
				
				.'WHEN ' .
				$this->_zendDb->quoteInto(
						$this->_zendDb->quoteIdentifier($this->_credentialColumn, true)
						. ' = ' . $this->_credentialTreatment, $this->_credential
				)
				. ' THEN 1  ELSE 0 END) AS '
				. $this->_zendDb->quoteIdentifier(
						$this->_zendDb->foldCase('zend_auth_credential_match')
				)
		);
		//Zend_Debug::dump($credentialExpression);
		// get select
		$dbSelect = clone $this->getDbSelect();
		$dbSelect->from($this->_tableName, array('*', $credentialExpression))
		->where($this->_zendDb->quoteIdentifier($this->_identityColumn, true) . ' = ?', $this->_identity);
	
		//Zend_Debug::dump($dbSelect);
	
		 
		return $dbSelect;
	}
	
	
}