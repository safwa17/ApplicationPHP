<?php

/**
 * Définition d'une classe permettant de gérer les membres 
 * en relation avec la base de données
 *
 */

class MembreManager
{
	private $_db; // Instance de PDO - objet de connexion au SGBD

	/** 
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * verification de l'identité d'un membre (Login/password)
	 * @param string $login
	 * @param string $password
	 * @return membre si authentification ok, false sinon
	 */

	public function verif_identification($login, $password)
	{
		// echo $login." : ".$password;
		$req = "SELECT * FROM Utilisateur 
		 WHERE mail=:login 
		 and motdepasse=:password ";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login, ":password" => $password));

		if ($data = $stmt->fetch()) {
			$membre = new Membre($data);
			return $membre;
		} else {
			return ($data);
		}
	}


	function membreRegister($membre)
	{
		$nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
		$prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL);
		$idiut = filter_input(INPUT_POST, 'idiut', FILTER_SANITIZE_STRING);
		$motdepasse = filter_input(INPUT_POST, 'pssword', FILTER_SANITIZE_STRING);

		// Check si la checkbox de admin est 1 ou 0 
		$admin = isset($_POST['admin']) && $_POST['admin'] == 1 ? 1 : 0;

		// Verification si un des field est vide
		if (empty($nom) || empty($prenom) || empty($email) || empty($idiut) || empty($motdepasse)) {
			return 'Remplissez le formulaire';
		}

		$req = 'INSERT INTO Utilisateur(nom, prenom, mail, idiut, motdepasse, admin)
		VALUES (:nom, :prenom, :mail, :idiut, :pssword, :admin)';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":nom" => $nom, ":prenom" => $prenom, ":mail" => $email, ":idiut" => $idiut, ":pssword" => $motdepasse, ":admin" => $admin));

		if ($stmt->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function mesInformationsByIdIUT($_idiut): ?Membre
	{
		$req = "SELECT * FROM Utilisateur 
		WHERE idIUT = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($_idiut));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			throw new Exception("SQL Error: " . implode(" ", $errorInfo));
		}

		$infomembre = $stmt->fetch();
		if (!$infomembre) {
			echo "Error: No member found with idIUT = {$_idiut}";
			return null;
		}
		// var_dump($infomembre);

		return new Membre($infomembre);
	}


	public function allUti()
	{
		$req = "SELECT * FROM Utilisateur ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($req));

		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			throw new Exception("SQL Error: " . implode(" ", $errorInfo));
		}

        $utilisateurs = [];

        while ($donnees = $stmt->fetch()) {
            $utilisateurs[] = new Membre($donnees);
        }

        return $utilisateurs;
	}


	public function SupprimerUti(Membre $membre): bool
    {
        $req = 'DELETE FROM Utilisateur
        WHERE idIUT = ?';
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($membre->idiut()));

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

}



