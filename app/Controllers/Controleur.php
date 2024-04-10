<?php
//acces au controller parent pour l heritage
namespace App\Controllers;
use CodeIgniter\Controller;

//=========================================================================================
//définition d'une classe Controleur (meme nom que votre fichier Controleur.php) 
//héritée de Controller et permettant d'utiliser les raccoucis et fonctions de CodeIgniter
//  Attention vos Fichiers et Classes Controleur et Modele doit commencer par une Majuscule 
//  et suivre par des minuscules
//=========================================================================================

class Controleur extends BaseController {

//=====================================================================
//Fonction index correspondant au Controleur frontal (ou index.php) en MVC libre
//=====================================================================
public function index() {
    // Code du controleur frontal
    if (isset($_GET['action'])) {
		switch ($_GET['action']) {
			case "deconexion":
				session_unset();
				$this->loginPage();
				break;
			case "accueil":
				$this->accueil();
				break;
			case "renseignerFiche":
				$this->renseignerFiche();
				break;
			case "supprimerFiche":
				$this->supprimerFiche();
				break;
			case "consulterFiche":
				$this->consulterFiche(null);
				break;
			default:
				echo view("login");
				break;
		}
	} elseif (isset($_POST['consulterMois'])) {
		$this->consulterFiche($_POST['consulterMois']);
	}elseif (isset($_POST['idFraisHorsForfait'])) {
		$this->supprimerFiche();
	} 
	
	elseif (isset($_POST['login']) && isset($_POST['mdp'])) {
		$this->login(htmlspecialchars($_POST['login']), htmlspecialchars($_POST['mdp']));
	} elseif (isset($_POST['etape']) && isset($_POST['km']) && isset($_POST['nuite']) && isset($_POST['repas'])) {
		$this->submitFF($_POST['etape'], $_POST['km'], $_POST['nuite'], $_POST['repas']);
	} elseif (isset($_POST['frais_h']) && isset($_POST['date_h']) && isset($_POST['montant_h'])) {
		$this->submitFHF($_POST['frais_h'], $_POST['date_h'], $_POST['montant_h']);
	}elseif (isset($_POST['modif_etape'])){
		$this->soumettreModificationFraisForfait($_POST['modif_etape'], $_POST['modif_km'],$_POST['modif_nuite'],$_POST['modif_repas']);
	} elseif (isset($_POST['date'])){
		$this->modificationFraisHorsForfait($_POST['date'],$_POST['libelle'],$_POST['montant'],$_POST['id_fhf']);
	} 
	else {
		echo view("login");
	}
	
    // Fin du controleur frontal
}

		
		//=========================
		//fin du controleur frontal
		//=========================



//======================================================
// Code du controleur simple (ex fichier Controleur.php)
//======================================================

// Action 1 : Affiche la liste de tous les billets du blog
public function loginPage() {
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	unset($_SESSION['error_fiche']);
	echo view('login');
		
}

public function accueil(){
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	unset($_SESSION['error_fiche']);
	echo view('accueil_visiteur');
}


public function Login($login, $mdp)
    {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

			$modele = new \App\Models\Modele();

			$user = $modele->getId($login, $mdp); // Utilisez votre méthode pour récupérer l'utilisateur.

			if ($user){
				$_SESSION['user_id'] = $user->id; // Utilisez la notation avec la flèche pour accéder aux propriétés de l'objet.
    			$_SESSION['username'] = $user->login;
    			$_SESSION['nom'] = $user->nom;
    			$_SESSION['prenom'] = $user->prenom;
				echo view('accueil_visiteur');
			}
			else{
				$_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
				echo view('login');
			}

        
    }

public function submitFF($etape,$km,$repas,$nuite){

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$modele = new \App\Models\Modele();
	
	$fiche = $modele->ficheFrais($etape,$km,$nuite,$repas,$_SESSION['user_id']);

	$this->renseignerFiche();
}

public function submitFHF($frais,$date,$montant){
	$modele = new \App\Models\Modele();
	session_start();

	$fiche = $modele->ficheHorsForfait($frais,$date,$montant,$_SESSION['user_id']);

	$this->renseignerFiche();
}



public function consulterFiche($consulterMois = null) {
    
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

    $modele = new \App\Models\Modele();

    $data['lignesFraisHorsForfait'] = [];
    $data['quantites'] = [];

    // Vérifiez si un mois est sélectionné
    if ($consulterMois) {
        // Si un mois est sélectionné, chargez les données spécifiques à ce mois
        $data['lignesFraisHorsForfait'] = $modele->getLignesFraisHorsForfait($_SESSION['user_id'], $consulterMois);
        $data['quantites'] = $modele->getQuantitesFraisForfait($_SESSION['user_id'], $consulterMois);
    }

    echo view('consulter_fiche', $data);
}

public function renseignerFiche() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR', 'fr', 'fr', 'fra', 'fr_FR@euro');
    $mois = strftime("%B");

    $modele = new \App\Models\Modele();

    $data['lignesFraisHorsForfait'] = [];
    $data['quantites'] = [];

    // Vérifiez si un mois est sélectionné

    // Si un mois est sélectionné, chargez les données spécifiques à ce mois
    $data['lignesFraisHorsForfait'] = $modele->getLignesFraisHorsForfait($_SESSION['user_id'], $mois);
    $data['quantites'] = $modele->getQuantitesFraisForfait($_SESSION['user_id'], $mois);

    // Définissez la variable $quantites dans le tableau $data
    $quantites = $data['quantites'];

    echo view('renseigner_fiche', $data);
}

public function supprimerFiche() {
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$FHF_id = htmlspecialchars($_POST['idFraisHorsForfait']);
	$modele = new \App\Models\Modele();

	$modele->supprimerFicheHorsForfait($FHF_id);

	$this->renseignerFiche();

}


public function soumettreModificationFraisForfait($ETP,$KM,$NUI,$REP)
    {
        
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $user_id = $_SESSION['user_id'];
            $modele = new \App\Models\Modele();


            // Mettez à jour les quantités de frais forfait
            $modele->updateQuantitesFraisForfait($user_id, $ETP,$KM,$NUI,$REP);

			$this->renseignerFiche();

            // Redirigez l'utilisateur vers une autre page ou affichez un message de confirmation
            
        
    }

public function modificationFraisHorsForfait($date,$libelle,$montant,$id_fhf){
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$user_id = $_SESSION['user_id'];
	$modele = new \App\Models\Modele();

	$modele->updateFraisHorsForfait($date,$libelle,$montant,$id_fhf);

	$this->renseignerFiche();
}

//==========================
//Fin du code du controleur simple
//===========================

//fin de la classe
}



?>