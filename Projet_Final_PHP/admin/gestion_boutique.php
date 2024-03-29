<?php
require_once("../init.inc.php");
//if is admin
if(!internauteEstConnecteEtEstAdmin()){
    header("location:../connexion.php");
    exit();
}
//supression
if(isset($_GET['action']) && $_GET['action'] == "suppression"){
    $resultat = executeRequete("SELECT * FROM stock WHERE article_id=$_GET[id]");
    if ($resultat->num_rows > 0) {
        executeRequete("DELETE FROM stock WHERE article_id=$_GET[id]");
    }
    executeRequete("DELETE FROM article WHERE id=$_GET[id]");
    $resultat = executeRequete("SELECT * FROM article WHERE id=$_GET[id]");
    $produit_a_supprimer = $resultat->fetch_assoc();
    $chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . $produit_a_supprimer['image_link'];
    if(!empty($produit_a_supprimer['image_link']) && file_exists($chemin_photo_a_supprimer)) {
        unlink($chemin_photo_a_supprimer);
    }
    $contenu .= '<div class="validation">Suppression du produit : ' . $_GET['id'] . '</div>';
    $_GET['action'] = 'affichage';
}

//enregistrements
if(!empty($_POST)){
    $photo_bdd = ""; 
    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        $photo_bdd = $_POST['photo_actuelle'];
    }
    if(!empty($_FILES['image_link']['name'])){
        $nom_photo = $_POST['name'] . '_' .$_FILES['image_link']['name'];
        $photo_bdd = RACINE_SITE . "photo/$nom_photo";
        $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . "/photo/$nom_photo"; 
        copy($_FILES['image_link']['tmp_name'],$photo_dossier);
    }
    foreach($_POST as $indice => $valeur){
        $_POST[$indice] = htmlEntities(addSlashes($valeur));
    }
    $user_id = $_SESSION['user']['id'];
    $publication_date = date("Y-m-d H:i:s");
    executeRequete("REPLACE INTO article (id, name, description, price, publication_date, author_id, catégorie, image_link) values ('', '$_POST[name]', '$_POST[description]', '$_POST[price]', '$publication_date', '$user_id', '$_POST[catégorie]', '$photo_bdd')");
    $id = $mysqli->insert_id;
    executeRequete("INSERT INTO stock (article_id, quantity) values ('$id', '$_POST[quantity]')"); 
    $contenu .= '<div class="validation">Le produit a été ajouté</div>';
    $_GET['action'] = 'affichage';
}

//liens produits
$contenu .= '<a href="?action=affichage">Affichage des produits</a><br>';
$contenu .= '<a href="?action=ajout">Ajout d\'un produit</a><br><br><hr><br>';
//produits
if(isset($_GET['action']) && $_GET['action'] == "affichage"){
    $resultat = executeRequete("SELECT * FROM article");
    $contenu .= '<h2> Affichage des Produits </h2>';
    $contenu .= 'Nombre de produit(s) dans la boutique : ' . $resultat->num_rows;
    $contenu .= '<table border="1"><tr>';

    while($colonne = $resultat->fetch_field()){    
        $contenu .= '<th>' . $colonne->name . '</th>';
    }
    $contenu .= '<th>Modification</th>';
    $contenu .= '<th>Supression</th>';
    $contenu .= '</tr>';
 
    while ($ligne = $resultat->fetch_assoc()){
        $contenu .= '<tr>';
        foreach ($ligne as $indice => $information){
            if($indice == "image_link"){
                $contenu .= '<td><img src="' . $information . '" ="70" height="70"></td>';
            }
            else{
                $contenu .= '<td>' . $information . '</td>';
            }
        }
        $contenu .= '<td><a href="?action=modification&id=' . $ligne['id'] .'"><img src="/edit.png"></a></td>';
        $contenu .= '<td><a href="?action=suppression&id=' . $ligne['id'] .'" OnClick="return(confirm(\'En êtes vous certain ?\'));"><img src="/suppression.png"></a></td>';
        $contenu .= '</tr>';
    }
    $contenu .= '</table><br><hr><br>';
}
// affichage
require_once("../haut.inc.php");
echo $contenu;
if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')){
    if(isset($_GET['id'])){
        $resultat = executeRequete("SELECT * FROM article WHERE id=$_GET[id]");
        $resultat2 = executeRequete("SELECT * FROM stock WHERE id=$_GET[id]");
        $produit_actuel = $resultat->fetch_assoc();
    }
    echo '
    <h1> Formulaire Produits </h1>
    <form method="post" enctype="multipart/form-data" action="">
        <input type="hidden" id="id" name="id" value="'; if(isset($produit_actuel['id'])) echo $produit_actuel['id']; echo '">
        <label for="name">name</label><br>
        <input type="text" id="name" name="name" placeholder="le nom du produit" value="'; if(isset($produit_actuel['name'])) echo $produit_actuel['name']; echo '"><br><br>
        <label for="description">description</label><br>
        <input type="text" id="description" name="description" placeholder="la description du produit" value="'; if(isset($produit_actuel['description'])) echo $produit_actuel['description']; echo '" ><br><br>
        <label for="price">price</label><br>
        <input type="text" id="price" name="price" placeholder="le prix du produit" value="'; if(isset($produit_actuel['price'])) echo $produit_actuel['price']; echo '" > <br><br>
        <label for="catégorie">catégorie</label><br>
        <input type="text" id="catégorie" name="catégorie" placeholder="la catégorie du produit" value="'; if(isset($produit_actuel['catégorie'])) echo $produit_actuel['catégorie']; echo '" ><br><br>
        <label for="quantity">quantity</label><br>
        <input type="text" id="quantity" name="quantity" placeholder="Nombre de produit"<br><br>
        <label for="image_link">image_link</label><br>
        <input type="file" id="image_link" name="image_link"><br><br>
        ';

        if(isset($produit_actuel)){
            echo '<i>Vous pouvez uplaoder une nouvelle photo si vous souhaitez la changer</i><br>';
            echo '<img src="' . $produit_actuel['image_link'] . '"  ="90" height="90"><br>';
            echo '<input type="hidden" name="photo_actuelle" value="' . $produit_actuel['image_link'] . '"><br>';
        }
        echo '<input type="submit" value="'; echo ucfirst($_GET['action']) . ' du produit"></form>';
}
require_once("../bas.inc.php"); ?>