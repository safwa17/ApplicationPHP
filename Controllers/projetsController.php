<?php
include "Modules/projets.php";
include "Models/projetsManager.php";

class ProjetController
{
    private $projetsManager; // instance du manager
    private $categorieManager; // instance du manager
    private $contexteManager; // instance du manager
    private $tagsManager; // instance du manager
    private $membreManager; // instance du manager
    private $commentaireManager; // instance du manager

    private $twig;

    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {
        $this->projetsManager = new projetsManager($db);
        $this->categorieManager = new CategorieManager($db);
        $this->contexteManager = new ContexteManager($db);
        $this->tagsManager = new tagsManager($db);
        $this->membreManager = new membreManager($db);
        $this->commentaireManager = new CommentaireManager($db);
        $this->twig = $twig;
    }



    // liste projets de la bd 
    public function listeProjets()
    {
        $projets = $this->projetsManager->getListProjets();
        echo $this->twig->render('projets_liste.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
    }



    // details des projets
    public function projetDetails($requestData)
    {
        if (isset($requestData["idprojet"])) {
            $idprojet = $requestData["idprojet"];
            $idcontexte = $requestData["Id_contexte"];
            $idcategorie = $requestData["Id_categorie"];
            $idtags = $requestData["Id_tags"];
            $projet = $this->projetsManager->getDetailsProjet($idprojet);
            $contexte = $this->contexteManager->getContexteProjet($idcontexte);
            $categorie = $this->categorieManager->getCategorieProjet($idcategorie);
            $collaborateur = $this->projetsManager->getCollaborateurProjet($idprojet);
            $commentaire = $this->commentaireManager->getCommentaireProjet($idprojet);
            $tags = $this->tagsManager->getTagsProjet($idtags);


            // code de Mohammed Alshanquiti 
            $collab_test = [];
            foreach ($collaborateur as $collab) {
                $collab_new = array('nom' => $collab->nom(), 'prenom' => $collab->prenom());
                $collab_sanitized[] = $collab_new;
                $new_collab_test = $collab->nom() . "  " . $collab->prenom();
                $collab_test[] = $new_collab_test;
            }
            // code de Mohammed Alshanqiti 

             echo $this->twig->render('projet_details.html.twig', array('projet' => $projet, 'contexte' => $contexte, 'categorie' => $categorie, 'tags' => $tags[0]->intitule(), 'collaborateurs' => $collab_test, 'commentaire' => $commentaire, 'acces' => $_SESSION['acces']));

            // if ($contexte) {
            //     echo $this->twig->render('projet_details.html.twig', array('projet' => $projet, 'contexte' => $contexte, 'categorie' => $categorie, 'tags' => $tags[0]->intitule(), 'collaborateurs' => $collab_test, 'commentaire' => $commentaire, 'acces' => $_SESSION['acces']));
            // } else {
            //     echo "Error: Project not found.";
            // }
        } else {
            echo "Error: Project ID not provided.";
        }
    }


    // recherche formulaire 
    public function formRechercheProjet()
    {
        echo $this->twig->render('projet_recherche.html.twig', array('acces' => $_SESSION['acces']));
    }

    // recherche execution
    public function rechercheProjet()
    {
        if (isset($_POST["titreProjet"])) {
            $titre = $_POST["titreProjet"];
            $description = $_POST["titreProjet"];
            $projet = $this->projetsManager->rechercherProjet($titre, $description);
            if ($projet) {
                echo $this->twig->render('projet_recherche.html.twig', array('projet' => $projet, 'acces' => $_SESSION['acces']));
            } else {
                echo "Error: Project not found.";
            }
        } else {
            echo "Error: Project ID not provided.";
        }
    }




    public function formAddProjet()
    {
        $tags = $this->tagsManager->allTags();
        $contextes = $this->contexteManager->allContexteNorm();
        $categories = $this->categorieManager->allCategorieNorm();
        $collaborateurs = $this->membreManager->allUti();

        echo $this->twig->render('ajout_projet.html.twig', array('tags' => $tags, 'contextes' => $contextes, 'categories' => $categories, 'collaborateurs' => $collaborateurs, 'acces' => $_SESSION['acces']));
    }

    public function addProjet()
    {
        $idprojet = new Projet($_POST);

        $uploadfile = "";
        if ($_FILES["projectPhoto"]["error"] == UPLOAD_ERR_OK) {
            $uploaddir = "./img/";
            $uploadfile = $uploaddir . basename($_FILES["projectPhoto"]["name"]);

            if (move_uploaded_file($_FILES["projectPhoto"]["tmp_name"], $uploadfile)) {
                $idprojet->setImage(basename($_FILES["projectPhoto"]["name"]));
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Error: " . $_FILES["projectPhoto"]["error"];
        }

        $idprojet = $this->projetsManager->ajoutProjet($idprojet);

        $ajout2 = $this->projetsManager->ajoutProjetCollab($idprojet);

        echo $this->twig->render('membre_espace.html.twig', array('uploadfile' => $uploadfile, 'idprojet' => $idprojet, 'ajout2' => $ajout2, 'acces' => $_SESSION['acces']));
    }


    public function formModifProjet()
    {
        $projets = $this->projetsManager->getListProjets();
        $tags = $this->tagsManager->allTags();
        $contextes = $this->contexteManager->allContexteNorm();
        $categories = $this->categorieManager->allCategorieNorm();
        $collaborateurs = $this->membreManager->allUti();
        echo $this->twig->render('gestion_projet.html.twig', array('tags' => $tags, 'projets' => $projets, 'contextes' => $contextes, 'categories' => $categories, 'collaborateurs' => $collaborateurs, 'acces' => $_SESSION['acces']));
    }


    public function ModifProjet()
    {
        $this->projetsManager->ModifProjet();
        $this->projetsManager->ModifProjetCollab();
    }


    // supprimer un projet
    public function formSuppProjet()
    {
        $projets = $this->projetsManager->getListProjets();
        echo $this->twig->render('supprimer_projet.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
    }


    public function SuppProjet()
    {
        $this->projetsManager->suppContribuer();
        $this->projetsManager->suppTags();
        $this->projetsManager->suppCat();
        $this->projetsManager->suppCom();
        $this->projetsManager->suppProject();
    }




}