<?php
require_once("init.inc.php");

if(!internauteEstConnecte()) {
    header("location:connexion.php");
    exit();
}

if(isset($_POST['ajout_panier'])){
    $getid = $_POST['id'];
    $resultat = executeRequete("SELECT * FROM article WHERE article.id = $getid");
    $articleid = executeRequete("SELECT id FROM article WHERE article.id = $getid");
    $row = $resultat->fetch_assoc();
    $articleidtest = $row['id'];
    $user_id = $_SESSION['user']['id'];
    $quantité = $_POST['quantity'];
    executeRequete("INSERT INTO cart (user_id, article_id, quantity) values ($user_id, $articleidtest , $quantité)");
}

function displayCartItems() {
    global $mysqli;
    $user_id = $_SESSION['user']['id'];
    $query = executeRequete("SELECT cart.id, cart.user_id, cart.article_id, cart.quantity, article.name, article.price, article.image_link 
              FROM cart 
              INNER JOIN article ON cart.article_id = article.id 
              INNER JOIN user ON cart.user_id = user.id 
              WHERE cart.user_id = $user_id");
    
    echo "<h1>Mon panier</h1>";
    if (!empty($query->num_rows)) {
        $row = mysqli_fetch_assoc($query);
        $cart_id = $row['id'];
        $article_id = $row['article_id'];
        $article_name = $row['name'];
        $article_price = $row['price'];
        $article_image = $row['image_link'];
        $article_quantité = $row['quantity'];
        echo '<div class="cart-item">';
        echo '<p>Article Name: '.$article_name.'</p>';
        echo '<p>Price: '.$article_price.'</p>';
        echo '<p>Quantité: '.$article_quantité.'</p>';
        echo '<img src="'.$article_image.'" alt="'.$article_name.'" />';
        echo '<a href="increase-item.php?cart_id='.$cart_id.'" class="increase-item">Increase Item</a>';
        echo '<a href="decrease-item.php?cart_id='.$cart_id.'" class="decrease-item">Decrease Item</a>';
        echo '<a href="remove-item.php?cart_id='.$cart_id.'" class="remove-item">Remove Item</a>';
        echo '</div>';
    } else {
        echo 'No items in the cart.';
    }
}

function placeOrder() {
    global $conn, $user_id;
    $query = "SELECT solde FROM User WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $balance = $row['solde'];
    $query = "SELECT SUM(Article.price) as total_price 
              FROM Cart 
              INNER JOIN Article ON Cart.article_id = Article.id 
              WHERE Cart.user_id = $user_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $total_price = $row['total_price'];
    if ($balance >= $total_price) {
        $new_balance = $balance - $total_price;
        $query = "UPDATE User SET solde = $new_balance WHERE id = $user_id";
        mysqli_query($conn, $query);
        $query = "DELETE FROM Cart WHERE user_id = $user_id";
        mysqli_query($conn, $query);
        echo 'Order placed successfully.';
    } else {
        echo 'Insufficient balance to place the order.';
    }
}

function increaseItem($cart_id) {
    global $conn;
    $query = executeRequete("UPDATE Cart SET quantity = quantity + 1 WHERE id = $cart_id");
}

function decreaseItem($cart_id) {
    global $conn;
    $query = "UPDATE Cart SET quantity = quantity - 1 WHERE id = $cart_id";
    mysqli_query($conn, $query);
}

function removeItem($cart_id) {
    global $conn;
    $query = "DELETE FROM Cart WHERE id = $cart_id";
    mysqli_query($conn, $query);
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $cart_id = $_GET['cart_id'];
    switch ($action) {
        case 'increase':
            increaseItem($cart_id);
            break;
        case 'decrease':
            decreaseItem($cart_id);
            break;
        case 'remove':
            removeItem($cart_id);
            break;
        default:
            break;
    }
}

require_once("haut.inc.php");
?>
<h2>Cart</h2>
<div class="cart-items">
    <?php displayCartItems(); ?>
</div>
<h2>Place Order</h2>
<button onclick="placeOrder()">Place Order</button>

<?php
require_once("bas.inc.php");
?>