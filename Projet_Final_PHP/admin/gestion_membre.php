<?php
require_once("../init.inc.php");

$contenu .= '<a href="?action=affichage">Affichage des utilisateurs</a><br>';

// AFFICHAGE UTILISATEURS
if(isset($_GET['action']) && $_GET['action'] == "affichage")
{
    $contenu .= '<table><tr><th>ID</th><th>Nom d\'utilisateur</th><th>Email</th><th>Solde</th><th>Photo de profil</th><th>Role</th><th>Action</th></tr>';
    $result = executeRequete("SELECT * FROM user");

    while($utilisateur = $result->fetch_assoc())
    {
        $contenu .= '<tr>';
        $contenu .= '<td>'.$utilisateur['id'].'</td>';
        $contenu .= '<td>'.$utilisateur['username'].'</td>';
        $contenu .= '<td>'.$utilisateur['email'].'</td>';
        $contenu .= '<td>'.$utilisateur['solde'].'</td>';
        $contenu .= '<td><img src="'.$utilisateur['profile_picture'].'" alt="Photo de profil"></td>';
        $contenu .= '<td>'.$utilisateur['role'].'</td>';
        $contenu .= '<td><a href="?action=changer_statut&id='.$utilisateur['id'].'">Switch status</a> | <a href="?action=supprimer&id='.$utilisateur['id'].'">Supprimer</a></td>';
        $contenu .= '</tr>';
    }
    $contenu .= '</table>';
}

//CHANGER STATUT UTILISATEUR
if(isset($_GET['action']) && $_GET['action'] == "changer_statut" && isset($_GET['id'])){

    $id = $_GET['id'];
    $stmt = $mysqli->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultat = $stmt->get_result();
    $utilisateur = $resultat->fetch_assoc();

    if ($utilisateur) {
        $nouveau_role = $utilisateur['role'] == 'administrateur' ? 'user' : 'administrateur';
        $stmt = $mysqli->prepare('UPDATE user SET role = ? WHERE id = ?');
        $stmt->bind_param('si', $nouveau_role, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo 'Le rôle de l\'utilisateur a été modifié avec succès';
        } else {
            echo 'La mise à jour du rôle de l\'utilisateur a échoué';
        }
    } else {
        echo 'Aucun utilisateur avec cet identifiant n\'a été trouvé';
    }
}

//SUPPRIMER UTILISATEUR
if(isset($_GET['action']) && $_GET['action'] == "supprimer" && isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $mysqli->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $resultat = $stmt->get_result();
    $utilisateur = $resultat->fetch_assoc();

    if ($utilisateur) {

        $stmt = $mysqli->prepare('DELETE FROM user WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo 'L\'utilisateur a été supprimé avec succès';
        } else {
            echo 'La suppression de l\'utilisateur a échoué';
        }
    } else {
        echo 'Aucun utilisateur avec cet identifiant n\'a été trouvé';
    }
}

require_once("../haut.inc.php");
echo $contenu;
require_once("../bas.inc.php");
?>