<?php

namespace App\Controller;

trait TraitCommonMethods
{


    public function getTabsPrix($produit, $activeTab, $options)
    {

        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('produit_edit', ['id' => $produit->getId()])),
            'prix' => array('link' => $this->generateUrl('prix_index', ['id' => $produit->getId()])),
            'relations unités' => array('link' => $this->generateUrl('app_unite_relation_index', ['id' => $produit->getId()])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }
}
