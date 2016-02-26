<?php

class Portal_Ecommerce_Panier_Ligne {

    private $produit;
    private $nbProduit;

    public function  __construct($produitId) {
        $this->nbProduit = 0;
        $produits = new Portal_Ecommerce_Models_DbTable_Produits();
        $produit = $produits->fetchRow(array('produitId = ?'=>$produitId))->toArray();
        $this->produit = $produit;
    }

    public function ajouter($nb = 1 ) {
        $this->nbProduit += $nb;
    }
    
    public function getProduit() {
        return $this->produit;
    }

    public function setProduit($produit) {
        $this->produit = $produit;
    }

    public function getNbProduit() {
        return $this->nbProduit;
    }

    public function setNbProduit($nbProduit) {
        $this->nbProduit = $nbProduit;
    }







}

?>