<?php
// utilisation des sessions
session_start();

include "moteurtemplate.php";
include "connect.php";

include "Controllers/membresController.php";
include "Controllers/projetsController.php";
include "Controllers/categorieController.php";
include "Controllers/contextecontroller.php";
include "Controllers/commentaireController.php";
include "Controllers/tagsController.php";

$memController = new MembreController($bdd, $twig);
$proController = new ProjetController($bdd, $twig);
$categorieController = new CategorieController($bdd, $twig);
$contexteController = new ContexteController($bdd, $twig);
$commentaireController = new commentaireController($bdd, $twig);
$tagsController = new tagsController($bdd, $twig);


// texte du message
$message = "";

// ============================== connexion / deconnexion - sessions ==================

// si la variable de session n'existe pas, on la crée
if (!isset($_SESSION['acces'])) {
  $_SESSION['acces'] = "non";
}

if (!isset($_SESSION['admin'])) {
  $_SESSION['admin'] = "non";
}


// formulaire de connexion
if (isset($_GET["action"]) && $_GET["action"] == "login") {
  $memController->membreFormulaire();
}

// click sur le bouton connexion
if (isset($_POST["connexion"])) {
  $message = $memController->membreConnexion($_POST);
}

// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action'] == "logout") {
  $message = $memController->membreDeconnexion();
}


// formulaire d'inscription
if (isset($_GET["action"]) && $_GET["action"] == "register") {
  $memController->membreFormulaireInscription();
}

// inscription
if (isset($_POST["inscription"])) {
  $memController->verif_email();
  // echo "ok";
}


// ============================== page d'accueil ==================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  echo $twig->render('index.html.twig', array('acces' => $_SESSION['acces']));
}

// ============================== mon espace ==================

if (isset($_GET["action"]) && $_GET["action"] == "mespace") {
  if (isset($_GET["idIUT"])) {
    $idIUT = $_GET["idIUT"];
    $memController->monEspace(['idIUT' => $idIUT]);
  } else {
    echo "Error: Membre ID not provided.";
  }
}



// ============================== gestion des projets ==================

// liste des projets dans un tableau HTML
//  https://.../index/php?action=liste
if (isset($_GET["action"]) && $_GET["action"] == "liste") {
  $proController->listeProjets();
}


// details des projets lors du clic
if (isset($_GET["action"]) && $_GET["action"] == "details") {
  // print_r($_GET);
  $proController->projetDetails($_GET);
}


// fonction pour commenter lors du clic dans la page details du projet
if (isset($_GET["action"]) && $_GET["action"] == "commenter") {
  $commentaireController->Commenter();
  $proController->listeProjets();
}


// --------------------AJOUT DE PROJET---------------------
// formulaire d'ajout de projet
if (isset($_GET["action"]) && $_GET["action"] == "add") {
  $proController->formAddProjet();
}

// formulaire ajout d'un projet : saisie des caractéristiques à ajouter dans la BD
//  https://.../index/php?action=valider_ajout
if (isset($_POST["valider_ajout"])) {
  $proController->addProjet();
  $idIUT = $_SESSION["idIUT"];
  $memController->monEspace(['idIUT' => $idIUT]);
}

// ------------------  MODIFICATION DE PROJET -------------------------

// formulaire de gestion
if (isset($_GET["action"]) && $_GET["action"] == "modif") {
  $proController->formModifProjet();
}

// formulaire ajout d'un projet : saisie des caractéristiques à ajouter dans la BD
//  https://.../index/php?action=modifier
if (isset($_POST["modifier"])) {
  echo 'we are here';
  $proController->ModifProjet();
  $idIUT = $_SESSION["idIUT"];
  $memController->monEspace(['idIUT' => $idIUT]);
}


// ------------------  SUPPRESSION DE PROJET -------------------------

// formulaire de suppression
if (isset($_GET["action"]) && $_GET["action"] == "suppr") {
  $proController->formSuppProjet();
}

// suppression d'un projet lors du clic
//  https://.../index/php?action=supprimer
if (isset($_POST["supprimer"])) {
  // echo'we are here';
  $proController->SuppProjet();
  $idIUT = $_SESSION["idIUT"];
  $memController->monEspace(['idIUT' => $idIUT]);
}

// -------------------------- ESPACE ADMIN -------------//

// gestion des SAE/Ressource
// fonction pour formulaire SAE ou Ressource
if (isset($_GET["action"]) && $_GET["action"] == "form_contexte") {
  $contexteController->formAjoutContexte();
}

//fonction pour ajouter une SAE ou Ressource
if (isset($_GET["action"]) && $_GET["action"] == "valider_contexte") {
  $contexteController->ajoutContexte();
}

// suppression d'une SAE ou Ressources au clic sur le bouton de confirmation
//  https://.../index/php?action=suppr_contexte
if (isset($_GET["action"]) && $_GET["action"] == "suppr_contexte") {
  $contexteController->suppContexte();
}


// gestion des categories
// fonction pour formulaire categories
if (isset($_GET["action"]) && $_GET["action"] == "form_categorie") {
  $categorieController->formAjoutCategorie();
}

// fonction pour ajouter les categories dans la bd
if (isset($_GET["action"]) && $_GET["action"] == "valider_categorie") {
  $categorieController->ajoutCategorie();
}

// suppression d'une categorie dans la bd
//  https://.../index/php?action=suppr_categorie
if (isset($_GET["action"]) && $_GET["action"] == "suppr_categorie") {
  $categorieController->suppCategorie();
}



// gestion des utilisateurs
// fonction pour formulaire ajout utilisateurs
if (isset($_GET["action"]) && $_GET["action"] == "form_uti") {
  $memController->formAjoutUti();
}

// fonction pour ajouter l'utilisateur dans la bd
if (isset($_GET["action"]) && $_GET["action"] == "ajout_uti") {
  $memController->ajoutUti();
}

// suppression d'un utilisateur dans la bd
//  https://.../index/php?action=suppr_uti
if (isset($_GET["action"]) && $_GET["action"] == "suppr_uti") {
  $memController->suppUti();
}


//------------------- RECHERCHE ------------------
// recherche de projet: saisie des critres de recherche dans un formulaire
//  https://.../index/php?action=recher
if (isset($_GET["action"]) && $_GET["action"] == "recher") {
  $proController->formRechercheProjet();
}

// recherche des projets 
// --> au clic sur le bouton "valider_recher" du form précédent
if (isset($_POST["valider_recher"])) {
  if (isset($_POST["titreProjet"])) {
    $titre = $_POST["titreProjet"];
    $proController->rechercheProjet($titre);
  } else {
    echo "Erreur: Titre du projet non fournit.";
  }
}





