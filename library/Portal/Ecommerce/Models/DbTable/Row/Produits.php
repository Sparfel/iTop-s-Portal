<?php
 class Portal_Ecommerce_Models_DbTable_Row_Produits extends Centurion_Db_Table_Row
{
    public function __toString()
    {
        return $this->produitId;
    }

}