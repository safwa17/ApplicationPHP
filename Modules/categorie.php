<?php
/** 
* définition de la classe categorie
*/
class Categorie {
        private string $_Id_categorie;
        private string $_intitule;

		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['Id_categorie'])) { $this->_Id_categorie = $donnees['Id_categorie']; }
			if (isset($donnees['intitule'])) { $this->_intitule = $donnees['intitule']; }

        }
        // GETTERS //
		public function Id_categorie() { return $this->_Id_categorie;}
		public function intitule() { return $this->_intitule;}

		// SETTERS //
		public function setIdCategorie(string $_Id_categorie) { $this->_Id_categorie = $_Id_categorie; }
		public function setTitreProjet(string $_intitule) { $this->_intitule = $_intitule; }

		
    }

