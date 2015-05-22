<?php
class Request_PdfController extends Centurion_Controller_Action
{
	public function preDispatch()
	{
		$this->_helper->authCheck();
		$this->_helper->aclCheck();
		$session = new Zend_Session_Namespace('Zend_Auth');
		$this->_org_id = $session->pref->_org_id;
	}
	public function imprimerAction()
	{
		
 		$id = $this->_request->getParam('id',null);
 		
 		//On récupère les données du ticket
 		$webservice = $this->_helper->getHelper('ItopWebservice');
 		$request = $webservice->getInfoTicket($id,$this->_org_id);
 		
 		// Création du document Pdf
 		$pdf = new Syleps_Document_Pdf_Request();
 
 		
 		//céation d'une nouvelle page au format A4
		//la page est ajoutée au document pdf
		//enregistrement de la page courante dans la variable $currentPage
		$no = 1;
		$pdf->pages[] = $currentPage = new Portal_Document_Pdf_Page_Request(Zend_Pdf_Page::SIZE_A4,$request['ref'],$no);
		
		
		$currentPage->setPageTitle();
 
		
		
		$currentPage->addRequestInfo($request);
		
		//$currentPage->addText('Ticket n°'.$id.' et Organisation '.$this->_org_id);
		//$currentPage->addText($request['description']);
		
		arsort($request['public_log']['entries']);
		foreach ($request['public_log']['entries'] as $log)
		{
			/*$order   = array("\r\n", "\n", "\r");
			$replace = '<br />';
			$currentPage->addPublicLog($log['user_login'],$log['date'],str_replace($order, $replace,$log['message']));*/
			$curY = $currentPage->addPublicLog($log['user_login'],$log['date'],$log['message']);
			if ($curY < 100) {
				// Add new page
				$no++;
				$pdf->pages[] = $currentPage = new Syleps_Document_Pdf_Page_Request(Zend_Pdf_Page::SIZE_A4,$request['ref'],$no);
				$currentPage -> setPageTitle();
			}
			
		}
		
		
		
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