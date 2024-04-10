<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Définition de l'encodage de caractères -->
    <meta charset="UTF-8">
    <!-- Spécification pour la compatibilité avec Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Configuration de la vue pour les appareils mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers la feuille de style CSS -->
    <link href="<?= base_url('public/css/style.css'); ?>" rel="stylesheet">
    <!-- Lien vers l'icône du site (favicon) -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('public/images/favicon.ico'); ?>">
    <!-- Titre de la page -->
    <title>Consulter Fiche</title>
</head>
<body>
    <!-- Barre de navigation (Navbar) -->
    <div class="navbar">
        <!-- Partie gauche de la Navbar -->
        <div class="navbar_left">
            <!-- Lien vers la page d'accueil avec le logo -->
            <a href="getdata?action=accueil"><img src="<?= base_url('public/images/logo1.png'); ?>"></a>
        </div>
        <!-- Partie droite de la Navbar -->
        <div class="navbar_right">
            <!-- Lien vers la page d'accueil -->
            <?php echo    '<div ><a href="getdata?action=accueil">Accueil</a></div>' ?>
            <!-- Lien pour se déconnecter -->
            <?php echo    '<div><a href="getdata?action=deconexion">Déconnexion</a></div>' ?>
        </div>
    </div>

    <!-- Contenu principal de la page -->
    <form method="POST" action="postdata">
        <!-- Menu déroulant pour sélectionner le mois -->
        <select class="mois" name="consulterMois">
            <option value="mois">---Mois---</option>
            <!-- Options pour les mois -->
                <option value="Janvier">Janvier</option>
                <option value="Fevrier">Février</option>
                <option value="Mars">Mars</option>
                <option value="Avril">Avril</option>
                <option value="Mai">Mai</option>
                <option value="Juin">Juin</option>
                <option value="Juillet">Juillet</option>
                <option value="Aout">Août</option>
                <option value="Septembre">Septembre</option>
                <option value="Octobre">Octobre</option>
                <option value="Novembre">Novembre</option>
                <option value="Decemebre">Décemebre</option>
        </select>
        <!-- Bouton de soumission du formulaire -->
        <input type="submit" value="Rechercher">
    </form>

    <!-- Formulaire pour les frais forfait -->
    <form action="traitement_fiche.php" method="post">
        <!-- Champs pour les frais forfait -->
        <div>
            <h1>Frais Forfait</h1>
            <label>Etape(s) :</label>
            <input type="number" value="<?php echo isset($quantites['ETP']) ? $quantites['ETP'] : ''; ?>" name="etape">
        </div>
        <div>
            <label>Kilometre(s) :</label>
            <input type="number" value="<?php echo isset($quantites['KM']) ? $quantites['KM'] : ''; ?>" name="km" >
        </div>
        <div>
            <label>Nuitée(s) :</label>
            <input type="number" value="<?php echo isset($quantites['NUI']) ? $quantites['NUI'] : ''; ?>" name="nuite" >
        </div>
        <div>
            <label>Repas :</label>
            <input type="number" value="<?php echo isset($quantites['REP']) ? $quantites['REP'] : ''; ?>" name="repas" >
        </div>
    </form>

    <!-- Afficher les données des frais hors forfait -->
    <?php foreach ($lignesFraisHorsForfait as $ligne) { ?>
        <form method="post" action="traitement_fiche.php">
            <div>
                <h1>Frais Hors Forfait</h1>
                <label>Date :</label>
                <input type="date" name="date" value="<?= $ligne['date'] ?>"><br>
            </div>
            <div>
                <label>Libellé :</label>
                <input type="text" name="libelle" value="<?= $ligne['libelle'] ?>"><br>
            </div>
            <div>
                <label>Montant :</label>
                <input type="number" step="0.01" name="montant" value="<?= $ligne['montant'] ?>"><br>
            </div>
            <p>--------------------</p>
        </form>
    <?php } ?>
</body>
</html>
