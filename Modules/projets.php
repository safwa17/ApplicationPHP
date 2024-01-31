<?php
/** 
* définition de la classe projets
*/
class Projet {
        private string $_idprojet;
        private string $_titre;
		private string $_description;
		private string $_image;
		private string $_lien_source;
		private string $_lien_demo;
		private string $_Id_contexte;
		private string $_Id_categorie;
		private string $_tags_projet;
		
		public function getIdProjet() {
			return $this->_idprojet;
		}
		
        // constructeur
		public function __construct(array $donnees) {
			if (isset($donnees['Id_Projet'])) { $this->_idprojet = $donnees['Id_Projet']; }
			if (isset($donnees['titre'])) { $this->_titre = $donnees['titre']; }
			if (isset($donnees['description'])) { $this->_description = $donnees['description']; }
			if (isset($donnees['image'])) { $this->_image = $donnees['image']; }
			if (isset($donnees['lien_source'])) { $this->_lien_source = $donnees['lien_source']; }
			if (isset($donnees['lien_demo'])) { $this->_lien_demo = $donnees['lien_demo']; }
			if (isset($donnees['Id_contexte'])) { $this->_Id_contexte = $donnees['Id_contexte']; }
			if (isset($donnees['Id_categorie'])) { $this->_Id_categorie = $donnees['Id_categorie']; }
			if (isset($donnees['Id_tags'])) { $this->_tags_projet = $donnees['Id_tags']; }
		}
				   
        // GETTERS //
		public function idProjet() { return $this->_idprojet;}
		public function titreProjet() { return $this->_titre;}
		public function descriptionProjet() { return $this->_description;}
		public function imageProjet() { return $this->_image;}
		public function lienSource() { return $this->_lien_source;}
		public function lienDemo() { return $this->_lien_demo;}
		public function contexteProjet() { return $this->_Id_contexte;}
		public function categorieProjet() { return $this->_Id_categorie;}
		public function tagsProjet() { return $this->_tags_projet;}
		
		// SETTERS //
		public function setIdProjet(string $idprojet) { $this->_idprojet = $idprojet; }
		public function setTitreProjet(string $titre) { $this->_titre = $titre; }
		public function setDescriptionProjet(string $description) { $this->_description = $description; }
		public function setImage(string $image) { $this->_image = $image; }
		public function setLienSource(string $lien_source) { $this->_lien_source = $lien_source; }
		public function setLienDemo(string $lien_demo) { $this->_lien_demo = $lien_demo; }
		public function setContexteProjet(string $Id_contexte) { $this->_Id_contexte = $Id_contexte; }
		public function setCategorieProjet(string $Id_categorie) { $this->_Id_categorie = $Id_categorie; }
		public function setTagsProjet(string $tags_projet) { $this->_tags_projet = $tags_projet; }
		
    }

