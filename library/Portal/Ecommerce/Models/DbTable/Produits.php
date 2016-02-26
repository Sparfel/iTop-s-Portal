<?php
 class Portal_Ecommerce_Models_DbTable_Produits extends Centurion_Db_Table_Abstract

{
	protected $_database = 'ecommerce';
    /**
     * The table name
     * 
     * @var string
     */
    protected $_name = 'produits';

    /**
     * The primary key column or columns
     * 
     * @var mixed
     */
    protected $_primary = array('produitId');

    /**
     * Classname for row
     * 
     * @var string
     */

    protected $_rowClass = 'Portal_Ecommerce_Models_DbTable_Row_Produits';

 

    /**
     * Simple array of class names of tables that are "children" of the current table.
     * 
     * @var array
     */
    //protected $_dependentTables = array('posts' => 'Blog_Model_DbTable_Post');
    
    public function __construct() {
	  //on determine la base de donn�es dans laquelle se trouve l'objet.
	  $base = $this->_database;
	  $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/db.ini', APPLICATION_ENV);
	   $driverName = $config->$base->db->adapter;
	  
	   $host  = $config->$base->db->params->host;
	   $username = $config->$base->db->params->username;
	   $password = $config->$base->db->params->password;
	   $dbname = $config->$base->db->params->dbname;
	   $driver_options = $config->$base->db->params->driver_options_1002;
		//echo $host. ' '.$username.' '.$password.' '.$dbname;
	   $params = array
	        (
	        'host' => $host,
	        'username' => $username,
	        'password' => $password,
	        'dbname' => $dbname,
	        'driver_options' => array('1002'=>$driver_options)
	    );
	    try {
			  $this->_db = Zend_Db::factory($driverName,$params);
			  $this->_db->getConnection();
			  $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
			}
		catch ( Zend_Db_Adapter_Exception $e) {
	  			echo 'DB Cnx => '.$e->getMessage ();
			}
	}
	
	public function listProducts() {
		$select = $this->select ();
        $select->order('produitid ASC');
        return $this->fetchAll ($select);
	}
	
	
}