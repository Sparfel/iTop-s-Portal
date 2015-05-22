<?php

class Portal_Form_Password extends Centurion_Form
{
    public function __construct() //($locations,$years,$locations_selected,$years_selected,$user_filter)
    {
        parent::__construct();
        $this->setName('password');
        //$this->setMethod('post');
        //$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/auth/admin-user/put');
        
        $pswd = new Zend_Form_Element_Password('pswd');
        $pswd->setLabel($this->_translate('Nouveau Mot de passe :'));
        $pswd->setAttrib('size', 35);
        $pswd->setAttrib('required',true);
        //$pswd->removeDecorator('label');
        //$pswd->removeDecorator('htmlTag');
        //$pswd->removeDecorator('Errors');
        $pswd->addValidator('StringLength', false, array(4,15));
        $pswd->addErrorMessage($this->_translate('Merci de choisir un mot de passe comprenant 4 à 15 caractères'));
        $pswd->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array('Label', array('placement' => 'PREPEND', 'escape' => false)),
        		array('HtmlTag', array('tag' => 'div','class'=>'UserOption'))
        		));
        
        $confirmPswd = new Zend_Form_Element_Password('confirm_pswd');
        $confirmPswd->setLabel($this->_translate('Confirmer le nouveau mot de passe :'));
        $confirmPswd->setAttrib('size', 35);
        $confirmPswd->setAttrib('required',true);
        $confirmPswd->addValidator('Identical', false, array('token' => 'pswd'));
        $confirmPswd->addErrorMessage($this->_translate('Les mots de passe ne correspondent pas.'));
        $confirmPswd->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array('Label', array('placement' => 'PREPEND', 'escape' => false)),
        		array('HtmlTag', array('tag' => 'div','class'=>'UserOption'))
        		));
        
        
        $submit = new Zend_Form_Element_Button('submit');
        $submit->setAttrib('id', 'submitbutton')
		       ->setLabel($this->_translate('Valider'));
        
       /*if ($validator->isValid($pwd1)) {
        	// l'email est valide
        } else {
        	// l'email est invalide ; affichons pourquoi
        	foreach ($validator->getMessages() as $messageId => $message) {
        		echo "Echec de validation '$messageId' : $message\n";
        	}
        	}
        */
        $this->addElements(array($pswd,$confirmPswd,$submit));
	    
	    $this->addDisplayGroup(
	        		array(
		        		'pswd',
		    			'confirm_pswd',
		        		'submit'
	        		),
	        		'request',
	        		array(
	        			'legend' => $this->_translate('Changement de mot de passe')
	        			)
        		);
        $request = $this->getDisplayGroup('request');
        $request ->setDecorators(
        			array(
		        		'FormElements',
		        		array('Fieldset'), //,array('class'=>'sectionwrap')),
		        		array('HtmlTag',
		        				array('tag'=>'div')
		        			)
		        		)
        			);
        
    }
    
    public function isValid ($data)
    {
    	$passTwice = $this->getElement('confirm_pswd');
    	$passTwice->getValidator('Identical')->setToken($data['pswd'])->setMessage($this->_translate('Les mots de passe ne correspondent pas.'));
    	return parent::isValid($data);
    }

}
