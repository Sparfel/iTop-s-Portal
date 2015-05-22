<?php

class Portal_Itop_Form_TemplateForm extends Centurion_Form
{
	// Prends un objet Syleps_iTop_Service_ServiceElementTemplate en paramètre.
	public function __construct($template,$EltSrv)
	{
		parent::__construct($template);
		
		$tab_field = array(); // Tableau d'objet des éléments du formulaire
		$i = 1;
		
		if (!(is_null($template))) {
			// la description de l'élément de service
			$EtlSrvDesc = new Zend_Form_Element_Hidden('eltsrvdesc');
			$EtlSrvDesc->setDescription($template->getParentDescription());
			$tab_field[$i]['variable'] = $EtlSrvDesc;
			$tab_field[$i]['name'] = 'eltsrvdesc';
			$i++;
		}
		else if (!(is_null($EltSrv))){
			$EtlSrvDesc = new Zend_Form_Element_Hidden('eltsrvdesc');
			$EtlSrvDesc->setDescription($EltSrv->getDescription());
			$tab_field[$i]['variable'] = $EtlSrvDesc;
			$tab_field[$i]['name'] = 'eltsrvdesc';
			$i++;
		}
				
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel($this->_translate('Title'))
				->setRequired(true)
				->setAllowEmpty(false)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty')
				->setAttrib('size','40')
				->setAttrib('required',true);
		$tab_field[$i]['variable'] = $title;
		$tab_field[$i]['name'] = 'title';
		$i++;
		
		$description = new Zend_Form_Element_Textarea('description');
		$description->setLabel($this->_translate('Description'))
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->setAttribs(array(
					'cols' => 60,
					'rows' => 7))
			->setAttrib('required',true);
		$tab_field[$i]['variable'] = $description;
		$tab_field[$i]['name'] = 'description';
		$i++;
		
		if (is_null($template)) {
			if (is_null($EltSrv)){
				$FormName =$this->_translate('Déclaration d\'incident');
				$this->setName($FormName);
			}
			else {
				$FormName = $EltSrv->getName();
				$this->setName($FormName);
			}
		}
		else {
			$FormName = $template->getParentName();
			$this->setName($FormName);

			foreach ($template->_field_list as $field) {
				switch ($field->getInputType()) {
					case 'text' : 
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Text($variable_name);
						$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required','true')->addValidator('NotEmpty');}
						//Valeur initiale
						$$variable_name->setValue($field->getInitialValue());
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = $variable_name;
						$i++;
						break;
					case 'hidden' :
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Hidden($variable_name);
						//$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						//if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required','true')->addValidator('NotEmpty');}
						//Valeur initiale
						$$variable_name->setValue($field->getInitialValue());
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = '';//$variable_name;
						$i++;
						break;
					case 'text_area' :
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Textarea($variable_name);
						$$variable_name->setAttribs(array(
								'cols' => 60,
								'rows' => 7));
						$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required',true)->addValidator('NotEmpty');}
						//Valeur initiale
						$$variable_name->setValue($field->getInitialValue());
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = $variable_name;
						$i++;
						break;
					case 'drop_down_list' :
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Select($variable_name);
						$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required',true)->addValidator('NotEmpty');}
						//Valeur initiale
						$$variable_name->setValue($field->getValues());
						$values = explode(',',$field->getValues());
						foreach ($values as $k=>$v) {
							$$variable_name->addMultiOption($k,$v);
						}
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = $variable_name;
						$i++;
						break;
					case 'date' :
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Text($variable_name);
						$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required',true)->addValidator('NotEmpty');}
						//Valeur initiale
						$$variable_name->setValue($field->getInitialValue());
						//$$variable_name->setAttrib('id', 'field_startdate')
							//->setAttrib('size', '10')
						$$variable_name->setAttrib('class', 'datepicker')->setAttrib('placeholder', 'dd-mm-yyyy');
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = $variable_name;
						$i++;
						break;
					case 'date_and_time' :
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Text($variable_name);
						$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required',true)->addValidator('NotEmpty');}
						
						//Valeur initiale
						$$variable_name->setValue($field->getInitialValue());
						//$variable_name->setAttrib('id', 'field_startdate')
						//->setAttrib('size', '10')
						$$variable_name->setAttrib('class', 'datetimepicker')->setAttrib('placeholder', 'dd-mm-yyyy hh:mm');;
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = $variable_name;
						$i++;
						break;
					case 'radio_buttons' :
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Radio($variable_name);
						$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required',true)->addValidator('NotEmpty');}
							
						//Valeur initiale
						$$variable_name->setValue($field->getInitialValue());
						$values = explode(',',$field->getValues());
						foreach ($values as $k=>$v) {
							$$variable_name->addMultiOption($k,$v);
						}
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = $variable_name;
						$i++;
						break;
					default :
						$variable_name = $field->getCode();
						$$variable_name = new Zend_Form_Element_Text($variable_name);
						$$variable_name->setLabel($this->_translate($field->getLabel()));
						//Obligatoire ou non
						if ($field->getMandatory() == 'yes') { $$variable_name->setRequired(true)->setAllowEmpty(false)->setAttrib('required',true)->addValidator('NotEmpty');}
						
						//Valeur initiale
						$$variable_name->setValue($field->getInitialValue());
					
						// on mémorise dans un tableau pour la génération et la mise en page
						$tab_field[$i]['variable'] = $$variable_name;
						$tab_field[$i]['name'] = $variable_name;
						$i++;
						break;
				}
			}
		}
		 
		// Attachment (plusieurs pièces jointes)
		$tab_files = new Zend_Form_Element_File('tab_files[]');
		$tab_files->setIsArray(true);  //Pour pouvoir gérer un tableau de pièces jointes
		$tab_files->setAttrib('id','tab_files');//->setLabel($this->_translate('Ajouter des pièce jointes'));
		$tab_field[$i]['variable'] = $tab_files;
		$tab_field[$i]['name'] = 'tab_files';
		$i++;
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
		->setLabel($this->_translate('Valider'));
		
		$tab_field[$i]['variable'] = $submit;
		$tab_field[$i]['name'] = 'submit';
		$i++;
	
		//$file->addDecorator('HtmlTag', array('tag' => 'fieldset', 'class' => 'attachfieldset'));
		$tab_files->addDecorator ( 'Fieldset', array ('legend' => $this->_translate('Ajouter des pièces jointes')) );
		 
		$j=0;
		foreach ($tab_field as $element) {
			$tab_element[$j] = $element['variable'];
			$tab_name[$j] =  $element['name'];
			$j++;
		} 
		$this->addElements($tab_element);
		$this->addDisplayGroup(
				$tab_name,
				'Servicerequest',
				array(
						//'legend' => $this->_translate('Veuillez renseigner les informations suivantes :')
						'legend' => $FormName
				)
		);
	  
		$request = $this->getDisplayGroup('Servicerequest');
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
