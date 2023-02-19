<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$user_id = $_SESSION['user_id'];

$db = new PDO('mysql:host=localhost;dbname=nom_de_la_base_de_donnees', 'nom_d_utilisateur', 'mot_de_passe');

$balance = $db->prepare("SELECT balance FROM users WHERE id = ?");
$balance->execute([$user_id]);
$balance = $balance->fetchColumn();

$total_price = $db->prepare("SELECT SUM(price * quantity) FROM cart INNER JOIN articles ON cart.article_id = articles.id WHERE user_id = ?");
$total_price->execute([$user_id]);
$total_price = $total_price->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $db->beginTransaction();

    $insert_order = $db->prepare("INSERT INTO orders (user_id, name, email, address, total_price) VALUES (?, ?, ?, ?, ?)");
    $insert_order->execute([$user_id, $name, $email, $address, $total_price]);
    $order_id = $db->lastInsertId();

    $cart_items = $db->prepare("SELECT * FROM cart WHERE user_id = ?");
    $cart_items->execute([$user_id]);
    $cart_items = $cart_items->fetchAll();
    foreach ($cart_items as $item) {
        $article_id = $item['article_id'];
        $quantity = $item['quantity'];
        $insert_order_items = $db->prepare("INSERT INTO order_items (order_id, article_id, quantity) VALUES (?, ?, ?)");
        $insert_order_items->execute([$order_id, $article_id, $quantity]);
    }

    $update_balance = $db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $update_balance->execute([$total_price, $user_id]);

    $delete_cart_items = $db->prepare("DELETE FROM cart WHERE user_id = ?");
    $delete_cart_items->execute([$user_id]);

    $db->commit();

    $invoice = "Facture de la commande #$order_id\n\n";
    $invoice .= "Articles:\n";
    $cart_items = $db->prepare("SELECT articles.name, cart.quantity, articles.price FROM cart INNER JOIN articles ON cart.article_id = articles.id WHERE user_id = ?");
    $cart_items->execute([$user_id]);
    $cart_items = $cart_items->fetchAll();
    foreach ($cart_items as $item) {
        $article_name = $item['name'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $invoice .= "- $article_name x $quantity : $price €\n";
    }
    $invoice .= "\nTotal : $total_price €\n";

    // Envoi du email
    mail($email, 'Facture de votre commande', $invoice);

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Validation du panier</title>
</head>
<body>
    <h1>Validation du panier</h1>

    <p>Montant total de la commande : <?php echo $total_price;
