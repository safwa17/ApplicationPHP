<?php
/** 
* définition de la classe categorie
*/
class Tags {
        private string $idTag;
        private string $intitule;

		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['Id_tags'])) { $this->idTag = $donnees['Id_tags']; }
			if (isset($donnees['intitule'])) { $this->intitule= $donnees['intitule']; }

        }
        // GETTERS //
		public function idTag() { return $this->idTag;}
		public function intitule() { return $this->intitule;}

		// SETTERS //
		public function setIdTag(string $idTag) { $this->idTag = $idTag; }
		public function setIntitule(string $intitule) { $this->intitule = $intitule; }

		
    }

