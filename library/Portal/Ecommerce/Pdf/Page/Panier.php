<?php
//nous allons étendre Zend_Pdf_Page de manière à modifier cette classe en fonction de nos besoins
class Portal_Ecommerce_Pdf_Page_Panier extends Zend_Pdf_Page
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
 
	//police normale
	private $_normalFont;
	//police bold
	private $_boldFont;
	
	//Prix total TTC
	private $_totalPrice;
 
	public function  __construct($param1, $param2 = null, $param3 = null)
	{
		parent::__construct($param1, $param2, $param3);
 
		//à savoir: l'origine de la page démarre en bas à gauche !!!
		$this->_yPosition = $this->getHeight() - 50;
		$this->_xPosition = 150;
		$this->_leftMargin = 35;
		$this->_rightMargin = $this->getWidth() - 50;
		
		$this->_totalPrice=0;
 
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
		$style->setLineColor(new Zend_Pdf_Color_Html('#646496'));
		//épaisseur des lignes
		$style->setLineWidth(1);
		//définition de la police
		$style->setFont($this->_normalFont, 12);
		//application du style à la page
		$this->setStyle($style);
	}
 
	//permet de définir un titre à notre page
	public function setPageTitle()
	{
		//modification de la police
		$this->setFont($this->_boldFont, 16);
		
		
		$this->setFillColor(Zend_Pdf_Color_Html::color('#646496'))
			 ->drawText("Liste des articles commandés", $this->_xPosition, $this->_yPosition, 'UTF-8');
		// On rebascule la couleur du texte en pseudo noir
		$this->setFillColor(Zend_Pdf_Color_Html::color('#333333'));		
		//déplacement du curseur vers le bas de 15 pixels
		$this->_yPosition -= 15;
		$this->drawLine($this->_leftMargin +90, $this->_yPosition, $this->leftMargin + 390, $this->_yPosition);
		//déplacement du curseur vers le bas de 50 pixels
		$this->_yPosition -= 50;
		
		$image = Zend_Pdf_Image::imageWithPath('./layouts/frontoffice/images/company_logo.png');
 		
 		$this->drawImage($image, 
 					10 , //image top left
 					$this->getHeight() - 110, // image top
 					110  , //image bottom right
 					$this->getHeight() -10);//image bottom
 					
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
	


	}
 
	//définition du footer de la page
	public function setFooter()
	{
		$this->drawText("Portal iTop", 50, 15, 'UTF-8');
		$this->drawText("Your order", $this->getWidth() - 100, 15, 'UTF-8');
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
 
	//permet d'afficher les informations d'un utilisateur
	public function addProduct($article)
	{
		$ligne_article = $article->getProduit();
		$qte = $article->getNbProduit();
		
		//modification de la police
		$this->setFont($this->_normalFont, 12);
 
 
		$this->_xPosition = $this->_leftMargin;
		$this->setFont($this->_boldFont, 12);
		$this->drawText($ligne_article['nom'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		//déplacement du curseur vers la droite de 25 pixels
		$this->_xPosition += 130;
		$this->setFont($this->_normalFont, 12);
		$this->addText($ligne_article['description'], $this->_xPosition, $this->_yPosition, 'UTF-8');
		//déplacement du curseur vers la droite de 300 pixels
		$this->_xPosition += 280;
		$this->_yPosition += 15;
		$this->drawText('Qté : '.$qte, $this->_xPosition, $this->_yPosition, 'UTF-8');
		$this->_xPosition += 50;
		
		$this->drawText($ligne_article['prix']*$qte.' €', $this->_xPosition, $this->_yPosition, 'UTF-8');
		
		//On met à jour le prix total
		$this->_totalPrice +=$ligne_article['prix']*$qte;
		
		//déplacement du curseur vers la droite de 120 pixels
		//$this->_xPosition += 120;
		
		/*$this->drawText($user->mail, $this->_xPosition, $this->_yPosition, 'UTF-8');
		//d�placement du curseur vers la droite de 120 pixels
		$this->_xPosition += 120;
		$this->drawText($user->login, $this->_xPosition, $this->_yPosition, 'UTF-8');
		//d�placement du curseur vers la droite de 75 pixels
		$this->_xPosition += 75;
		$this->drawText($user->role, $this->_xPosition, $this->_yPosition, 'UTF-8');
		//d�placement du curseur vers le bas de 15 pixels*/
		$this->_yPosition -= 15;
		
		$this->addLine();
	}
 
	//permet d'afficher un texte d'une certaine taille sur plusieurs lignes
	public function addText($text)
	{
	    //positionnement du pointeur
	    //$this->_xPosition = 50;
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
	}
 
	//permet d'ajouter une ligne horizontal
	public function addLine()
	{
		$this->drawLine($this->_leftMargin, $this->_yPosition, $this->_rightMargin, $this->_yPosition);
		//d�placement du curseur vers le bas de 15 pixels
		$this->_yPosition -= 15;
	}
	
	//Permet d'afficher le total à payer
	public function addTotal()
	{
		//On dessine l'encadrement
		$this->drawRectangle(
    				$this->_xPosition - 50, 
    				$this->_yPosition , 
    				$this->_xPosition + 70, 
    				$this->_yPosition - 20, 
    				$fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
		
		$this->_yPosition -= 15;
		$this->_xPosition -= 35;
		$this->setFont($this->_boldFont, 13);
		$this->addText('Total : '.$this->_totalPrice.' €');
		$this->setFont($this->_normalFont, 12);
		$this->_yPosition -= 15;
	}
}