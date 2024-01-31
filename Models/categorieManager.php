<?php

/**
 * Définition d'une classe permettant de gérer les categories pour les afficher dans les details projet
 *
 */
class CategorieManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD


    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function getCategorieProjet($categorieProjet): ?Categorie
    {
        $req = "SELECT Id_categorie , categorie.intitule
        FROM categorie
        WHERE Id_categorie = ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($categorieProjet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $categorieData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($categorieData)) {
            return null;
        }

        return new Categorie($categorieData);
    }

    public function ajouterCategorie($formData)
    {
        $Intitule = filter_var($formData['categorieIntitule'], FILTER_SANITIZE_STRING);

        if (!empty($Intitule)) {
            $req = 'INSERT INTO categorie (intitule) VALUES (:categorieIntitule)';
            $stmt = $this->_db->prepare($req);
            $stmt->execute(array(":categorieIntitule" => $Intitule));

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function allCategorieNorm()
    {
        $req = "SELECT * FROM categorie ";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $categories = [];

        while ($donnees = $stmt->fetch()) {
            $categories[] = new Categorie($donnees);
        }

        return $categories;
    }

    public function allCategorie()
    {
        $req = "SELECT * FROM `categorie` WHERE Id_categorie NOT IN (SELECT Id_categorie FROM projet_categorie)";
        $stmt = $this->_db->prepare($req);
        $stmt->execute();

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $categories = [];

        while ($donnees = $stmt->fetch()) {
            $categories[] = new Categorie($donnees);
        }
        return $categories;
    }

    public function SupprimerCategorie(Categorie $categorie): bool
    {
        $req = 'DELETE FROM categorie
        WHERE Id_categorie = ?';
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($categorie->Id_categorie()));

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
