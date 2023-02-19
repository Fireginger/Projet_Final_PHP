<?php
require_once("init.inc.php");
//AFFICHAGE CATEGORIES
$categories_des_produits = executeRequete("SELECT DISTINCT catégorie FROM article");
$contenu .= '<div class="boutique-gauche">';
$contenu .= "<ul>";
while($cat = $categories_des_produits->fetch_assoc())
{
    $contenu .= "<li><a href='?catégorie=" . $cat['catégorie'] . "'>" . $cat['catégorie'] . "</a></li>";
}
$contenu .= "</ul>";
$contenu .= "</div>";
//AFFICHAGE PRODUITS
$contenu .= '<div class="boutique-droite">';
if(isset($_GET['catégorie']))
{
    $donnees = executeRequete("select id,name,description,price,image_link from article where catégorie='$_GET[catégorie]'");  
    while($produit = $donnees->fetch_assoc())
    {
        $contenu .= '<div class="boutique-produit">';
        $contenu .= "<h2>$produit[name]</h2>";
        $contenu .= "<a href=\"fiche_produit.php?id=$produit[id]\"><img src=\"$produit[image_link]\" =\"130\" height=\"100\"></a>";
        $contenu .= "<p>$produit[price] €</p>";
        $contenu .= '<a href="fiche_produit.php?id=' . $produit['id'] . '">Voir la fiche</a>';
        $contenu .= '</div>';
    }
}
$contenu .= '</div>';
require_once("haut.inc.php");
echo $contenu;
require_once("bas.inc.php"); 
?>