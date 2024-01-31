<?php
/** 
* définition de la classe projets
*/
class Contexte {
        private string $_idContexte;
        private string $_intitule;
        private string $_semestre;
        private string $_identifiant;

		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['Id_contexte'])) { $this->_idContexte = $donnees['Id_contexte']; }
			if (isset($donnees['intitule'])) { $this->_intitule = $donnees['intitule']; }
			if (isset($donnees['semestre'])) { $this->_semestre = $donnees['semestre']; }
			if (isset($donnees['identifiant'])) { $this->_identifiant = $donnees['identifiant']; }

        }           
        // GETTERS //
		public function idContexte() { return $this->_idContexte;}
		public function intitule() { return $this->_intitule;}
		public function semestre() { return $this->_semestre;}
		public function identifiant() { return $this->_identifiant;}

		// SETTERS //
		public function setIdContexte(string $_idContexte) { $this->_idContexte = $_idContexte; }
		public function setintitule(string $_intitule) { $this->_intitule = $_intitule; }
		public function setSemestre(string $_semestre) { $this->_semestre = $_semestre; }
		public function setIdentifiant(string $_identifiant) { $this->_identifiant = $_identifiant; }

		
    }