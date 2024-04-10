<?php
//acces au Modele parent pour l heritage
namespace App\Models;
use CodeIgniter\Model;

//=========================================================================================
//définition d'une classe Modele (meme nom que votre fichier Modele.php) 
//héritée de Modele et permettant d'utiliser les raccoucis et fonctions de CodeIgniter
//  Attention vos Fichiers et Classes Controleur et Modele doit commencer par une Majuscule 
//  et suivre par des minuscules
//=========================================================================================
class Modele extends Model {

//==========================
// Code du modele
//==========================

public function getId($login, $password)
{
    $db = db_connect();

    $sql = 'SELECT * FROM visiteur WHERE login = ? AND mdp = ?';

    $resultat = $db->query($sql, [$login, $password]);

    // Vous pouvez vérifier si la requête a réussi et récupérer les résultats.
    if ($resultat) {
        return $resultat->getRow(); // Si vous vous attendez à un seul résultat.
        // Ou, si vous vous attendez à plusieurs résultats, utilisez $resultat->getResult() pour obtenir un tableau d'objets.
    } else {
        return null; // Aucun utilisateur trouvé.
    }
}

public function ficheFrais($_montant_etape, $_montant_km, $_montant_repas, $_montant_nuite, $id) {
    setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR', 'fr', 'fr', 'fra', 'fr_FR@euro');
    $mois = strftime("%B");

    $db = db_connect();
    
    $sql = "SELECT * FROM FicheFrais WHERE idVisiteur = ? AND mois = '$mois'";
    $verification_fiche= $db->query($sql,[$id])->getRow();

    if ($verification_fiche === null) {
        $sql = 'INSERT INTO FicheFrais VALUES(?,?,?,?,?,?)';
        $db->query($sql, [$id, $mois, 0, 0, NULL, 'CR']);
    }


    $sql = 'SELECT COUNT(*) as count FROM LigneFraisForfait WHERE idVisiteur = ? AND mois = ?';
    $resultat = $db->query($sql, [$id, $mois])->getRow();

    if ($resultat && $resultat->count == 0) {
        $sql = 'INSERT INTO LigneFraisForfait VALUES(?,?,?,?)';
        $db->query($sql, [$id, $mois, 'ETP', $_montant_etape]);
        $db->query($sql, [$id, $mois, 'KM', $_montant_km]);
        $db->query($sql, [$id, $mois, 'NUI', $_montant_nuite]);
        $db->query($sql, [$id, $mois, 'REP', $_montant_repas]);
    } else {
        $_SESSION['error_fiche'] = "Les frais du mois courant ont déjà été renseignés, veuillez les modifier ci-dessous.";
    }
}

public function ficheHorsForfait($_FHF_frais,$_FHF_date,$_FHF_montant,$id){

    $db = db_connect();
    $sql = 'INSERT INTO LigneFraisHorsForfait(idVisiteur,mois,libelle,date,montant) VALUES(?,?,?,?,?)';

    setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');
    $mois = strftime("%B");

    $db -> query($sql,[$id,$mois,$_FHF_frais, $_FHF_date,$_FHF_montant]);
    
}

public function getLignesFraisHorsForfait($id, $moisSelect) {
    $db = db_connect();
    $sql = 'SELECT id, libelle, date, montant FROM LigneFraisHorsForfait WHERE idVisiteur = ? AND mois = ?';
    $query = $db->query($sql, [$id, $moisSelect]);
    $resultat = $query->getResultArray();
    return $resultat;
}

public function getQuantitesFraisForfait($id, $moisSelect) {
    $db = db_connect();
    $typesFraisForfait = ['ETP', 'KM', 'NUI', 'REP'];
    $quantites = [];

    foreach ($typesFraisForfait as $type) {
        $sql = 'SELECT quantite FROM LigneFraisForfait WHERE idVisiteur = ? AND mois = ? AND idFraisForfait = ?';
        $query = $db->query($sql, [$id, $moisSelect, $type]);
        $result = $query->getRow();

        if ($result) {
            $quantites[$type] = $result->quantite;
        } else {
            $quantites[$type] = 0; // Ou une autre valeur par défaut si nécessaire
        }
    }


    return $quantites;
}

public function supprimerFicheHorsForfait($id) {
    $db = db_connect();

    $sql = 'DELETE FROM LigneFraisHorsForfait WHERE id = ?';
    $query = $db->query($sql, [$id]);
}

public function updateQuantitesFraisForfait($id, $ETP,$KM,$NUI,$REP){

    setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');
    $mois = strftime("%B");
    $db = db_connect();
    $sql = 'UPDATE lignefraisforfait SET quantite= ? WHERE idVisiteur = ? AND mois = ? AND idFraisForfait = ?';
    $query = $db->query($sql, [$ETP,$id,$mois,"ETP"]);
    $sql = 'UPDATE lignefraisforfait SET quantite= ? WHERE idVisiteur = ? AND mois = ? AND idFraisForfait = ?';
    $query = $db->query($sql, [$KM,$id,$mois,"KM"]);
    $sql = 'UPDATE lignefraisforfait SET quantite= ? WHERE idVisiteur = ? AND mois = ? AND idFraisForfait = ?';
    $query = $db->query($sql, [$NUI,$id,$mois,"NUI"]);
    $sql = 'UPDATE lignefraisforfait SET quantite= ? WHERE idVisiteur = ? AND mois = ? AND idFraisForfait = ?';
    $query = $db->query($sql, [$REP,$id,$mois,"REP"]);
}

public function updateFraisHorsForfait($date,$libelle,$montant,$id_fhf){

    $db = db_connect();
    $sql = 'UPDATE lignefraishorsforfait SET date = ?, libelle = ?, montant = ? WHERE id = ?';
    $query = $db->query($sql, [$date,$libelle,$montant,$id_fhf]);

}

//=========================================================================
// Fonction 1
// récupère les données BDD dans une fonction getBillets
// Renvoie la liste de tous les billets, triés par identifiant décroissant
//=========================================================================
public function getBillets() { 

//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
	$db = db_connect();

//=============================
// rédaction de la requete sql
//=============================
    $sql = 'select BIL_ID, BIL_TITRE, BIL_DATE from T_BILLET order by BIL_ID desc';
	
//=============================
// execution de la requete sql
//=============================	
    $resultat = $db->query($sql);

//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================	
    return $resultat;
   
}


//=========================================================================
// Fonction 2 
// récupère les données BDD dans une fonction getDetails
// Renvoie le détail d'un billet précédemment sélectionné par son id
//=========================================================================
public function getDetails($id) {
	
//==========================================================================================
// Connexion à la BDD en utilisant les données féninies dans le fichier app/Config/Database.php
//==========================================================================================
    $db = db_connect();	
	
//=====================================
// rédaction de la requete sql préparée
//=====================================
	$sql = 'SELECT * from T_BILLET WHERE BIL_ID=?';
	
//=====================================================
// execution de la requete sql en passant un parametre id
//=====================================================	
    $resultat = $db->query($sql, [$id]);
	
//=============================
// récupération des données de la requete sql
//=============================
	$resultat = $resultat->getResult();

//=============================
// renvoi du résultat au Controleur
//=============================		
    return $resultat;
  
}

//==========================
// Fin Code du modele
//===========================


//fin de la classe
}


?>