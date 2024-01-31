<?php
include "Modules/commentaire.php";
include_once "Models/commentaireManager.php";


class CommentaireController
{
    private $commentaireManager; // instance du manager
    private $membreManager; // instance du manager
    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->commentaireManager = new commentaireManager($db);
        $this->membreManager = new MembreManager($db);
        $this->twig = $twig;
    }

    public function Commenter()
    {
        if (isset($_GET["commentaire"]) && isset($_GET["idprojet"])) {

            $commentaire = $this->commentaireManager->commenterProjet();
            $this->twig->render('projet_details.html.twig', array('commentaire' => $commentaire, 'acces' => $_SESSION['acces']));
        } else {
            // si erreur
            echo "Commenter method non executée. GET data pas trouvé";
        }
    }
}

