<?php
function executeRequete($req){
    global $mysqli;
    $resultat = $mysqli->query($req);
    if(!$resultat)
    {
        die("Erreur sur la requete sql.<br>Message : " . $mysqli->error . "<br>Code: " . $req);
    }
    return $resultat;
}
function debug($var, $mode = 1){
    echo '<div style="background: orange; padding: 5px; float: right; clear: both; ">';
    $trace = debug_backtrace();
    $trace = array_shift($trace);
    echo 'Debug demandé dans le fichier : $trace[file] à la ligne $trace[line].';
    if($mode === 1)
    {
        print '<pre>'; print_r($var); print '</pre>';
    }
    else
    {
        print '<pre>'; var_dump($var); print '</pre>';
    }
    echo '</div>';
}
function internauteEstConnecte(){ 
    if(!isset($_SESSION['user'])) return false;
    else return true;
}
function internauteEstConnecteEtEstAdmin(){
    if(internauteEstConnecte() && $_SESSION['user']['role'] == "administrateur") return true;
    else return false;
}