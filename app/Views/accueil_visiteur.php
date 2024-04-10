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
    <title>Connexion</title>
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
    <div class="nom"> Bonjour <?php echo $_SESSION['nom'].' '.$_SESSION['prenom'] ?> !</div>
    <div class="container">
        <!-- Bloc 1 - Lien pour renseigner une fiche de frais -->
        <div class="block1">
            <a href="getdata?action=renseignerFiche">Renseigner une Fiche de Frais</a>
        </div>
        <!-- Bloc 2 - Lien pour consulter les fiches de frais -->
        <div class="block2">
            <a href="getdata?action=consulterFiche">Consulter les Fiches de Frais</a>
        </div>
    </div>
</body>
</html>
