<?php
/** 
* définition de la classe commentaire
*/
class Commentaire {
        private string $commentaire;
        private string $id_IUT;
        private string $id_projet;
        private string $id_commentaire;

		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['commentaire'])) { $this->commentaire = $donnees['commentaire']; }
			if (isset($donnees['idIUT'])) { $this->idIUT= $donnees['idIUT']; }
			if (isset($donnees['Id_Projet'])) { $this->Id_Projet = $donnees['Id_Projet']; }
			if (isset($donnees['id_commentaire'])) { $this->id_commentaire = $donnees['id_commentaire']; }

        }           
        // GETTERS //
		public function idCommentaire() { return $this->id_commentaire;}
		public function idProjet() { return $this->id_projet;}
		public function idIUT() { return $this->id_IUT;}
		public function commentaire() { return $this->commentaire;}

		// SETTERS //
		public function setIdCommentaire(string $idCommentaire) { $this->id_commentairee = $idCommentaire; }
		public function setIdProjet(string $id_projet) { $this->id_projet = $id_projet; }
		public function setidIUT(string $idIUT) { $this->_idIUT = $idIUT; }
		public function setCommentaire(string $commentaire) { $this->commentaire = $commentaire; }

		
    }

