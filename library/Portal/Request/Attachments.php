<?php
// Classe utilisée pour gérer les PJ de manière unitaire
class Portal_Request_Attachments {
	
	public $_ref; // Référence de l'objet auquel sont rattachées les PJ
	public $_idR; // ID de l'objet auquel sont rattachées les PJ
	public $_Aattachment; // Tableau d'Objet de type Portal_Request_Attachment
	protected $_org_id;
		
	public function __construct($idR) {
		$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
        //On récupère l'ID de l'organisation du user
		$session = new Zend_Session_Namespace('Zend_Auth');
		$this->_org_id = $session->pref->_org_id;
		$this->_idR = $idR;
		//On récupère la ou les PJ.
		$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		$listOfattachment =  $webservice->getAttachment($idR,$this->_org_id);
		$i = 0;
		if (is_array($listOfattachment))
			{if (count($listOfattachment)> 0)
				{
				foreach ($listOfattachment as $key => $attach)
					{
						$id = $attach['id'];
						$temp_id = $attach['temp_id'];
						$item_id = $attach['item_id'];
						$item_class = $attach['item_class'];
						$item_org_id = $attach['item_org_id'];
						$friendlyname = $attach['friendlyname'];
						$data = $attach['contents']['data'];
						$filename = $attach['contents']['filename'];
						$mimetype = $attach['contents']['mimetype']; 
						$Oattachment = new Portal_Request_Attachment($id, $temp_id, $item_class, $item_id, $item_org_id, $friendlyname, $mimetype, $filename, $data);
						$this->_Aattachment[$i] = $Oattachment;
						$i++;
					}
				}
			}
		//On mémorise les fichiers joints en prévision du download.
		$session->Attachments = $this;
	}
	
		
}