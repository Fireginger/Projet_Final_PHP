<?php
require_once("init.inc.php");
$query =  executeRequete("SELECT id, author_id, name, description, price, image_link, publication_date, catégorie FROM Article ORDER BY catégorie, publication_date DESC");
$result = $query;
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = htmlspecialchars($row['id']);
        $author_id = htmlspecialchars($row['author_id']);
        $name = htmlspecialchars($row['name']);
        $description = htmlspecialchars($row['description']);
        $price = htmlspecialchars($row['price']);
        $image_link = htmlspecialchars($row['image_link']);
        $publication_date = htmlspecialchars($row['publication_date']);
        $catégorie = htmlspecialchars($row['catégorie']);
        $contenu .= '<div class="article-item">';
        $contenu .= '<h2>'.$name.'</h2>';
        $contenu .= '<p>Catégorie: '.$catégorie.'</p>';
        $contenu .= '<p>Description: '.$description.'</p>';
        $contenu .= '<p>Price: '.$price.'</p>';
        $contenu .= '<p>Publication Date: '.$publication_date.'</p>';
        $contenu .= '<img src="'. $image_link .'" alt="'. $name .'" />';
        $contenu .= '</div>';
    }
} else {
    echo '<p>No articles found.</p>';
}
require_once("haut.inc.php");
echo $contenu;
require_once("bas.inc.php");
?>