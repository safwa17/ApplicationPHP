<?php

/**
 * Définition d'une classe permettant de gérer les commentaires pour les afficher dans la page du projet
 *
 */
class CommentaireManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD


    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function getCommentaireProjet($idprojet)
    {
        $req = "SELECT idIUT,commentaire,Id_Projet,id_commentaire
        FROM commentaires
        WHERE commentaires.Id_Projet = ?
        ";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($idprojet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        while ($donnees = $stmt->fetch()) {
            $commentaires[] = new Commentaire($donnees);
        }
        if (empty($commentaires)) {
            return [];
        } else
        return $commentaires;
    }

    public function commenterProjet()
    {
        $commentaire = filter_input(INPUT_GET, 'commentaire', FILTER_SANITIZE_STRING);
        $idIUT = isset($_SESSION['idIUT']) ? $_SESSION['idIUT'] : null;
        $idprojet = filter_input(INPUT_GET, 'idprojet', FILTER_SANITIZE_STRING);
        // Vérifier si les valeurs de 'commentaire' et 'idIUT' ne sont pas vides avant de mettre les infos dans la bd.
        if (!empty($commentaire) && !empty($idIUT)) {
            $req = 'INSERT INTO commentaires (commentaire, Id_Projet, idIUT) VALUES (:commentaire, :Id_Projet, :idIUT)';

            $stmt = $this->_db->prepare($req);
            $result = $stmt->execute(array(":commentaire" => $commentaire, ":Id_Projet" => $idprojet, ":idIUT" => $idIUT));

            if ($result && $stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            return false;
        }
    }




}