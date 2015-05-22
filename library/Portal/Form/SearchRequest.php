<?php

class Portal_Form_SearchRequest extends Centurion_Form
{
    public function __construct($criteria) //Tableau des critère de recherche
    {
        parent::__construct();
        $this->setName('SearchRequest');
        $this->setAttrib('onsubmit', 'return false'); // Le bouton valider est désactivé
        
        $decoratorsBegin = array(
        		'ViewHelper',
        		'Errors',
        		array('Description', array('tag' => 'p', 'class' => 'description')),
        		array('HtmlTag', array('tag' => 'td')),
        		array('Label', array('tag' => 'th')),
        		array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'openOnly'=>true))
        );
        $decorators = array(
        		'ViewHelper',
        		'Errors',
        		array('Description', array('tag' => 'p', 'class' => 'description')),
        		array('HtmlTag', array('tag' => 'td')),
        		array('Label', array('tag' => 'th')),
        		array(array('tr' => 'HtmlTag'), array('tag' => 'tr'))
        );
        $decoratorsEnd = array(
        		'ViewHelper',
        		'Errors',
        		array('Description', array('tag' => 'p', 'class' => 'description')),
        		array('HtmlTag', array('tag' => 'td')),
        		array('Label', array('tag' => 'th')),
        		array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'closeOnly'=>true))
        );
        
        
        $ref = new Zend_Form_Element_Text('ref');
        $ref->setLabel($this->_translate('Référence'))
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->setAttrib('size','20');
        $ref->setDecorators($decoratorsBegin);
        /*if (isset($criteria[$ref->getName()])) {
        	$ref->setValue($criteria[$ref->getName()]);
        }*/
        
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel($this->_translate('Title'))
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->setAttrib('size','20');
        $title->setDecorators($decoratorsEnd);
        
        $description = new Zend_Form_Element_Text('description');
        $description->setLabel($this->_translate('Description'))
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->setAttrib('size','20');
        $description->setDecorators($decoratorsBegin);
        
        $public_log = new Zend_Form_Element_Text('public_log');
        $public_log->setLabel($this->_translate('Journal Public'))
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->setAttrib('size','20');
        $public_log->setDecorators($decoratorsEnd);
        
        
        $date_started = new Zend_Form_Element_Text('date_started');
        $date_started->setLabel($this->_translate('Date de début'));
        $date_started->setAttrib('class', 'datepicker')->setAttrib('placeholder', 'yyyy-mm-dd');
        $date_started->setDecorators($decoratorsBegin);
        
        $date_closed = new Zend_Form_Element_Text('date_closed');
        $date_closed->setLabel($this->_translate('Date de clôture'));
        $date_closed->setAttrib('class', 'datepicker')->setAttrib('placeholder', 'yyyy-mm-dd');
        $date_closed->setDecorators($decoratorsEnd);
        
        
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
        		->setLabel($this->_translate('Rechercher'));
        $submit->setDecorators(array(
		            'ViewHelper',
		            array(array('td' => 'HtmlTag'), array('tag' => 'td', 'colspan' => 4, 'class'=>'BTSearch')),
		            array(array('tr' => 'HtmlTag'), array('tag' => 'tr'))
		        ));
        
        $this->addElements(array($ref,
		    			$title,
		        		$description,
		        		$public_log,
	        			$date_started,
	        			$date_closed,
	        			$submit	)
        				);
	    
	    $this->addDisplayGroup(
	        		array('ref',
		    			'title',
		        		'description',
		        		'public_log',
	        			'date_started',
	        			'date_closed',
	        			'submit'
		        		
	        		),
	        		'request',
	        		array(
	        			'legend' => $this->_translate('Critères')
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
        
        $request ->setDecorators(
	        		array(
	        		'FormElements',
	        		array('HtmlTag', array('tag' => 'table','class'=>'SearchModule')),
	        		'Form'
        			)
        		);
       
        
    }

}
