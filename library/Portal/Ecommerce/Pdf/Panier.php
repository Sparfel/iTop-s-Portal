<?php
//nous allons étendre Zend_Pdf de manière à modifier cette classe en fonction de nos besoins
class Portal_Ecommerce_Pdf_Panier extends Zend_Pdf
{
	//redéfinition du construteur en configurant le titre et l'auteur du fichier PDF
	public function  __construct($source = null, $revision = null, $load = false)
	{
		parent::__construct($source, $revision, $load);
 
		//définition du titre et de l'auteur du fichier
		$this->properties['Title'] = "Purchase order";
		$this->properties['Author'] = "Portal iTop";
	}
}