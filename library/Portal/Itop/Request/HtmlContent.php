<?php
/* This class is used to modify the Html content from iTop
*	The Url included into the description and the logs are not readable
*	if you are not connected to iTop. This class will get the pictures through 
*	the webservice and insert them into the new content
*/
class Portal_Itop_Request_HtmlContent {

	protected $_html_brut_desc; //Description iTop
	protected $_html_portal_desc; //Description Portal
	protected $_Ahtml_brut_log; 
	//protected $_APicture;
	protected $_RefId;
	
	public function __construct($RequestId=null) {
		$this->_RefId = $RequestId;
	}
	
	public function generateItop2Portal($WSrequest) {
		$this->_html_brut_desc = htmlspecialchars_decode($WSrequest['description']);
		arsort($WSrequest['public_log']['entries']);
		$this->_Ahtml_brut_log = $WSrequest['public_log']['entries']; 
		//Zend_Debug::dump($this->_Ahtml_brut_log);
		$this->getArrayPic($this->_html_brut_desc);
		$this->_RefId = $WSrequest['id'];
		
		//Portal description generation
		$this->_html_portal_desc = $this->generatePortalHtml($this->_html_brut_desc,'Itop2Portal');
		//Public Log Html convertion to be displayed into the portal.
		//We add into the Ahtml_brut_log[] an entry called Ahtml_brut_log[][message_html_portal]
		for ($i = 0; $i< count($this->_Ahtml_brut_log); $i++) {
			$this->_Ahtml_brut_log[$i]['message_html_portal'] =$this->generatePortalHtml($this->_Ahtml_brut_log[$i]['message_html'],'Itop2Portal');
			
		}

	}
	
	/*
	 * This function will convert the iTop's html contents into a portal's html contents, it means we download the inline images 
	 * to display it into a web page.  
	 */
	private function generatePortalHtml($html_brut, $way){
		//First, we get the pictures
		$formated_html = $html_brut;
		//Zend_Debug::dump($formated_html);
		//We put the Inline Images into an array
		switch ($way) {
			case 'Itop2Portal' : 
				$APicture = $this->getArrayPic($html_brut);
				break;
			case 'Portal2Itop' : 
				$APicture = $this->getArrayPicFromPortal($html_brut);
				break;
			default : 
				$APicture = $this->getArrayPic($html_brut);
				break;
		}
		//Zend_Debug::dump($APicture);
		if (is_array($APicture)){
			for ($i = 0 ; $i < count($APicture); $i++) {
				switch ($way) {
					case 'Itop2Portal' : 
						$formated_html = str_replace($APicture[$i]['itop'],$APicture[$i]['portal'],$formated_html,$count);
						break;
					case 'Portal2Itop' : 
						$formated_html = str_replace($APicture[$i]['portal'],$APicture[$i]['itop'],$formated_html,$count);
						break;
					default :  
						$formated_html = str_replace($APicture[$i]['itop'],$APicture[$i]['portal'],$formated_html,$count);
						break;
				}
				/*echo 'Portal : '. $APicture[$i]['portal'];
				echo '<br>';
				echo 'iTop : '.$APicture[$i]['itop'];
				echo '<hr>';*/
			}
		}
		//Zend_Debug::dump($formated_html);
		return $formated_html;
		
	}
	
	
	/*This function will generate and return an Array from html
	 *  the array will have for each line : the iTop link / the new readable link / the id and the secret
	 */
	private function getArrayPic($html_brut){
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$nb_img = substr_count($html_brut,'<img src=');
		$Aimg = array();
		$html_read = $html_brut;
		//echo strpos($html_read, '>');
		for ($i = 0; $i < $nb_img; $i++) {
			$Aimg[$i]['itop'] = strstr(substr($html_read,strpos($html_read, '<img src=')),'>',true).'>';
			//the image's style
			$imgStyle = strstr(substr($Aimg[$i]['itop'],strpos($Aimg[$i]['itop'], 'style=')),' ',true);
			$imgStyle = substr($imgStyle,strpos($imgStyle,'"')+1, -1); // We get the Id, +1 to take off the first ", -1 to take off the last "
			$Aimg[$i]['imgStyle'] = $imgStyle;
			// THe portal display, we won't show always picture in tehir original size
			$Aimg[$i]['imgStyle_display'] = 'max-width: 450px;';
			
			//the data-img-id
			$imgId = strstr(substr($Aimg[$i]['itop'],strpos($Aimg[$i]['itop'], 'data-img-id=')),' ',true);
			$imgId = substr($imgId,strpos($imgId,'"')+1, -1); // We get the Id, +1 to take off the first ", -1 to take off the last "
			$Aimg[$i]['imgId'] = $imgId;
			//the data-img-secret
			$imgSecret = strstr(substr($Aimg[$i]['itop'],strpos($Aimg[$i]['itop'], 'data-img-secret=')),'>',true);
			$imgSecret = substr($imgSecret,strpos($imgSecret,'"')+1, -1); // We get the Id, +1 to take off the first ", -1 to take off the last "
			$Aimg[$i]['imgSecret'] = $imgSecret;
			
			//Now we have to generate the new link which will display the picture on the portal
			// We use a controller action to do this !
			$img_portal_link = $view->url(array('action' => 'displayimage','id'=>$this->_RefId,'secret'=>$Aimg[$i]['imgSecret']),null,false);
			$Aimg[$i]['portal'] = '<img src="'.$img_portal_link.'" style="'.$Aimg[$i]['imgStyle_display'].'" class="inline-image" href="'.$img_portal_link.'">';
 			
			// we get the rest of the string to analyze
			$html_read = substr($html_read,strpos($html_read, $Aimg[$i]['itop'])+ strlen($Aimg[$i]['itop']));
			
		}
		return $Aimg;
		
	}
	
	public function getHtmlBrut(){
		return $this->_html_brut_desc;
	}
	
	public function getHtmlDescPortal(){
		return $this->_html_portal_desc;
	}
	
	public function getHtmlLogPortal(){
		return $this->_Ahtml_brut_log;
	}
	
	
	public function getAPic(){
		$this->getArrayPic($this->_html_brut_desc);
	}
	
	//Here we push html from Portal to iTop
	public function generatePortal2Itop($html_portal){
		$result = $this->generatePortalHtml($html_portal,'Portal2Itop');
		//Zend_Debug::dump($result);
		return $result;
		
	}
	
	//We give in input the Html from the portal
	//THIS FUNCTION WILL INSERT INLINE IMAGE INTO ITOP
	private function getArrayPicFromPortal($html_brut){
		$webService =Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		//$front = Zend_Controller_Front::getInstance();
		if (null === $viewRenderer->view) {
			$viewRenderer->initView();
		}
		$view = $viewRenderer->view;
		$nb_img = substr_count($html_brut,'<img ');
		$Aimg = array();
		$html_read = $html_brut;
		//echo strpos($html_read, '>');
		for ($i = 0; $i < $nb_img; $i++) {
			$Aimg[$i]['portal'] = strstr(substr($html_read,strpos($html_read, '<img ')),'>',true).'>';
			//the image's style
			$imgStyle = strstr(substr($Aimg[$i]['portal'],strpos($Aimg[$i]['portal'], 'style=')),'" /',true);
			$imgStyle = substr($imgStyle,strpos($imgStyle,'"')+1, -1); // We get the Id, +1 to take off the first ", -1 to take off the last "
			$Aimg[$i]['imgStyle'] = $imgStyle;
			
			//We sent now the picture to iTop and get back the secret Id !
			//First we get the image
			$image = strstr(substr($Aimg[$i]['portal'],strpos($Aimg[$i]['portal'], 'src="')+5),'" ',true);
			$image_link = 'http://' . $_SERVER['SERVER_NAME'].$image;
			$ApathImg = explode('/',$image);
			
			$image_name = $ApathImg[count($ApathImg)-1];
			$image_type = image_type_to_mime_type(exif_imagetype($image_link));
			$fileData =file_get_contents($image_link);
			$fileData = base64_encode($fileData);
			
			$item_class = 'UserRequest';
			$session = new Zend_Session_Namespace('Zend_Auth');
			$org_id = $session->pref->_org_id;
			
			//We add the image into iTop (Objet inlineImage)
			//We generate a secret Id for the picture
			$secret = dechex(rand (1,99999999));
			$InlineImage_id = $webService->AddInlineImage($image_name,$fileData,$item_class,$this->_RefId,$image_type,$org_id,$secret);
			
			//We build the iTop link for the picture 
			$Aimg[$i]['itop'] = '<img src="'.$webService->getItopUrl().'/pages/ajax.document.php?operation=download_inlineimage&id='.$InlineImage_id.'&s='.$secret.'">';
			
			// we get the rest of the string to analyze
			$html_read = substr($html_read,strpos($html_read, $Aimg[$i]['portal'])+ strlen($Aimg[$i]['portal']));
				
		}
		//Zend_Debug::dump($Aimg);
		return $Aimg;
	
	}
	
	
}