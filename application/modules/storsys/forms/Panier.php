<?php
class Storsys_Form_Panier extends Centurion_Form {

    public function init() {
        $this->setAction('panier/update');
        $session = Zend_Registry::get('session');
        
        $panier =  $session->panier;
      
        if (isset($panier) &&  sizeof($panier->getLignes()) > 0) {
	        foreach ($panier->getLignes() as $ligne) {
	            $produit = $ligne->getProduit();
	            
	            $NbProduit = new Zend_Form_Element_Text('NbProduit'.$produit['produitId']);
	            $NbProduit->setValue($ligne->getNbProduit())
	                    ->setDecorators(array(
	                    'ViewHelper'
	                    ))
	                    ->addFilter('StripTags')
	                    ->addFilter('StringTrim')
	                    ->addValidator(new Zend_Validate_Int());
	
	            $this->addElement($NbProduit);
	        }
        }
        $recalcButton = new Zend_Form_Element_Submit('recalculer');
        $recalcButton ->setDecorators(array(
                'ViewHelper'
                ))
                ->setLabel('Recalculer le total')
                ->setAttrib('class', 'recalculer submit');
        $this->addElement($recalcButton);
    }


}

?>