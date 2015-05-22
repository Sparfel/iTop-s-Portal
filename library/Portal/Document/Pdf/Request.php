<?php
//nous allons étendre Zend_Pdf de manière à modifier cette classe en fonction de nos besoins
class Portal_Document_Pdf_Request extends Zend_Pdf
{
	//redéfinition du construteur en configurant le titre et l'auteur du fichier PDF
	public function  __construct($source = null, $revision = null, $load = false)
	{
		parent::__construct($source, $revision, $load);
 
		//définition du titre et de l'auteur du fichier
		$this->properties['Title'] = "Détail de l'incident";
		$this->properties['Author'] = "Portail Helpdesk iTop";
	}
}