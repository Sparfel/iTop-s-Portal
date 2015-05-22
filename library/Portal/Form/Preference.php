<?php

class Portal_Form_Preference extends Centurion_Form
{
    public function __construct($Opref) //($locations,$years,$locations_selected,$years_selected,$user_filter)
    {
        parent::__construct();
        $this->setName('preference');
        
        
        $filterUser = new Zend_Form_Element_Checkbox($Opref->_ParamName_UserFilter);
        $filterUser->setLabel($this->_translate('Ne voir que vos tickets'))
        	->setAttrib('class', $Opref->_ParamName_UserFilter)
        	->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array('Label', array('placement' => 'PREPEND', 'escape' => false)),
        		array('HtmlTag', array('tag' => 'div','class'=>'UserOption'))
        ));
      	
        if ($Opref->_userFilter == 'true') {$filterUser->setchecked(true);}
        		
        
        // No location in request with iTop original version
        /*
        $filterLocation = new Zend_Form_Element_Select($Opref->_ParamName_UserLocation);
        $filterLocation->setLabel($this->_translate('Sites'))
        			->setRequired(false)
        			->setAttrib('class', $Opref->_ParamName_UserLocation)
        			->setAttrib('multiple','multiple');
        $filterLocation->addMultiOption('All', $this->_translate('Tous'));
       
        //TODO : attention ! pas toujours de site défini !
        if (is_array($Opref->_AlocationList)){
        foreach ($Opref->_AlocationList as $c) {
        	$filterLocation->addMultiOption($c, $c);
        	}
        }
		//on passe le tableau des valeurs 'selected' pour les présélectionner.
		if (count($Opref->_AlocationFilter)>0)
        	//{$filterLocation->setValue(explode(',',$Opref->_AlocationFilter));}
			{$filterLocation->setValue($Opref->_AlocationFilter);}
		else
        	{$filterLocation->setValue('All');}
        	
        $filterLocation->setDecorators(array(
        			'ViewHelper',
        			'Description',
        			'Errors',
        			array('Label', array('placement' => 'PREPEND', 'escape' => false)),
        			array('HtmlTag', array('tag' => 'div', 'class'=>'LocationOption'))
        	));
        */
        
        $filterYear = new Zend_Form_Element_Select($Opref->_ParamName_UserYear);
        $filterYear->setLabel($this->_translate('Années'))
        			->setRequired(false)
        			->setAttrib('class', $Opref->_ParamName_UserYear)
        			->setAttrib('multiple','multiple');
        $filterYear->addMultiOption('All', $this->_translate('Toutes'));
        if (count($Opref->_AyearList)>0){
	        foreach ($Opref->_AyearList as $c) {
	        	$filterYear->addMultiOption($c, $c);
	        }
    	}
        //on passe le tableau des valeurs 'selected' pour les présélectionner.
   		if (count($Opref->_AyearFilter)>0)
        	{$filterYear->setValue($Opref->_AyearFilter);}
        else
        	{$filterYear->setValue('All');}
        	
        $filterYear->setDecorators(array(
        		'ViewHelper',
        		'Description',
        		'Errors',
        		array('Label', array('placement' => 'PREPEND', 'escape' => false)),
        		array('HtmlTag', array('tag' => 'div', 'class'=>'YearOption'))
        		));
        
        
        /*$submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
        		->setLabel($this->_translate('Valider'));*/
        
        $this->addElements(array($filterUser,/*$filterLocation,*/$filterYear));//,$submit));
	    
	    $this->addDisplayGroup(
	        		array(
		        		$Opref->_ParamName_UserFilter,
	    			//$Opref->_ParamName_UserLocation,
	        		$Opref->_ParamName_UserYear//,
	        		//'submit'
	        		),
	        		'request',
	        		array(
	        			'legend' => $this->_translate('Filtres')
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
