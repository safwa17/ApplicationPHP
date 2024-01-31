<?php

/**
 * Définition d'une classe permettant de gérer les projets 
 * en relation avec la base de données
 */

class ProjetsManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD

    /** 
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

    // liste de tous les projets de la BD
    public function getListProjets()
    {
        $projets = array();
        $req = "SELECT  Id_Projet, titre, description,image,lien_source,lien_demo,Id_contexte,Id_categorie,Id_tags 
        FROM Projets";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();

        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        // récup des données
        while ($donnees = $stmt->fetch()) {
            $projets[] = new Projet($donnees);
        }
        // print_r($projets);
        return $projets;
    }


    // recupère les details d'un projet à partir de son id
    public function getDetailsProjet($idprojet)
    {
        $req = "SELECT * 
        FROM Projets 
        WHERE Projets.Id_Projet = ?;
        ";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $projectData = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($projectData);

        if (empty($projectData['image'])) {
            return "";
        }

        if (empty($projectData)) {
            return null;
        }

        return new Projet($projectData);
    }


    // récupère les projets du membre pour les afficher dans son espace

    public function getProjetsMembre($idiut): array
    {
        $req = "SELECT * 
        FROM Projets 
        NATURAL JOIN contribuer 
        WHERE idIUT = ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idiut));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $projets = [];

        while ($donnees = $stmt->fetch()) {
            $projets[] = new Projet($donnees);
        }
        return $projets;
    }


    // récupère les collaborateurs du projet pour les afficher dans son espace

    public function getCollaborateurProjet($idprojet): array
    {

        $req = "SELECT nom,prenom FROM Utilisateur 
        INNER JOIN contribuer ON Utilisateur.idIUT = contribuer.idIUT 
        INNER JOIN Projets ON contribuer.Id_Projet = Projets.Id_Projet 
        WHERE Projets.Id_Projet = ?";

        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $collaborateurs = [];

        while ($donnees = $stmt->fetch()) {
            $collaborateurs[] = new Membre($donnees);
        }


        return $collaborateurs;
    }




    /**
     * méthode de recherche de projets dans la BD à partir du titre ou de la description
     */
    public function rechercherProjet($titre, $description)
    {
        $req = "SELECT * FROM Projets WHERE titre LIKE :titre OR description LIKE :description";
        $stmt = $this->_db->prepare($req);
        $stmt->bindValue(':titre', "%" . $titre . "%", PDO::PARAM_STR);
        $stmt->bindValue(':description', "%" . $description . "%", PDO::PARAM_STR);
        $stmt->execute();

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }

        $projets = [];

        while ($donnees = $stmt->fetch()) {
            $projets[] = new Projet($donnees);
        }
        return $projets;
    }






    // ajout de projet dans la BD
    public function ajoutProjet($idprojet)
    {
        $projectTitle = filter_input(INPUT_POST, 'projectTitle', FILTER_SANITIZE_STRING);
        $projectContext = filter_input(INPUT_POST, 'projectContext', FILTER_SANITIZE_STRING);
        $projectTags = filter_input(INPUT_POST, 'projectTags', FILTER_SANITIZE_STRING);
        $projectCategorie = filter_input(INPUT_POST, 'projectCategorie', FILTER_SANITIZE_STRING);
        $projectDescription = filter_input(INPUT_POST, 'projectDescription', FILTER_SANITIZE_STRING);
        $demoLink = filter_input(INPUT_POST, 'demoLink', FILTER_SANITIZE_STRING);
        $sourceLink = filter_input(INPUT_POST, 'sourceLink', FILTER_SANITIZE_STRING);
        $image = $idprojet->imageProjet();
        $req = 'INSERT INTO Projets (titre, Id_contexte, Id_tags, Id_categorie, description, lien_demo, lien_source,image)
        VALUES (:projectTitle, :projectContext, :projectTags, :projectCategorie, :projectDescription, :demoLink, :sourceLink,:image)';

        if ($projectContext == false) {
            echo 'project context not found';
        } else {
            echo '';
        }

        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":projectTitle" => $projectTitle,
                ":projectContext" => $projectContext,
                ":projectTags" => $projectTags,
                ":projectCategorie" => $projectCategorie,
                ":projectDescription" => $projectDescription,
                ":demoLink" => $demoLink,
                ":sourceLink" => $sourceLink,
                ":image" => $image
            )
        );

        if ($stmt->rowCount() > 0) {
            $req = 'SELECT MAX(Id_Projet) FROM Projets';
            $stmt = $this->_db->prepare($req);
            $stmt->execute();
            $idprojet = $stmt->fetchcolumn();
            return $idprojet;
        } else {
            return false;
        }
    }


    // ajout de collaborateurs sur le projet créer dans la BD
    function ajoutProjetCollab($idprojet)
    {
        $projectCollaborators = filter_input(INPUT_POST, 'projectCollaborators', FILTER_SANITIZE_STRING);
        $req = 'INSERT INTO contribuer (idIUT,Id_Projet)
        VALUES (:projectCollaborators,:idprojet)';

        $stmt = $this->_db->prepare($req);
        $stmt->execute(array(":projectCollaborators" => $projectCollaborators, ":idprojet" => $idprojet));
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    // modification d'un projet dans la BD

    public function ModifProjet()
    {
        $projectId = filter_input(INPUT_POST, 'modifyProjectId', FILTER_SANITIZE_NUMBER_INT);

        $projectTitle = filter_input(INPUT_POST, 'modifyProjectTitle', FILTER_SANITIZE_STRING);
        $projectContext = filter_input(INPUT_POST, 'modifyProjectContext', FILTER_SANITIZE_STRING);
        $projectTags = filter_input(INPUT_POST, 'modifyProjectTags', FILTER_SANITIZE_STRING);
        $projectCategorie = filter_input(INPUT_POST, 'modifyProjectCategorie', FILTER_SANITIZE_STRING);
        $projectDescription = filter_input(INPUT_POST, 'modifyProjectDescription', FILTER_SANITIZE_STRING);
        $demoLink = filter_input(INPUT_POST, 'modifyDemoLink', FILTER_SANITIZE_STRING);
        $sourceLink = filter_input(INPUT_POST, 'modifySourceLink', FILTER_SANITIZE_STRING);

        if (!$projectId) {
            echo 'Invalid project ID';
            return false;
        }

        $req = 'UPDATE Projets SET
                titre = :projectTitle,
                Id_contexte = :projectContext,
                Id_tags = :projectTags,
                Id_categorie = :projectCategorie,
                description = :projectDescription,
                lien_demo = :demoLink,
                lien_source = :sourceLink
                WHERE Id_Projet = :projectId';

        $stmt = $this->_db->prepare($req);
        $stmt->execute(
            array(
                ":projectTitle" => $projectTitle,
                ":projectContext" => $projectContext,
                ":projectTags" => $projectTags,
                ":projectCategorie" => $projectCategorie,
                ":projectDescription" => $projectDescription,
                ":demoLink" => $demoLink,
                ":sourceLink" => $sourceLink,
                ":projectId" => $projectId
            )
        );

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // modification des collaborateurs dans la BD
    function modifProjetCollab()
    {
        $projectCollaborators = filter_input(INPUT_POST, 'modifyProjectCollaborators', FILTER_SANITIZE_STRING);
        $projectId = filter_input(INPUT_POST, 'modifyProjectId', FILTER_SANITIZE_STRING);

        $req = 'UPDATE contribuer SET idIUT = :modifyProjectCollaborators WHERE Id_Projet = :idprojet';
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array(":modifyProjectCollaborators" => $projectCollaborators, ":idprojet" => $projectId));

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function suppContribuer()
    {
        $idprojet = filter_input(INPUT_POST, 'deleteProjectId', FILTER_SANITIZE_STRING);

        $reqContribuer = 'DELETE FROM contribuer WHERE Id_Projet = :idprojet';
        $stmtContribuer = $this->_db->prepare($reqContribuer);
        $stmtContribuer->bindParam(':idprojet', $idprojet);
        $stmtContribuer->execute();
        return $stmtContribuer->rowCount() > 0;
    }

    public function suppTags()
    {
        $idprojet = filter_input(INPUT_POST, 'deleteProjectId', FILTER_SANITIZE_STRING);

        $reqProjetsTags = 'DELETE FROM projets_tags WHERE Id_Projet = :idprojet';
        $stmtProjetsTags = $this->_db->prepare($reqProjetsTags);
        $stmtProjetsTags->bindParam(':idprojet', $idprojet);
        $stmtProjetsTags->execute();

        return $stmtProjetsTags->rowCount() > 0;
    }

    public function suppCat()
    {
        $idprojet = filter_input(INPUT_POST, 'deleteProjectId', FILTER_SANITIZE_STRING);

        $reqProjetCategorie = 'DELETE FROM projet_categorie WHERE Id_Projet = :idprojet';
        $stmtProjetCategorie = $this->_db->prepare($reqProjetCategorie);
        $stmtProjetCategorie->bindParam(':idprojet', $idprojet);
        $stmtProjetCategorie->execute();

        return $stmtProjetCategorie->rowCount() > 0;
    }
    public function suppCom()
    {
        $idprojet = filter_input(INPUT_POST, 'deleteProjectId', FILTER_SANITIZE_STRING);

        $reqProjetCom = 'DELETE FROM commentaires WHERE Id_Projet = :idprojet';
        $stmtProjetCom = $this->_db->prepare($reqProjetCom);
        $stmtProjetCom->bindParam(':idprojet', $idprojet);
        $stmtProjetCom->execute();

        return $stmtProjetCom->rowCount() > 0;
    }

    public function suppProject()
    {
        $idprojet = filter_input(INPUT_POST, 'deleteProjectId', FILTER_SANITIZE_STRING);

        $req = 'DELETE FROM Projets WHERE Id_projet = :idprojet';
        $stmt = $this->_db->prepare($req);
        $stmt->bindParam(':idprojet', $idprojet);
        $stmt->execute();

        return $stmt->rowCount() > 0;

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}



