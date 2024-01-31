<?php
include "Modules/membre.php";
include "Models/membreManager.php";

/**
 * Définition d'une classe permettant de gérer les membres 
 *   en relation avec la base de données	
 */
class MembreController
{
	private $membreManager; // instance du manager
	private $projetsManager;
	private $contexteManager;
	private $categorieManager;

	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->membreManager = new MembreManager($db);
		$this->projetsManager = new ProjetsManager($db);
		$this->contexteManager = new contexteManager($db);
		$this->categorieManager = new categorieManager($db);

		$this->twig = $twig;
	}


	function membreConnexion($data)
	{
		// verif du login et mot de passe
		$membre = $this->membreManager->verif_identification($_POST['login'], $_POST['passwd']);
		if ($membre != false) { // acces autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idIUT'] = $membre->idiut();
			$_SESSION['admin'] = $membre->admin(); // stockage de la valeur de admin 

			// print_r($_SESSION['admin']);
			// Redirige vers l'espace utilisateur après connexion
			header("Location: index.php?action=mespace&idIUT=" . $_SESSION['idIUT'] . $_SESSION['admin']);

		} else { // acces non autorisé : variable de session acces = non
			$_SESSION['acces'] = "non";
			echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces']));
		}
	}

	function verif_email()
	{
		$membre = new Membre($_POST);
		$email = $membre->email();
	
		if ($email !== false) {
			$email = (string) $email; // check que l'email soit a string 
	
			// Check si l'email utilise "etu.iut-tlse3.fr"
			$pattern = "/@etu\.iut-tlse3\.fr$/";
	
			if (preg_match($pattern, $email)) {
				$this->membreManager->membreRegister($membre);
				echo $this->twig->render('membre_inscription.html.twig', array('acces' => $_SESSION['acces']));
				// echo "on y est";
			} else {
				echo "c faux (wrong domain)";
			}
		} else {
			echo "c faux (invalid email)";
		}
	}
	
	/**
	 * deconnexion
	 * @param aucun
	 * @return rien
	 */
	function membreDeconnexion()
	{
		$_SESSION['acces'] = "non"; // acces non autorisé
		$message = "vous êtes déconnecté";
		echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
	}

	/**
	 * formulaire de connexion
	 * @param aucun
	 * @return rien
	 */
	function membreFormulaire()
	{
		echo $this->twig->render('membre_connexion.html.twig', array('acces' => $_SESSION['acces']));
	}

	/**
	 * forminscription
	 * @param aucun
	 * @return rien
	 */
	function membreFormulaireInscription()
	{
		echo $this->twig->render('membre_inscription.html.twig', array('acces' => $_SESSION['acces']));
	}


	// espace membre 
	public function monEspace($requestData)
	{
		if (isset($requestData["idIUT"])) {
			// $idIUT = $requestData["idIUT"];
			$membre = $this->membreManager->mesInformationsByIdIUT($_SESSION['idIUT']);
			$projet = $this->projetsManager->getProjetsMembre($_SESSION['idIUT']);

			$utilisateurs = $this->membreManager->allUti();
			$contextes = $this->contexteManager->allContexte();
			$categories = $this->categorieManager->allCategorie();
			
			$_SESSION['admin'] = $membre->admin();
			if ($membre) {
				echo $this->twig->render('membre_espace.html.twig', array('membre' => $membre, 'projet' => $projet, 'utilisateurs' => $utilisateurs, 'categories' => $categories,'contextes' => $contextes, 'acces' => $_SESSION['acces'],'admin' => $_SESSION['admin']));
			} else {
				echo "Error: Membre not found in monEspace.";
			}
		} else {
			echo "Error: Membre ID not provided in monEspace.";
		}
	}



	function formAjoutUti()
	{
		echo $this->twig->render('ajout_utilisateur.html.twig', array('acces' => $_SESSION['acces']));
	}

	public function ajoutUti()
	{
		$membre = new Membre($_POST);
		$email = $membre->email();
	
		if ($email !== false) {
			$email = (string) $email; // check que l'email soit a string 
	
			// Check si l'email utilise "etu.iut-tlse3.fr"
			$pattern = "/@etu\.iut-tlse3\.fr$/";
	
			if (preg_match($pattern, $email)) {
				$this->membreManager->membreRegister($membre);
				echo $this->twig->render('ajout_utilisateur.html.twig', array('acces' => $_SESSION['acces']));
			} else {
				echo "c faux (wrong domain)";
			}
		} else {
			echo "c faux (invalid email)";
		}
	}
	
    public function suppUti()
    {
        $membre = new Membre($_GET);
        $membreToSupp = $this->membreManager->SupprimerUti($membre);
        echo $this->twig->render('membre_espace.html.twig', array('membreToSupp' => $membreToSupp, 'acces' => $_SESSION['acces']));

    }


}
