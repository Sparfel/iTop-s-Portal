<?php

class Portal_Form_NewRequest extends Centurion_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('newRequest');

        //$id = new Zend_Form_Element_Hidden('id');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel($this->_translate('Title'))
	        ->setRequired(true)
	        ->setAllowEmpty(false)        
	        ->addFilter('StripTags')
	        ->addFilter('StringTrim')
	        ->addValidator('NotEmpty')
	        ->setAttrib('size','40')
        	->setAttrib('required',true);

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel($this->_translate('Description'))
	        ->setRequired(true)
	        ->addFilter('StripTags')
	        ->addFilter('StringTrim')
	        ->setAttribs(array(
	                'cols' => 60,
	                'rows' => 7))
	        ->setAttrib('required',true);
         
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
        		->setLabel($this->_translate('Valider'));
        
        
        /*$cancel = new Zend_Form_Element_Submit('reset');
        $cancel->setAttrib('id', 'resetbutton')
        		->setLabel('Annuler');
        */

         
		// Attachment (plusieurs pièces jointes)
        $file = new Zend_Form_Element_File('tab_files[]');
        $file->setIsArray(true);  //Pour pouvoir gérer un tableau de pièces jointes
        $file->setAttrib('id','tab_files');
        	//->setMultiFile(3)
        	//->setMaxFileSize(10485760)
        	//->setLabel($this->_translate('Ajouter des pièces jointes'))
        	//->setAttrib('multiple','multiple')
        	/*->setAttribs(array(
        						'label'=>'Files',
        						'order'=>'2'
        						)
        					);*/
        		
      	//$file->addDecorator('HtmlTag', array('tag' => 'fieldset', 'class' => 'attachfieldset'))
      	$file->addDecorator ( 'Fieldset', array ('legend' => $this->_translate('Ajouter des pièces jointes')) );
      	

      	
		
      	$this->addElements(array($title, $description,$file,$submit));
	    
	    $this->addDisplayGroup(
	        		array(
		        		'title',
		        		'description',
		        		'tab_files',
		        		'submit'
	        		),
	        		'request',
	        		array(
	        			'legend' => $this->_translate('Please fill the informations below :')
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

}
