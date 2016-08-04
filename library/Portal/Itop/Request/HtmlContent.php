<?php
/* This class is used to modify the Html content from iTop
*	The Url included into the description and the logs are not readable
*	if you are not connected to iTop. This class will get the pictures through 
*	the webservice and insert them into the new content
*/
class Portal_Itop_Request_HtmlContent {

	protected $_html_brut_desc;
	protected $_Ahtml_brut_log; 
	
	public function __construct($WSrequest) {
		$this->_html_brut_desc = htmlspecialchars_decode($WSrequest['description']);
		$this->_Ahtml_brut_log = arsort($WSrequest['public_log']['entries']);
		
		
	}

	/*This function will generate and return an Array from html
	 *  the array will have for each line : the iTop link / the new readable link / the id and the secret
	 */
	private function getArrayPic($html_brut){
		$nb_img = substr_count($html_brut,'<img src=');
		$Aimg = array();
		$html_read = $html_brut;
		echo strpos($html_read, '>');
		for ($i = 0; $i < $nb_img; $i++) {
			$Aimg[$i]['itop'] = strstr(substr($html_read,strpos($html_read, '<img src=')),'>',true).'>';
			//the image's style
			$imgStyle = strstr(substr($Aimg[$i]['itop'],strpos($Aimg[$i]['itop'], 'style=')),' ',true);
			$imgStyle = substr($imgStyle,strpos($imgStyle,'"')+1, -1); // We get the Id, +1 to take off the first ", -1 to take off the last "
			$Aimg[$i]['imgStyle'] = $imgStyle;
			//the data-img-id
			$imgId = strstr(substr($Aimg[$i]['itop'],strpos($Aimg[$i]['itop'], 'data-img-id=')),' ',true);
			$imgId = substr($imgId,strpos($imgId,'"')+1, -1); // We get the Id, +1 to take off the first ", -1 to take off the last "
			$Aimg[$i]['imgId'] = $imgId;
			//the data-img-secret
			$imgSecret = strstr(substr($Aimg[$i]['itop'],strpos($Aimg[$i]['itop'], 'data-img-secret=')),'>',true);
			$imgSecret = substr($imgSecret,strpos($imgSecret,'"')+1, -1); // We get the Id, +1 to take off the first ", -1 to take off the last "
			$Aimg[$i]['imgSecret'] = $imgSecret;
			
			//Now we have to generate the new link which will disopaly the picture on the portal
			// We use a controller action to do this !
			
			// we get the rest of the string to analyze
			$html_read = substr($html_read,strpos($html_read, $Aimg[$i])+ strlen($Aimg[$i]));
		}
		
	}
	
	
	public function nfo() {
		
		$html_brut = htmlspecialchars_decode($data['description']);
		$nb_img = substr_count($html_brut,'<img src=');
		echo 'Il y a '.$nb_img.' images.<br>';
		
		$Aimg = array();
		$html_read = $html_brut;
		echo strpos($html_read, '>');
		for ($i = 0; $i < $nb_img; $i++) {
		
			$Aimg[$i] = strstr(substr($html_read,strpos($html_read, '<img src=')),'>',true).'>';
			$html_read = substr($html_read,strpos($html_read, $Aimg[$i])+ strlen($Aimg[$i]));
			//$html_read = strstr($html_read,$Aimg[$i]);
			//echo strpos($html_read, '>').'<br>';
		
		}
		
		
		/*echo strpos($html_brut, '<img src=');
		 echo '<br>';
		$image =  'image : '.strstr(substr($html_brut,strpos($html_brut, '<img src=')),'>',true);*/
		Zend_Debug::dump($Aimg);
	}
	
}