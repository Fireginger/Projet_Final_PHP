<?php require_once("init.inc.php");
if(isset($_GET['action']) && $_GET['action'] == "deconnexion"){
    session_destroy();
}
if(internauteEstConnecte()){
    header("location:profil.php");
}
if($_POST)
{
    // $contenu .=  "pseudo : " . $_POST['pseudo'] . "<br>mdp : " .  $_POST['mdp'] . "";
    $resultat = executeRequete("SELECT * FROM user WHERE username='$_POST[username]'");
    if($resultat->num_rows != 0){
        // $contenu .=  '<div style="background:green">pseudo connu!</div>';
        $membre = $resultat->fetch_assoc();
        if($membre['password'] == $_POST['password']){
            //$contenu .= '<div class="validation">mdp connu!</div>';
            foreach($membre as $indice => $element){
                if($indice != 'password'){
                    $_SESSION['user'][$indice] = $element;
                }
            }
            header("location:profil.php");
        }
        else{
            $contenu .= '<div class="erreur">Erreur de Mot de passe</div>';
        }       
    }
    else{
        $contenu .= '<div class="erreur">Erreur de pseudo</div>';
    }
}
?>
<?php require_once("haut.inc.php"); ?>
<?php echo $contenu; ?>
<form method="post" action="">
    <label for="username">Username</label><br>
    <input type="text" id="username" name="username"><br> <br>
         
    <label for="password">Password</label><br>
    <input type="password" id="password" name="password"><br><br>
 
     <input type="submit" value="Se connecter">
</form>
<?php require_once("bas.inc.php"); ?>