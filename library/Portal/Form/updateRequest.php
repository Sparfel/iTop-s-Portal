<?php 
class Portal_Form_updateRequest extends Centurion_Form
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setName('attachment');

        //$ref = new Zend_Form_Element_Hidden('Ref');
        //$ref->setValue($RequestRef);
       
                 
		// Attachment (plusieurs pièces jointes)
        $file = new Zend_Form_Element_File('tab_files[]');
        $file->setIsArray(true);  //Pour pouvoir gérer un tableau de pièces jointes
        $file->setAttrib('id','tab_files');
       
        $file->addDecorator ( 'Fieldset', array ('legend' => $this->_translate('Ajouter des pièces jointes')) );
        
        $content = new Zend_Form_Element_Textarea('Log');
        $content->setLabel($this->_translate('Public Log'))
        //->setRequired(true)
        ->setAttrib('rows',5)
        ->setAttrib('width','100%')
        ->addFilter('StringTrim');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
        		->setLabel($this->_translate('Mise à jour'))
        		->setDecorators(array(
        				'ViewHelper',
        				'Description',
        				'Errors',
        				array('HtmlTag', array('tag' => 'li', 'class'=>'buttonsinline'))
        		));

        $resolved = new Zend_Form_Element_Submit('resolved');
        $resolved->setAttrib('id', 'resolvebutton')
        		->setLabel($this->_translate('Résolution'))
        		->setDecorators(array(
        				'ViewHelper',
        				'Description',
        				'Errors',
        				array('HtmlTag', array('tag' => 'li', 'class'=>'buttonsinline'))
        		));
        
        $closed = new Zend_Form_Element_Submit('close');
        $closed->setAttrib('id', '$closebutton')
        		->setLabel($this->_translate('Fermeture'))
        		->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array('HtmlTag', array('tag' => 'li', 'class'=>'buttonsinline'))
        		));
        
        $reopen = new Zend_Form_Element_Submit('reopen');
        $reopen->setAttrib('id', 'reopenbutton')
        		->setLabel($this->_translate('Ré-ouvrir'))
        		->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array('HtmlTag', array('tag' => 'li', 'class'=>'buttonsinline'))
        		));
      	
      	
      	if ($options['resolve']) {
      		$this->addElements(array($file,$content,$submit,$resolved));
      		$this->addDisplayGroup(
      				array( 'submit',
      						'resolved',
      						'tab_files',
      						'Log'
      				),
      				'request',
      				array(
      						'legend' => $this->_translate('Mise à jour du ticket')
      				)
      			);
      		
      	}
      	elseif ($options['close'] && $options['reopen'] ) { // on ne gère pas les autres cas mais on pourrait !
      		// un ticket résolu pourra soit etre réouvert ou fermé.
      		$this->addElements(array($file,$content,$closed,$reopen));
      		$this->addDisplayGroup(
      				array(  'reopen',
      						'close',
      						'tab_files',
      						'Log'      						
      				),
      				'request',
      				array(
      						'legend' => $this->_translate('Mise à jour du ticket')
      				)
      		);
      	
      	}
		elseif ($options['update']) {
			$this->addElements(array($file,$content,$submit));
			$this->addDisplayGroup(
					array('submit',
							'tab_files',
							'Log'							
					),
					'request',
					array(
							'legend' => $this->_translate('Mise à jour du ticket')
					)
			);
		}
		else {
				$this->addElements(array($file,$content));
				$this->addDisplayGroup(
						array('tab_files',
								'Log'
						),
						'request',
						array(
								'legend' => $this->_translate('Mise à jour du ticket')
						)
				);
		}
      	
		$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/request/openedrequest/update/id/'.$options['id']);
		
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

}