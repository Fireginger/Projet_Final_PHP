<?php
require_once("init.inc.php");
if(!internauteEstConnecte()) header("location:connexion.php");
$contenu .= '<p class="centre">Bonjour <strong>' . $_SESSION['user']['username'] . '</strong></p>';
$contenu .= '<div class="cadre"><h2> Voici vos informations </h2>';
$contenu .= '<p> votre email est: ' . $_SESSION['user']['email'] . '<br>';
require_once("haut.inc.php");
echo $contenu;
require_once("bas.inc.php");