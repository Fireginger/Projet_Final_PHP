<?php require_once("init.inc.php");
if($_POST){
    debug($_POST);
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['username']); 
    if(!$verif_caractere && (strlen($_POST['username']) < 1 || strlen($_POST['username']) > 20) ){
        $contenu .= "<div class='erreur'>L'username doit contenir entre 1 et 20 caractères. <br> Caractère accepté : Lettre de A à Z et chiffre de 0 à 9</div>";
    }
    else{
        $membre = executeRequete("SELECT * FROM user WHERE username='$_POST[username]'");
        if($membre->num_rows > 0){
            $contenu .= "<div class='erreur'>Username indisponible. Veuillez en choisir un autre svp.</div>";
        }
        else{
            foreach($_POST as $indice => $valeur){
                $_POST[$indice] = htmlEntities(addSlashes($valeur));
            }
            executeRequete("INSERT INTO user (username, password, email) VALUES ('$_POST[username]', '$_POST[password]', '$_POST[email]')");
            $contenu .= "<div class='validation'>Vous êtes inscrit à notre site web. <a href=\"connexion.php\"><u>Cliquez ici pour vous connecter</u></a></div>";
        }
    }
}
?>
<?php require_once("haut.inc.php"); ?>
<?php echo $contenu; ?>
<form method="post" action="">
    <label for="username">Username</label><br>
    <input type="text" id="username" name="username" maxlength="20" pattern="[a-zA-Z0-9-_.]{1,20}" title="caractères acceptés : a-zA-Z0-9-_." required="required"><br><br>
          
    <label for="password">Password</label><br>
    <input type="password" id="password" name="password" required="required"><br><br>
  
    <label for="email">Email</label><br>
    <input type="email" id="email" name="email" placeholder="exemple@gmail.com"><br><br>
     
    <input type="submit" name="inscription" value="S'inscrire">
</form>
<?php require_once("bas.inc.php"); ?>