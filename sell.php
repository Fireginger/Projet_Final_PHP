<?php
require_once("init.inc.php");

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
    $username = $_SESSION['user']['id'];
    $publication_date = date("Y-m-d H:i:s");
    executeRequete("REPLACE INTO article (id, name, description, price, publication_date, author_id, catégorie, image_link) values ('', '$_POST[name]', '$_POST[description]', '$_POST[price]', '$publication_date', '$username', '$_POST[catégorie]', '$photo_bdd')");
    $id = $mysqli->insert_id;
    executeRequete("INSERT INTO stock (article_id, quantity) values ('$id', '$_POST[quantity]')"); 
    $contenu .= '<div class="validation">Le produit a été ajouté</div>';
    $_GET['action'] = 'affichage';
}
$contenu .= '<a href="?action=ajout">Ajout d\'un produit</a><br><br><hr><br>';

//AFFICHAGE
require_once("haut.inc.php");
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
        <textarea name="description" id="description" placeholder="la description du produit">'; if(isset($produit_actuel['description'])) echo $produit_actuel['description']; echo '</textarea><br><br>
        <label for="price">price</label><br>
        <input type="text" id="price" name="price" placeholder="le prix du produit" value="'; if(isset($produit_actuel['price'])) echo $produit_actuel['price']; echo '" > <br><br>
        <label for="catégorie">catégorie</label><br>
        <input type="text" id="catégorie" name="catégorie" placeholder="la catégorie du produit" value="'; if(isset($produit_actuel['catégorie'])) echo $produit_actuel['catégorie']; echo '" ><br><br>
        <label for="quantity">quantity</label><br>
        <input type="text" id="quantity" name="quantity" placeholder="Nombre de produit"<br><br>
        <label for="image_link">image_link</label><br>
        <input type="file" id="image_link" name="image_link"><br><br>
        <input type="hidden" id="author_id" name="author_id" placeholder="l ID de l auteur du produit"  value="'; if(isset($produit_actuel['author_id'])) echo $produit_actuel['author_id']; echo '"> <br><br>
        ';

        if(isset($produit_actuel)){
            echo '<i>Vous pouvez uplaoder une nouvelle photo si vous souhaitez la changer</i><br>';
            echo '<img src="' . $produit_actuel['image_link'] . '"  ="90" height="90"><br>';
            echo '<input type="hidden" name="photo_actuelle" value="' . $produit_actuel['image_link'] . '"><br>';
        }
        echo '<input type="submit" value="'; echo ucfirst($_GET['action']) . ' du produit"></form>';
}
require_once("bas.inc.php"); 
?>