<?php
include "Modules/categorie.php";
include "Models/categorieManager.php";

class CategorieController
{
    private $categorieManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->categorieManager = new CategorieManager($db);
        $this->twig = $twig;
    }

    public function formAjoutCategorie()
    {
        // echo 'ok';
        echo $this->twig->render('ajout_categorie.html.twig', array('acces' => $_SESSION['acces']));

    }


    public function ajoutCategorie()
    {
        $categorieAdded = $this->categorieManager->ajouterCategorie($_POST);

        if ($categorieAdded) {
            $message = "Categorie ajouté !";
            echo $this->twig->render('ajout_categorie.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
        } else {
            echo "Fail";
        }
    }


    public function suppCategorie()
    {
        $categorie = new Categorie($_GET);
        $categorieToSupp = $this->categorieManager->SupprimerCategorie($categorie);
        echo 'Categorie supprimé ! ';
        echo $this->twig->render('membre_espace.html.twig', array('categorieToSupp' => $categorieToSupp, 'acces' => $_SESSION['acces']));
    }

}
