<?php
//nous allons étendre Zend_Pdf_Page de manière à modifier cette classe en fonction de nos besoins
class Portal_Document_Pdf_Page_Request extends Zend_Pdf_Page
{
	//variables de classe
	//position x du curseur
	private $_yPosition;
	//position y du curseur
	private $_xPosition;
	//marge à gauche
	private $_leftMargin;
	//marge à droite
	private $_rightMargin;
	//décalage du contenu % au libellé pour la première colone
	private $_xDecalageContenuCol1 = 65;
	//décalage du contenu % au libellé pour la seconde colone
	private $_xDecalageContenuCol2 = 65;
	//marge à gauche de la 2nde colonne
	private $_leftMarginCol2 = 350;
	
	//police normale
	private $_normalFont;
	//police bold
	private $_boldFont;
	
	//N° de page
	private $_no;
	
	//Id de la Requête
	private $_ref;
 
	public function  __construct($param1, $request_ref, $no)
	{
		$param2 = null;
		$param3 = null;
		parent::__construct($param1, $param2, $param3);
 
		//à savoir: l'origine de la page démarre en bas à gauche !!!
		$this->_yPosition = $this->getHeight() - 50;
		$this->_xPosition = 150;
		$this->_leftMargin = 35;
		$this->_rightMargin = $this->getWidth() - 50;
		
		$this->_ref = $request_ref;
		$this->_no = $no;
 
		//définition des polices qui seront utilisées sur la page
		$this->_normalFont = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
		$this->_boldFont = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
 
		$this->createStyle();
		$this->setFooter();
	}
 
	public function createStyle()
	{
		//création d'un style pour la page
		$style = new Zend_Pdf_Style();
		//couleur du texte
		$style->setFillColor(new Zend_Pdf_Color_Html('#333333'));
		//couleur des lignes
		$style->setLineColor(new Zend_Pdf_Color_Html('#1C94C4'));
		//épaisseur des lignes
		$style->setLineWidth(1);
		//définition de la police
		$style->setFont($this->_normalFont, 12);
		//application du style à la page
		$this->setStyle($style);
	}
	
	public function createStyle2()
	{
		$style = new Zend_Pdf_Style();
		//couleur des lignes
		$style->setLineColor(new Zend_Pdf_Color_Html('gray'));
		//épaisseur des lignes
		$style->setLineWidth(0.5);
		//application du style à la page
		$this->setStyle($style);
	}
 
	//permet de définir un titre à notre page
	public function setPageTitle()
	{
		//modification de la police
		$this->setFont($this->_boldFont, 16);
		
		$TxtTitle = 'Détail de l\'incident '.$this->_ref;
		
		$this->setFillColor(Zend_Pdf_Color_Html::color('#1C94C4'))
			 ->drawText($TxtTitle, $this->_xPosition, $this->_yPosition, 'UTF-8');
		// On rebascule la couleur du texte en pseudo noir
		$this->setFillColor(Zend_Pdf_Color_Html::color('#333333'));		
		//déplacement du curseur vers le bas de 15 pixels
		$this->_yPosition -= 15;
		$this->drawLine($this->_leftMargin +90, $this->_yPosition, $this->_leftMargin + 390, $this->_yPosition);
		//déplacement du curseur vers le bas de 50 pixels
		$this->_yPosition -= 50;
		
		$image = Zend_Pdf_Image::imageWithPath('./layouts/frontoffice/images/company_logo.png');
 		
		$this->drawImage($image,
				10 , //image top left
				$this->getHeight() - 38, // image top
				120  , //image bottom right
				$this->getHeight() - 10);//image bottom
 		
		
		$image2 = Zend_Pdf_Image::imageWithPath('./layouts/frontoffice/images/itop-logo-external.png');
		$this->drawImage($image2,
				$this->getWidth()- 125 , //image top left
				$this->getHeight() - 35, // image top
				$this->getWidth()- 27  , //image bottom right
				$this->getHeight() - 17);//image bottom

		
		/* GESTION DU CB, mais on s'en fout ici
		// Only the text to draw is required
		$barcodeOptions = array('text' => '1234');
		// No required options
		$rendererOptions = array();
		// Draw the barcode in a new image,
		$imageResource = Zend_Barcode::draw(
		    'code128', 'image', $barcodeOptions, $rendererOptions
			);
		imagejpeg($imageResource, 'barcode.jpg', 100);
		// Free up memory
		imagedestroy($imageResource);
		$image = Zend_Pdf_Image::imageWithPath('barcode.jpg');
		$this->drawImage($image,
					$this->getWidth()- 150, //image top left
 					$this->getHeight() - 60, // image top
 					$this->getWidth() -20, //image bottom right
 					$this->getHeight() -10);//image bottom
		*/


	}
 
	//définition du footer de la page
	public function setFooter()
	{   
		$this->setFont($this->_normalFont, 8);
		$this->drawText('services.syleps.fr', $this->_leftMargin, 15, 'UTF-8');
		$this->drawText(' - '.$this->_no.' - ', $this->getWidth() / 2, 15, 'UTF-8');
		$this->drawText('Ticket Incident '.$this->_ref, $this->getWidth() - 100, 15, 'UTF-8');
	}
	
	
	
 
	//permet de vérifier la position du curseur
	//de manièreà savoir si nous pouvons continuer à écrire sur la page
	public function checkPosition()
	{
		//s'il reste plus de 75 pixels, nous pouvons contnuer à écrire
		//sinon il n'est plus possible d'écrire
		if($this->_yPosition > 75)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
 
	//permet d'afficher les informations d'entête d'un ticket
	public function addRequestInfo($request)
	{
		$yInitial = $this->_yPosition;
		$xMax =  $this->_xPosition;
		
		/* 1ere ligne */
		//1ere colonne, 1ere ligne
		$this->_xPosition = $this->_leftMargin;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Référence : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol1;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['ref'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		$this->_yPosition -= 15;
		//1ere colonne, 2eme ligne
		$this->_xPosition = $this->_leftMargin;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Appelant : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol1;;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['caller_id_friendlyname'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		/* 3ème ligne */
		$this->_yPosition -= 15;
		//1ere colonne, 3ere ligne
		$this->_xPosition = $this->_leftMargin;
		//libellé
		/*$this->setFont($this->_boldFont, 10);
		$this->drawText('Site : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol1;;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['site_name'], $this->_xPosition, $this->_yPosition, 'UTF-8');*/
		/* 4ème ligne */
		$this->_yPosition -= 15;
		//1ere colonne, 4ere ligne
		$this->_xPosition = $this->_leftMargin;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Service : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol1;;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['service_name'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		/* 5ème ligne */
		$this->_yPosition -= 15;
		$curY = $this->_yPosition;
		//1ere colonne, 5eme ligne
		$this->_xPosition = $this->_leftMargin;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Titre : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol1;;
		$this->setFont($this->_normalFont, 10);
		$this->addColText($request['title'],1);
		
		/* 6ème ligne */
		//$this->_yPosition -= 15;
		$curY = $this->_yPosition;
		//1ere colonne, 5eme ligne
		$this->_xPosition = $this->_leftMargin;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Description : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol1;
		$this->setFont($this->_normalFont, 10);
		$this->addColText($request['description'],1);
		
		$yMinCol1 =  $this->_yPosition;
		
		/*Changement de colonne */
		//2nde Colonne
		$this->_yPosition = $yInitial;
		$this->_xPosition = $this->_leftMarginCol2;
		
		
		//2ere colonne, 1ere ligne
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Status : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol2;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['status'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		$this->_yPosition -= 15;
		if ($request['status'] == 'pending') {
			//2ere colonne, 6eme ligne
			$this->_yPosition -= 15;
			$this->_xPosition = $this->_leftMarginCol2;
			//libellé
			$this->setFont($this->_boldFont, 10);
			$this->drawText('Raison : ', $this->_xPosition,  $this->_yPosition, 'UTF-8');
			//Contenu
			$this->_xPosition += $this->_xDecalageContenuCol2;
			$this->setFont($this->_normalFont, 10);
			$this->addColText($request['pending_reason'],2);
		}
		/* 2nde ligne */
		//2ere colonne, 2ere ligne
		//$this->_yPosition -= 15;
		$this->_xPosition = $this->_leftMarginCol2;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Priorité : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol2;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['priority'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		//2ere colonne, 3ere ligne
		$this->_yPosition -= 15;
		$this->_xPosition = $this->_leftMarginCol2;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Type : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol2;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['request_type'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		//2ere colonne, 4ere ligne
		$this->_yPosition -= 15;
		$this->_xPosition = $this->_leftMarginCol2;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Ticket chaud : ', $this->_xPosition, $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol2;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['escalation_flag'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		//2ere colonne, 5eme ligne
		$this->_yPosition -= 15;
		$this->_xPosition = $this->_leftMarginCol2;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Date de début : ', $this->_xPosition,  $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol2 + 10;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['start_date'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		//2ere colonne, 6eme ligne
		$this->_yPosition -= 15;
		$this->_xPosition = $this->_leftMarginCol2;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Dernière MAJ : ', $this->_xPosition,  $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol2 + 10;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['last_update'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		if ($request['status'] == 'resolved') {
			//2ere colonne, 6eme ligne
			$this->_yPosition -= 15;
			$this->_xPosition = $this->_leftMarginCol2;
			//libellé
			$this->setFont($this->_boldFont, 10);
			$this->drawText('Date résolution : ', $this->_xPosition,  $this->_yPosition, 'UTF-8');
			//Contenu
			$this->_xPosition += $this->_xDecalageContenuCol2;
			$this->setFont($this->_normalFont, 10);
			$this->drawText($request['resolution_date'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		}
		//2ere colonne, eme ligne
		$this->_yPosition -= 15;
		$this->_xPosition = $this->_leftMarginCol2;
		//libellé
		$this->setFont($this->_boldFont, 10);
		$this->drawText('Agent : ', $this->_xPosition,  $this->_yPosition, 'UTF-8');
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol2;
		$this->setFont($this->_normalFont, 10);
		$this->drawText($request['agent_id_friendlyname'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		
		$this->_yPosition -= 15;
		$yMinCol2 =  $this->_yPosition;
		
		//Le Y=0 est en bas de la page, on dmiminue Y à chaque ligne ajouté, on prend donc le min
		$this->_yPosition = min($yMinCol1,$yMinCol2);
		
		//$this->_yPosition -= 15;
	
		$this->addLine();
		
		
		//$this->drawRectangle(440,517 , 540, 622);
	}
	
	//permet d'afficher les informations d'entête d'un ticket
	public function addPublicLog($user,$date,$log)
	{
		
		$this->_xPosition = $this->_leftMargin;
		//Entete
		//on dessine une puce
		$this->drawRectangle($this->_xPosition +1,$this->_yPosition+2 , $this->_xPosition+3, $this->_yPosition+4);
		$this->_xPosition +=5;
		$this->setFont($this->_boldFont, 10);
		$this->drawText($user.' a écrit le '.$date, $this->_xPosition, $this->_yPosition, 'UTF-8');
		$this->_yPosition -= 15;
		$this->addLineType2();
		//Contenu
		$this->_xPosition += $this->_xDecalageContenuCol1;
		$this->setFont($this->_normalFont, 10);
		$this->addText($log);
		
		$this->addLine();
		
		//On renvoie le Y pour savoir si on change de page ou pas
		return $this->_yPosition;
		
	}
	
	
	//permet d'afficher un texte d'une certaine taille sur plusieurs lignes
	public function addColText($text, $col)
	{
		//$yPosition0 = $this->_yPosition;
		//$xPosition0 = $this->_xPosition;
		//modification de la police
		$this->setFont($this->_normalFont, 10);
	
		//positionnement du pointeur
		if ($col == 2){
			$this->_xPosition = $this->_leftMarginCol2 +  $this->_xDecalageContenuCol2;
		}
		else {
			$this->_xPosition = $this->_leftMargin + $this->_xDecalageContenuCol1;
		}
		//permet d'effectuer la césure d'une chaîne de caractère
		//retourne le texte après avoir inséré\n tous les 95 caractères sans couper un mot
		$textWrapped = wordwrap($text, 50, "\n", false);
	
		//permet de couper une cha�ne en segment
		//chaque segment est ici d�limit� par \n
		//� noter que deux arguments doivent �tre fournis lors du premier appel � la fonction strtok
		//lors des appels suivant � strtok, seul le d�limiteur sera indiqu�
		//strtok retourne false lorsque la cha�ne est vide
		$token = strtok($textWrapped, "\n");
	
		//tant que la cha�ne n'est pas vide
		while($token !== false)
		{
			//ajoute le texte � notre page � la position x et y
			$this->drawText($token, $this->_xPosition, $this->_yPosition, 'UTF-8');
			//strtok a d�j� �t� appel� une premi�re fois,
			//nous pouvons donc maintenant indiquer uniquement le d�limiteur
			$token = strtok("\n");
			//modification de la valeur de y pour la prochaine �criture
			$this->_yPosition -= 15;
		}
		//On remet Y comme il était
		//$this->_yPosition = $yPosition0;
		//$this->_xPosition = $xPosition0;
	}
 
	//permet d'afficher un texte d'une certaine taille sur plusieurs lignes
	public function addText($text)
	{
		//modification de la police
		$this->setFont($this->_normalFont, 10);
		
	    //positionnement du pointeur
	    $this->_xPosition = $this->_leftMargin;
		//permet d'effectuer la césure d'une chaîne de caractère
		//retourne le texte après avoir inséré\n tous les 95 caractères sans couper un mot
		$textWrapped = wordwrap($text, 120, "\n", false);
 
		//permet de couper une cha�ne en segment
		//chaque segment est ici d�limit� par \n
		//� noter que deux arguments doivent �tre fournis lors du premier appel � la fonction strtok
		//lors des appels suivant � strtok, seul le d�limiteur sera indiqu�
		//strtok retourne false lorsque la cha�ne est vide
		$token = strtok($textWrapped, "\n");
 
		//tant que la cha�ne n'est pas vide
		while($token !== false)
		{
			//ajoute le texte � notre page � la position x et y
			$this->drawText($token, $this->_xPosition, $this->_yPosition, 'UTF-8');
			//strtok a d�j� �t� appel� une premi�re fois,
			//nous pouvons donc maintenant indiquer uniquement le d�limiteur
			$token = strtok("\n");
			//modification de la valeur de y pour la prochaine �criture
			$this->_yPosition -= 15;
		}
	}
 
	//permet d'ajouter une ligne horizontal
	public function addLine()
	{
		$this->drawLine($this->_leftMargin, $this->_yPosition, $this->_rightMargin, $this->_yPosition);
		//d�placement du curseur vers le bas de 15 pixels
		$this->_yPosition -= 15;
	}
	
	
	//permet d'ajouter une ligne horizontal
	public function addLineType2()
	{
		$this->createStyle2();
		$this->drawLine($this->_leftMargin, $this->_yPosition, $this->_rightMargin, $this->_yPosition);
		//d�placement du curseur vers le bas de 15 pixels
		$this->_yPosition -= 15;
		
		//On remet le style comme avant
		$this->createStyle();
		
	}
	

}