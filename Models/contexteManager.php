<?php


class ContexteManager
{
    private $_db; // Instance de PDO - objet de connexion au SGBD

    /** 
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function getContexteProjet($contexteProjet): ?Contexte
    {
        $req = "SELECT intitule , semestre , identifiant FROM contexte 
        INNER JOIN Projets ON Projets.Id_contexte = contexte.Id_contexte
        WHERE Projets.Id_Projet = ?";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($contexteProjet));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $contextData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($contextData)) {
            return null;
        }

        return new Contexte($contextData);
    }


    public function ajouterContexte()
    {
        $identifiant = filter_input(INPUT_POST, 'contexteIdentifiant', FILTER_SANITIZE_STRING);

        $req = 'INSERT INTO contexte (identifiant) VALUES (:identifiant)';
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array(":identifiant" => $identifiant));

        if ($stmt->rowCount() > 0) {
            $contexteData = [
                'idContexte' => $this->_db->lastInsertId(),
                'intitule' => null,
                'semestre' => null, 
                'identifiant' => $identifiant,
            ];

            return new Contexte($contexteData);
        } else {

            return false;
        }
    }

    public function allContexteNorm()
    {
        $req = "SELECT * FROM contexte ";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($req));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $contextes = [];

        while ($donnees = $stmt->fetch()) {
            $contextes[] = new Contexte($donnees);
        }

        return $contextes;
    }

    public function allContexte()
    {
        $req = "SELECT * FROM `contexte` WHERE Id_contexte NOT IN (SELECT Id_contexte FROM Projets)";
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($req));

        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            throw new Exception("SQL Error: " . implode(" ", $errorInfo));
        }

        $contextes = [];

        while ($donnees = $stmt->fetch()) {
            $contextes[] = new Contexte($donnees);
        }

        return $contextes;
    }


    public function SupprimerContexte(Contexte $contexte): bool
    {
        $req = 'DELETE FROM contexte
        WHERE Id_contexte = ?';
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($contexte->idContexte()));

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

}



