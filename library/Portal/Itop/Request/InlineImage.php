<?php
// Classe utilisée pour gérer les PJ de manière unitaire
class Portal_Itop_Request_InlineImage {
	
	public $_id; // Id de la pièce jointe
	public $_temp_id; // Id temporaire
	public $_item_class; // Classe de l'objet auquel est rattachée la pièce jointe
	public $_item_id; // Id de l'objet auquel est rattachée la pièce jointe
	public $_item_org_id; // Id de l'organisation
	public $_friendlyname;
	public $_mimetype; // Type Mime de la PJ
	public $_filename; //nom de la PJ
	public $_data; // contenu binaire de la PJ
	public $_filetype;
	public $_fileextension;
	public $_expire;
	protected $_contents;
	
		
	public function __construct($id, $temp_id,$expire, $item_class, $item_id, $item_org_id, $friendlyname, $mimetype,$filename, $data) {
		$this->_id = $id;
        $this->_temp_id = $temp_id;
        $this->_expire = $expire;
        $this->_item_class = $item_class;
		$this->_item_id = $item_id;
		$this->_item_org_id = $item_org_id;
		$this->_friendlyname = $friendlyname;
		$this->_mimetype = $mimetype;
		$this->_filename = $filename;
		$this->_data = $data;
		$fileinfo = explode('/',$mimetype);
		if (is_array($fileinfo)) {
			$this->_filetype = $fileinfo[0];
			$this->_fileextension = $fileinfo[1];
		}
	}
		
}