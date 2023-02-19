<?php
require_once("init.inc.php");
if(isset($_GET['id']))  { $resultat = executeRequete("SELECT * FROM article INNER JOIN stock ON stock.article_id = article.id WHERE article.id = '$_GET[id]'"); }
if($resultat->num_rows <= 0) { header("location:boutique.php"); exit(); }
$produit = $resultat->fetch_assoc();
$contenu .= "<h2>Nom : $produit[name]</h2><hr><br>";
$contenu .= "<p>Catégorie: $produit[catégorie]</p>";
$contenu .= "<img src='$produit[image_link]' ='150' height='150'>";
$contenu .= "<p>Description: $produit[description]</p>";
$contenu .= "<p>Prix : $produit[price] €</p><br>";
if($produit['quantity'] > 0){
    $contenu .= "<i>Nombre de produit(s) disponible : $produit[quantity] </i><br><br>";
    $contenu .= '<form method="post" action="panier.php">';
        $contenu .= "<input type='hidden' name='id' value='$produit[id]'>";
        $contenu .= '<label for="quantity">Quantité : </label>';
        $contenu .= '<select id="quantity" name="quantity">';
            for($i = 1; $i <= $produit['quantity'] && $i <= 5; $i++){
                $contenu .= "<option>$i</option>";
            }
        $contenu .= '</select>';
        $contenu .= '<input type="submit" name="ajout_panier" value="ajout au panier">';
    $contenu .= '</form>';
}
else{
    $contenu .= 'Rupture de stock !';
}

$contenu .= "<br><a href='boutique.php?catégorie=" . $produit['catégorie'] . "'>Retour vers la séléction de " . $produit['catégorie'] . "</a>";
require_once("haut.inc.php");
echo $contenu;
require_once("bas.inc.php"); 
?> 