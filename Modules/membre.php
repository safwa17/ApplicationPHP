<?php
/** 
* définition de la classe itineraire
*/
class Membre {
        private string $_nom;
        private string $_prenom;
		private string $_email;
		private string $_idiut;
		private string $_password;
		private int $_admin;
		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['nom'])) { $this->_nom = $donnees['nom']; }
			if (isset($donnees['prenom'])) { $this->_prenom = $donnees['prenom']; }
			if (isset($donnees['mail'])) { $this->_email = $donnees['mail']; }
			if (isset($donnees['idIUT'])) { $this->_idiut = $donnees['idIUT']; }
			if (isset($donnees['password'])) { $this->_password = $donnees['password']; }
			if (isset($donnees['admin'])) { $this->_admin = $donnees['admin']; }
        }           
        // GETTERS //
		public function nom() { return $this->_nom;}
		public function prenom() { return $this->_prenom;}
		public function email() { return $this->_email;}
		public function idiut() { return $this->_idiut;}
		public function password() { return $this->_password;}
		public function admin() { return $this->_admin;}

		
		// SETTERS //
        public function setNom(string $nom) { $this->_nom= $nom; }
		public function setPrenom(string $prenom) { $this->_prenom = $prenom; }
		public function setEmail(string $email) { $this->_email = $email; }
		public function setIdIut(string $_idiut) { $this->_idiut = $_idiut; }
		public function setPassword(string $password) { $this->_password = $password; }	
		public function setAdmin(int $admin) { $this->_admin = $admin; }		

    }

