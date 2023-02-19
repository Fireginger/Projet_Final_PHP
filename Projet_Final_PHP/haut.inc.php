<!Doctype html>
<html>

<head>
    <title>Projet Final PHP</title>
    <link rel="stylesheet" href="<?php echo RACINE_SITE; ?>style.css">
    <link rel="stylesheet" href="<?php echo RACINE_SITE; ?>Connexion.css">
    <link rel="stylesheet" type="text/css" href="profil.css">
    <link rel="stylesheet" type="text/css" href="edit.css">
    <link rel="stylesheet" type="text/css" href="panier.css">
    <link rel="stylesheet" type="text/css" href="sell.css">

</head>

<body>
    <header>
        <div class="conteneur">
            <div>
                <a href="" title="Mon Site">Projet Final PHP</a>
            </div>
            <nav>
                <?php
                    if(internauteEstConnecteEtEstAdmin())
                    {
                        echo '<a href="' . RACINE_SITE . 'admin/gestion_membre.php">Gestion des membres</a>';
                        echo '<a href="' . RACINE_SITE . 'admin/gestion_commande.php">Gestion des commandes</a>';
                        echo '<a href="' . RACINE_SITE . 'admin/gestion_boutique.php">Gestion de la boutique</a>';
                    }
                    if(internauteEstConnecte())
                    {
                        echo '<a href="' . RACINE_SITE . 'profil.php">Voir votre profil</a>';
                        echo '<a href="' . RACINE_SITE . 'boutique.php">Accès à la boutique</a>';
                        echo '<a href="' . RACINE_SITE . 'sell.php">Ajouter un article</a>';
                        echo '<a href="' . RACINE_SITE . 'edit.php">Modifier un article</a>';
                        echo '<a href="' . RACINE_SITE . 'panier.php">Voir votre panier</a>';
                        echo '<a href="' . RACINE_SITE . 'connexion.php?action=deconnexion">Se déconnecter</a>';
                    }
                    else
                    {
                        echo '<a href="' . RACINE_SITE . 'inscription.php">Inscription</a>';
                        echo '<a href="' . RACINE_SITE . 'connexion.php">Connexion</a>';
                        echo '<a href="' . RACINE_SITE . 'boutique.php">Accès à la boutique</a>';
                    }
                    ?>
            </nav>
        </div>
    </header>
    <section>
        <div class="conteneur">