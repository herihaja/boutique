<?php

namespace App\Controller;

trait TraitCommonMethods
{


    function getTabsOrganisation($organisation, $activeTab, $params)
    {
        $page = isset($params['page']) ? $params['page'] : false;
        $projet = isset($params['projet']) ? $params['projet'] : false;
        $negociation = isset($params['negociation']) ? $params['negociation'] : false;

        $tabnav = array(
            'info. générale' => array('link' => $this->generateUrl("organisation_edit", array('id' => $organisation->getid()))),
            'projets' => array('link' => $this->generateUrl("organisation_projets", array('id' => $organisation->getid())))
        );
        if ($projet) {
            $tabnav['negociations'] = array('link' => $this->generateUrl("negociation_index", array('projet' => $projet->getid())));
        }
        if ($negociation)
            $tabnav['documents'] = array('link' => $this->generateUrl("documents_index", array('id' => $negociation->getid(), 'entity' => 'Negociation')));

        /*  ******  Uncomment these later on ***********
        $tabnav = array_merge($tabnav, array('traitement de dossier' => array('link' => $this->generateUrl("organisation_avancement", array('id'=>$organisation->getid()))),
             'historique' => array('link' => $this->generateUrl("organisation_historique", array('id'=>$organisation->getid()))),
        ));
        
        $tabnav['labellisations'] = array('link' => $this->generateUrl("labelisation_index", array('id' => $organisation->getid())));
        $tabnav['décisions'] = array('link' => $this->generateUrl("resultat_evaluation_decision_labelisation_index", array('id' => $organisation->getid())));
         * 
         */

        foreach ($tabnav as $key => $tab) {
            if ($key == $activeTab)
                $tabnav[$key]['active_class'] = 'active';
        }
        return $tabnav;
    }

    function getBreadcrumbVariable($params)
    {
        $organisation = isset($params['organisation']) ? $params['organisation'] : false;
        $projet = isset($params['projet']) ? $params['projet'] : false;
        $page = isset($params['page']) ? $params['page'] : false;
        $negociation = isset($params['negociation']) ? $params['negociation'] : false;

        $breadcrumb = array(
            'main' => 'Ressources et Supports',
            $this->generateUrl('organisation_index') => 'Organisations',
        );
        if ($organisation) {
            $breadcrumb[$this->generateUrl('organisation_edit', array('id' => $organisation->getId()))] = $organisation->getName();
        }

        if ($projet || in_array($page, array("projet_index", "projet_add"))) {
            $breadcrumb[$this->generateUrl('organisation_projets', array('id' => $organisation->getId()))] = 'Projets';

            if ($projet)
                $breadcrumb[$this->generateUrl('projet_edit', array('id' => $organisation->getId(), 'projet_id' => $projet->getId()))] = $projet->getIntitule();

            if ($page == "projet_add")
                $breadcrumb['no-link'] = "Ajouter un projet";
        }

        if ($negociation || $page == "negociation_index") {
            $breadcrumb[$this->generateUrl('negociation_index', array('projet' => $projet->getId()))] = 'Negociations';
            if ($negociation)
                $breadcrumb[$this->generateUrl('negociation_edit', array('id' => $negociation->getId()))] = $negociation;
        }

        return $breadcrumb;
    }

    function getTabsCtd($rc, $activeTab, $options)
    {
        $nomination = isset($options['nomination']) ? $options['nomination'] : false;
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('localite_edit', array('id' => $rc->getId(), 'type' => 'CTD'))),
            'nominations' => array('link' => $this->generateUrl('nomination_index', array('id' => $rc->getId()))),
        );
        if ($nomination) {
            $tabs['documents'] = array('link' => $this->generateUrl('documents_index', array('id' => $nomination->getId(), 'entity' => 'Nomination')));
        }
        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }


    function getTabsRH($rc, $activeTab, $options)
    {
        $tabs = array(
            'fiche' => array('link' => $this->generateUrl('ressource_humaine_edit', array('id' => $rc->getId()))),
            'educations' => array('link' => $this->generateUrl('education_index', array('id' => $rc->getPersonne()->getId()))),
            'expériences' => array('link' => $this->generateUrl('experience_index', array('id' => $rc->getPersonne()->getId()))),
            'certifications' => array('link' => $this->generateUrl('certification_index', array('id' => $rc->getId()))),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    function getBreadCrumbRH($ressourceHumaine, $action, $params)
    {
        $education = isset($params["education"]) ? $params["education"] : false;
        $experience = isset($params["experience"]) ? $params["experience"] : false;
        $personne = $ressourceHumaine->getPersonne();
        $breadcrumb = array(
            "main" => "Bénéficiaires",
            $this->generateUrl("ressource_humaine_index") => "Ressources humaine",
            $this->generateUrl("ressource_humaine_edit", array("id" => $ressourceHumaine->getId())) => $ressourceHumaine,

        );

        $arrayTitrePrefix = array("new" => "Ajouter", "edit" => "Modifier");

        if ($education) {
            $breadcrumb[$this->generateUrl("education_index", array("id" => $personne->getId()))] = "Liste des éducations";
            if (in_array($action, ["new", "edit"]))
                $breadcrumb["no-link"] = $arrayTitrePrefix[$action] . " une éducation";
        }

        if ($experience) {
            $breadcrumb[$this->generateUrl("experience_index", array("id" => $personne->getId()))] = "Liste des expériences";
            if (in_array($action, ["new", "edit"]))
                $breadcrumb["no-link"] = $arrayTitrePrefix[$action] . " une expérience";
        }

        return $breadcrumb;
    }

    public function getOptionsPersonne($personne, $entity, $action)
    {
        if ($personne->getAgent()) {
            $agent = $personne->getAgent();
            $tabs = $this->getTabsAgent($agent, $entity . "s", array());
            $options = array("agent" => $agent, "personne" => $personne);
        } elseif ($personne->getCollaborateur()) {
            $collaborateur = $personne->getCollaborateur();
            $breadcrumb = $this->getBreadcrumbVariableCollaborateur(array('collaborateur' => $collaborateur, 'page' => $entity . '_' . $action));
            $tabs = $this->getTabNavVariableCollaborateur($collaborateur, $entity . 's', array('collaborateur' => $collaborateur));
            $options = array("collaborateur" => $collaborateur, "personne" => $personne);
        } elseif ($personne->getRessourceHumaine()) {
            $ressourceHumaine = $personne->getRessourceHumaine();
            $activeTab = ($entity !== "experience") ? $entity : "expérience";
            $tabs = $this->getTabsRH($ressourceHumaine, $activeTab . "s", array());
            $options = array("ressource_humaine" => $ressourceHumaine, "personne" => $personne);
            $breadcrumb = $this->getBreadcrumbRH($ressourceHumaine, $action, array($entity => true));
        } else {
            $options = array();
        }

        $options["tabs"] = $tabs;
        $options["breadcrumb"] = $breadcrumb;
        return $options;
    }

    public function getTabsCompteAdmin($rc, $activeTab, $options)
    {
        $tabs = array(
            'fiche' => array('link' => $this->generateUrl('compte_administratif_edit', array('id' => $rc->getId()))),
            'details' => array('link' => $this->generateUrl('compte_administratif_detail_index', array('id' => $rc->getId()))),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    private function getTabsRcRef($rc, $activeTab)
    {
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('renforcement_capacites_referentiel_edit', array('id' => $rc->getId()))),
            'documents' => array('link' => $this->generateUrl('documents_index', array('id' => $rc->getId(), 'entity' => 'RenforcementCapacitesReferentiel'))),
            'modules' => array('link' => $this->generateUrl('rc_modules', array('id' => $rc->getId()))),
        );
        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }


    public function getTabsModule($module, $activeTab, $options)
    {

        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('module_edit', array('id' => $module->getId()))),
            'documents' => array('link' => $this->generateUrl('documents_index', array('id' => $module->getId(), 'entity' => 'Module'))),
            //'themes de formation' => array('link' => $this->generateUrl('module_themes', array('id'=> $module->getId()))),
            'agenda' => array('link' => $this->generateUrl('module_agenda_index', array('id' => $module->getId()))),
        );
        //if ($analyse)
        //    $tabs["propositions technique"] = array('link' => $this->generateUrl('proposition_technique_index', array('analyse'=> $analyse->getId())));

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    function toUrls($breadcrumb)
    {
        if (isset($breadcrumb["main"]))
            $urls = ["main" => $breadcrumb["main"]];
        foreach ($breadcrumb["urls"] as $route => $params) {
            $label = $params["label"];
            unset($params["label"]);
            $urls[$this->generateUrl($route, $params)] = $label;
        }
        if (isset($breadcrumb["titre"]))
            $urls["no-link"] = $breadcrumb["titre"];

        return $urls;
    }

    function toTabs($result)
    {
        $method = $result["method"];

        return $this->$method($result["entity"], $result["activeTab"], $result["options"]);
    }

    public function getTabsPublication($rc, $activeTab, $options)
    {
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('publication_edit', array('id' => $rc->getId()))),
            'documents' => array('link' => $this->generateUrl('documents_index', array('id' => $rc->getId(), 'entity' => 'Publication'))),
            //'historique' => array('link' => $this->generateUrl('prevision_realisation_index', array('rc'=> $rc->getId()))),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }


    public function getTabsCertification($rc, $activeTab, $options)
    {
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('certification_edit', array('id' => $rc->getId()))),
            //'documents' => array('link' => $this->generateUrl('documents_index', array('id'=> $rc->getId(), 'entity' => 'Publication'))),
            'formation et accompagnement' => array('link' => $this->generateUrl('resultat_decision_renforcement_capacites', array('id' => $rc->getId()))),
            'ressources humaines' => array('link' => $this->generateUrl('certification_rh_index', array('id' => $rc->getId()))),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsUser($user, $activeTab, $options)
    {

        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('auth_user_edit', ['id' => $user->getId()])),
            'profiles' => array('link' => $this->generateUrl('auth_permission_liste', ['id' => $user->getId(), 'entity' => 'user'])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsGroup($groupe, $activeTab, $options)
    {
        $user = isset($options['user']) ? $options['user'] : false;
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('auth_group_edit', ['id' => $groupe->getId()])),
            'profiles' => array('link' => $this->generateUrl('auth_permission_liste', ['id' => $groupe->getId(), 'entity' => 'group'])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsThemeFormation($module, $activeTab, $options)
    {
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('theme_formation_edit', array('id' => $module->getId()))),
            //'documents' => array('link' => $this->generateUrl('documents_index', array('id'=> $module->getId(), 'entity' => 'Module'))),
            //'domaines de compétences' => array('link' => $this->generateUrl('competence_index', array('id'=> $module->getId()))),
        );
        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    private function getTabsAgent($agent, $activeTab)
    {
        $tabs = array(
            'fiche' => array('link' => $this->generateUrl("agent_edit", array('id' => $agent->getId()))),
            'projets' => array('link' => $this->generateUrl("agent_projets", array('id' => $agent->getId()))),
            'organigramme' => array('link' => $this->generateUrl("agent_organigrammes", array('id' => $agent->getId()))),
            'non disponibilité' => array('link' => $this->generateUrl("non_disponibilite_index", array('id' => $agent->getId()))),
        );
        foreach ($tabs as $key => $tab) {
            if ($key == $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsAAF($rc, $activeTab, $options)
    {

        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('autre_activite_formation_edit', array('id' => $rc->getId()))),
            'documents' => array(
                'link' => $this->generateUrl(
                    'documents_index',
                    array(
                        'id' => $rc->getId(),
                        'entity' => 'AutreActiviteFormation'
                    )
                )
            ),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }


    public function getTabsMateriel($rc, $activeTab, $options)
    {

        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('materiel_fourniture_edit', array('id' => $rc->getId()))),
            'historique' => array('link' => $this->generateUrl('page_en_construction')),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsUtilisation($rc, $activeTab, $options)
    {

        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('utilisation_materiel_edit', array('id' => $rc->getId()))),
            'matériels' => array('link' => $this->generateUrl('etre_utilise_index', ['id' => $rc->getId()])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsAcquisition($rc, $activeTab, $options)
    {
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('acquisition_materiel_edit', array('id' => $rc->getId()))),
            'matériels' => array('link' => $this->generateUrl('acquisition_detail_index', ['id' => $rc->getId()])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }


    public function getTabsSemaine($rc, $activeTab, $options)
    {

        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('semaine_edit', array('id' => $rc->getId()))),
            'programmation' => array('link' => $this->generateUrl('hebdo_activite_index', ['id' => $rc->getId(), 'type' => 'programmation'])),
            'réalisation' => array('link' => $this->generateUrl('hebdo_activite_index', ['id' => $rc->getId(), 'type' => 'réalisation'])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsAccreditationMain($rc, $activeTab, $options)
    {
        $demande = isset($options['demande']) ? $options['demande'] : false;
        $evaluation = isset($options['evaluation']) ? $options['evaluation'] : false;
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('accreditation_edit', array('id' => $rc->getId()))),
            'collaborateurs' => array('link' => $this->generateUrl('etre_accredite_index', ['id' => $rc->getId()])),
            'demandes' => array('link' => $this->generateUrl('accreditation_demande_index', ['id' => $rc->getId()])),
        );
        if ($demande) {
            $tabs['évaluations'] = array('link' => $this->generateUrl('evaluation_accreditation_index', ['id' => $demande->getId()]));
            if ($evaluation) {
                $tabs['résultat'] = array('link' => $this->generateUrl('resultat_evaluation_accreditation_index', ['id' => $evaluation->getId(), 'accreditation' => $rc->getId(), 'demande' => $demande->getId()]));
            }
            $tabs['documents'] = array('link' => $this->generateUrl('documents_index', ['id' => $demande->getId(), 'entity' => 'AccreditationDemande']));
        }

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsReportingActivite($activeTab, $options)
    {

        $tabs = array(
            'suivi mensuel' => array('link' => $this->generateUrl('hebdo_reporting')),
            'suivi trimestriel' => array('link' => $this->generateUrl('pta_reporting', ['type' => 'trimestriel'])),
            'suivi semestriel' => array('link' => $this->generateUrl('pta_reporting', ['type' => 'semestriel'])),
        );

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsBesoin($rc, $activeTab, $options)
    {
        $demande = isset($options['demande']) ? $options['demande'] : false;
        $analyse = isset($options['analyse']) ? $options['analyse'] : false;
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('besoin_edit', array('id' => $rc->getId()))),
            'documents' => array('link' => $this->generateUrl('documents_index', ['id' => $rc->getId(), 'entity' => 'Besoin'])),
            'analyses' => array('link' => $this->generateUrl('analyse_index', ['id' => $rc->getId()])),
        );

        $tabs['propositions techniques'] = array('link' => $this->generateUrl('proposition_technique_index', ['id' => $rc->getId()]));

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }

    public function getTabsLabelisation($rc, $activeTab, $options)
    {
        $demande = isset($options['demande']) ? $options['demande'] : false;
        $analyse = isset($options['analyse']) ? $options['analyse'] : false;
        $evaluation = isset($options['evaluation']) ? $options['evaluation'] : false;
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('labelisation_edit', array('id' => $rc->getId()))),
            'demandes' => array('link' => $this->generateUrl('labelisation_demande_index', ['id' => $rc->getId()])),
            //'organisations' => array('link' => $this->generateUrl('etre_labelise_index', ['id'=>$rc->getId()])),
        );

        if ($demande) {
            $tabs['évaluation'] = array('link' => $this->generateUrl('evaluation_labelisation_index', ['id' => $demande->getId()]));
            if ($evaluation) {
                $tabs['résultat'] = array('link' => $this->generateUrl('resultat_evaluation_labelisation_index', ['id' => $evaluation->getId(), 'accreditation' => $rc->getId(), 'demande' => $demande->getId()]));
            }
            $tabs['documents'] = array('link' => $this->generateUrl('documents_index', ['id' => $demande->getId(), 'entity' => 'LabelisationDemande']));
        }

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }



    public function getTabsParcours($rc, $activeTab, $options)
    {
        $concours = isset($options['concours']) ? $options['concours'] : false;
        $manifestation = isset($options['manifestation']) ? $options['manifestation'] : false;
        $tabs = array(
            'info. générale' => array('link' => $this->generateUrl('parcours_edit', array('id' => $rc->getId()))),
            //'modules' => array('link' => $this->generateUrl('modules_index', array('id' => $rc->getId()))),
            'concours' => array('link' => $this->generateUrl('concours_index', array('id' => $rc->getId()))),

        );

        if ($concours) {
            $tabs['documents'] = array('link' => $this->generateUrl('documents_index', array('id' => $concours->getId(), 'entity' => 'Concours')));
            $tabs['ressources humaines'] = array('link' => $this->generateUrl('etre_retenu_concours_index', array('id' => $concours->getId())));
        }

        $tabs['manifestations'] = array('link' => $this->generateUrl('manifestation_index', array('id' => $rc->getId())));

        if ($manifestation) {
            $tabs['documents'] = array('link' => $this->generateUrl(
                'documents_index',
                array('id' => $manifestation->getId(), 'entity' => 'Manifestation')
            ));
            $tabs['ressources humaines'] = array(
                'link' => $this->generateUrl(
                    'etre_retenu_selection_index',
                    array('id' => $manifestation->getId())
                )
            );
        }

        foreach ($tabs as $key => $tab) {
            if ($key === $activeTab)
                $tabs[$key]['active_class'] = 'active';
        }
        return $tabs;
    }
}
