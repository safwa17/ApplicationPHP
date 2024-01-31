<?php
include "Modules/contexte.php";
include_once "Models/contexteManager.php";


class ContexteController
{
    private $contexteManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->contexteManager = new ContexteManager($db);
        $this->twig = $twig;
    }


    public function FormAjoutContexte()
    {
        echo $this->twig->render('ajout_contexte.html.twig', array('acces' => $_SESSION['acces']));
    }

    public function ajoutContexte()
    {
        $sae = $this->contexteManager->ajouterContexte();
        echo $this->twig->render('ajout_contexte.html.twig', array('sae' => $sae, 'acces' => $_SESSION['acces']));
    }

    public function suppContexte()
    {
        $contexte = new Contexte($_GET);
        $contexteToSupp = $this->contexteManager->SupprimerContexte($contexte);
        print_r($contexte);
        echo $this->twig->render('membre_espace.html.twig', array('contexteToSupp' => $contexteToSupp, 'acces' => $_SESSION['acces']));
    }

}
