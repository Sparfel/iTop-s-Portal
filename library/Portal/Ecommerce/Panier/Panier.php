<?php

class Portal_Ecommerce_Panier_Panier {

    private $lignes ;
    private $total = 0;

    public function ajouterArticle($produitId) {
        if (! isset($this->lignes[$produitId])) {
            $this->lignes[$produitId] = new Portal_Ecommerce_Panier_Ligne($produitId);
        }
        $this->lignes[$produitId]->ajouter();
        $produit = $this->lignes[$produitId]->getProduit();
        $this->total += $produit['prix'];
    }
    
    public function editNbArticle($produitId, $nbProduit) {
        $produit = $this->lignes[$produitId]->getProduit();
        $nbProduitOld = $this->lignes[$produitId]->getNbProduit();
        $this->total += ($nbProduit - $nbProduitOld) * $produit['prix'];
        if ($nbProduit == 0) {
            unset ($this->lignes[$produitId]);
        }else {
            $this->lignes[$produitId]->setNbProduit($nbProduit);
        }
    }

    public function getLignes() {
        return $this->lignes;
    }

    public function setLignes($lignes) {
        $this->lignes = $lignes;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }



}

?>