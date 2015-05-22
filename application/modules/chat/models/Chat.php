<?php

class Chat_Model_Chat
{
    protected $_message;
    protected $_sessId;
    protected $_role;
    protected $_id;
    protected $_user;
    protected $_org_id;
    protected $_organization;
    
    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
    
    public function __set($name, $value)
    {
        $method = 'set' . $name;

        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property');
        }

        $this->$method($value);
    }
    
    public function __get($name)
    {
        $method = 'get' . $name;

        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property');
        }

        return $this->$method();
    }
    
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }

        return $this;
    }
    
    public function setMessage($message)
    {
        $this->_message = (string) $message;
        return $this;
    }
    
    public function getMessage()
    {
        return $this->_message;
    }
    
    public function setSessId($sessId)
    {
        $this->_sessId = $sessId;
        return $this;
    }
    
    public function getSessId()
    {
        return $this->_sessId;
    }
    
    public function setRole($role)
    {
        $this->_role = $role;
        return $this;
    }

    public function getRole()
    {
        return $this->_role;
    }
    
    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setUser($user)
    {
    	$this->_user = $user;
    	return $this;
    }
    
    public function getUser()
    {
    	return $this->_user;
    }    

    public function setOrg_id($org_id)
    {
    	$this->_org_id = $org_id;
    	return $this;
    }
    
    public function getOrg_id()
    {
    	return $this->_org_id;
    }
    
    public function setOrganization($organization)
    {
    	$this->_organization = $organization;
    	return $this;
    }
    
    public function getOrganization()
    {
    	return $this->_organization;
    }
    
    
}

