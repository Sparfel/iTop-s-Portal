<?php
class Storsys_PdfController extends Zend_Controller_Action
{
	public function preDispatch()
	{
		//$this->_helper->authCheck();
		//$this->_helper->aclCheck();
		$session = new Zend_Session_Namespace('Zend_Auth');
		$this->_org_id = $session->org_id;
	}
	public function listAction()
	{
		//récupération des utilisateurs
		//$users = new Media_Model_DbTable_Books();
		//$data = $users->fetchAll();
 
 		//Récupération des produits
 		$session = Zend_Registry::get('session');
 		$data = $session->panier;
 		$Panier = $data->getLignes();
 		
 		// Création du document Pdf
 		$pdf = new Syleps_Ecommerce_Pdf_Panier();
 
 		//cr�ation d document PDF
		//$pdf = new My_Pdf_Users();       
 
		//céation d'une nouvelle page au format A4
		//la page est ajoutée au document pdf
		//enregistrement de la page courante dans la variable $currentPage
		$pdf->pages[] = $currentPage = new Syleps_Ecommerce_Pdf_Page_Panier(Zend_Pdf_Page::SIZE_A4);
 
		$currentPage->setPageTitle();
 
		//la boucle va permettre l'affichage de chaque utilisateur
		foreach($Panier as $article)
		{
			
			//vérification de la position du curseur sur la page
			//s'il ne reste plus suffisamment de place sur la page courante pour écrire,
			//nous devrons ajouter une page à notre document PDF
			if(!$currentPage->checkPosition())
			{
				//cr�ation d'une nouvelle page et modification de la page courante
				$pdf->pages[] = $currentPage = new My_Pdf_Page_Users(Zend_Pdf_Page::SIZE_A4);
			}
 
			//ajout dde l'uilisateur
			$currentPage->addProduct($article);
		}
 		$currentPage->addTotal();
		//permet de spécifier l'en-tête HTTP
		header('Content-Type: application/pdf; charset=UTF-8');
 
		//affichage de notre PDF
		echo $pdf->render();
 
		//comme l'action affiche un PDF, nous allons d�sactiver l'affichage de la vue et du layout
		//permet de d�sactiver l'affichage de la vue de l'action list
		$this->_helper->viewRenderer->setNoRender(true);
		//permet de d�sactiver l'affichage du layout
		$this->_helper->layout->disableLayout();
	}
}