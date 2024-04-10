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
    <title>Renseigner fiche</title>
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
    <h1 class="titre">Entrer Fiche de Frais</h1>
    <div class="container">
        <!-- Bloc 1 - Formulaire pour la fiche de frais -->
        <div class="block1">
            <h1>Fiche Frais</h1>
            <form action="postdata" method="post">
                <div>
                    <label>Etape(s) :</label>
                    <input type="number" placeholder="Montant" name="etape" value="0">
                </div>
                <div>
                    <label>Kilometre(s) :</label>
                    <input type="number" placeholder="Montant" name="km" value="0">
                </div>
                <div>
                    <label>Nuitée(s) :</label>
                    <input type="number" placeholder="Montant" name="nuite" value="0">
                </div>
                <div>
                    <label>Repas :</label>
                    <input type="number" placeholder="Montant" name="repas" value="0">
                </div>
                <div class="sub">
                    <input type="submit" value="Ajouter" name="submit_ff">
                </div>
            </form>
            <!-- Affichage d'un message d'erreur s'il y en a un dans la session -->
            <?php if(isset($_SESSION['error_fiche'])): ?>
                <div class="error-message"><?= esc($_SESSION['error_fiche']); ?></div>
            <?php endif; ?>
        </div>
        <!-- Bloc 2 - Formulaire pour les frais hors forfait -->
        <div class="block2">
            <h1>Fiche Hors Forfait</h1>
            <form action="postdata" method="post">
                <div>
                    <input type="text" placeholder="Frais" name="frais_h">
                </div>
                <div>
                    <input type="date" placeholder="Date" name="date_h">
                </div>
                <div>
                    <input type="number" placeholder="Montant" name="montant_h">
                </div>
                <div class="sub">
                    <input type="submit" value="Ajouter" name="submit_fhf">
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div class="container_2">
        <div class="item">
            <h1>Frais Forfait</h1>
            <form action="postdata" method="post">
                <div>
                    <label>Etape(s) :</label>
                    <input type="number" value="<?php echo $quantites['ETP'] ?>" name="modif_etape">
                </div>
                <div>
                    <label>Kilometre(s) :</label>
                    <input type="number" value="<?php echo $quantites['KM'] ?>" name="modif_km">
                </div>
                <div>
                    <label>Nuitée(s) :</label>
                    <input type="number" value="<?php echo $quantites['NUI'] ?>" name="modif_nuite">
                </div>
                <div>
                    <label>Repas :</label>
                    <input type="number" value="<?php echo $quantites['REP'] ?>" name="modif_repas">
                </div>
                <div class="sub">
                    <input type="submit" value="modifierFraisForfait" name="modif_ff">
                </div>
            </form>
            <h1>Frais Hors Forfait</h1>
            <?php
            foreach ($lignesFraisHorsForfait as $ligne) {
            ?>
                <form method="post" action="postdata">
                    <div>
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
                    <input type="hidden" name="id_fhf" value="<?= $ligne['id'] ?>">
                    <div>
                        <input type="submit" name="modifierFraisHorsForfait" value="Modifier">
                    </div>
                </form>
                <form method="post" action="postdata">
                    <input type="hidden" name="idFraisHorsForfait" value="<?= $ligne['id'] ?>">
                        <div>
                            <input type="submit" name="supprimerFiche" value="Supprimer">
                            </div>
                    <p>--------------------</p>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
</body>
</html>
