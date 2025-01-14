<?php



require_once("inc/init.php");

/////////// AJOUT AU PANIER
if (isset($_POST["addToCart"])) {


    $stmt = $pdo->query("SELECT * FROM product WHERE id = '$_POST[id_product]' ");
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    add_product($product, $_POST["quantity"]);


}

/////////// VIDER LE PANIER
if (isset($_GET['action']) && $_GET['action'] == "emptyCart") {
    unset($_SESSION["cart"]);

    $msg = "<div class='alert alert-success'>
        Votre panier a bien été vidé !
    </div>";

}

//////////// ENLEVER UN PRODUIT DU PANIER
if (isset($_GET['action']) && $_GET['action'] == "delete") {

    deleteProductFromCart($_GET["id_product"]);

}

//////////// PAIEMENT

if(isset($_POST["pay"])) {

    if(isUserConnected()) {

        // parcourir tous les produits que j'ai au panier
        for ($i = 0; $i < count($_SESSION["cart"]["id_product"]); $i++) {
        
            $id = $_SESSION["cart"]["id_product"][$i];
            // vérifier si la quantité séléctionné est dispo en bdd pour ce produit
            $stmt = $pdo->query("SELECT * FROM product WHERE id = '$id' ");
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // si j'ai plus de stock pour ce produit, faut que je l'enlève du panier avec un msg
        
            if ($product["stock"] <= 0) {
                $msg .= "<div class='alert alert-danger'>
                    Le produit " . $product["title"] . " n'est plus disponible et a été retiré de votre panier !
                </div>";
        
                deleteProductFromCart($id);
            } else if ($product["stock"] < $_SESSION["cart"]["quantity"][$i]) {
        
                $msg .= "<div class='alert alert-warning'>
                    La quantité disponible pour le produit " . $product["title"] . " a été mise à jour dans votre panier !
                </div>";
        
                $_SESSION["cart"]["quantity"][$i] = $product["stock"];
        
            }
        
        }
    
        // si j'ai du stock mais pas assez de quantité, faut que je change la quanité avec un msg
        if(empty($msg)) {
    
            // et si tout va bien tous les produits sont encore dispo
            // je génère une commande 
            $sql = "INSERT INTO orders (amount, created_at, state, member_id) VALUES(:amount, NOW(), :state, :member_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":amount" => totalCartAmount(),
                ":state" => "in progress",
                ":member_id" => $_SESSION["user"]["id"],
            ]);
    
            $nbrOrderInserted = $stmt->rowCount();
    
            // je récupère l'id de la commande qui vient d'être inséré en BDD
            $lastOrderId = $pdo->lastInsertId();
    
            // avec détail de commande
            for ($i = 0; $i < count($_SESSION["cart"]["id_product"]); $i++) {
    
                $id_product = $_SESSION["cart"]["id_product"][$i];
                $quantity = $_SESSION["cart"]["quantity"][$i];
                $price = $_SESSION["cart"]["price"][$i];
    
                $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                VALUES(:order_id, :product_id, :quantity, :price)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ":order_id" => $lastOrderId, 
                    ":product_id" => $id_product, 
                    ":quantity" => $quantity, 
                    ":price" => $price
                ]);
    
                // une fois que la commande et le détail de commande son générées faut que je mette à jour
                // les quantités restantes au niveau du stock pour chaque produit
    
                $pdo->exec("UPDATE product SET stock = stock - $quantity WHERE id = '$id_product' ");
    
            }
    
            if($nbrOrderInserted > 0) {
                $msg = "<div class='alert alert-success'>
                    Votre commande a bien été générée avec le n° $lastOrderId !
                </div>";
    
                unset($_SESSION["cart"]); // je vide le panier car la commande est passée !
            }
        }

    } else {

        $msg = "<div class='alert alert-warning'>
            Veuillez vous connecter pour passer un achat !
        </div>";

    }


}




require_once("inc/header.php");

?>

<!-- Body content -->

<?= $msg; ?>

<?php if (isset($_SESSION["cart"]) && count($_SESSION["cart"]["id_product"]) > 0) { ?>

    <div class="col-md-12">
        <a class="badge badge-danger" href="?action=emptyCart">Empty shopping cart</a>
    </div>

<?php } ?>

<table class="table my-5">
    <thead>
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Quantity</th>
            <th scope="col">Price</th>
            <th scope="col">Photo</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>

        <?php if (isset($_SESSION["cart"]) && count($_SESSION["cart"]["id_product"]) > 0) {

            for ($i = 0; $i < count($_SESSION["cart"]["id_product"]); $i++) { ?>
                <tr>
                    <td><?= $_SESSION["cart"]["title"][$i]; ?></td>
                    <td>
                        <?= $_SESSION["cart"]["quantity"][$i]; ?>
                        <!-- <form action="">
                                <select class="form-control" id="exampleFormControlSelect1">
                                    <option selected value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </form> -->
                    </td>
                    <td><?= $_SESSION["cart"]["price"][$i]; ?>€</td>
                    <td><img style="width:50px" src="pictures/<?= $_SESSION["cart"]["picture"][$i]; ?>"
                            alt="<?= $_SESSION["cart"]["title"][$i]; ?>"></td>
                    <td>
                        <a href="?action=delete&id_product=<?= $_SESSION["cart"]["id_product"][$i]; ?>">Delete</a>
                    </td>
                </tr>

            <?php }
        } ?>

        <tr>
            <td colspan="5" class="text-right"><strong>Total amount :</strong> <?= totalCartAmount(); ?>€</td>
        </tr>
    </tbody>
</table>

<div class="col-md-12">
    <a class="badge badge-dark" href="index.php">Go back to t-shirt category</a>
</div>

<div class="d-flex justify-content-end col-md-12">
    <form action="" method="POST">
        <input type="submit" name="pay" value="Pay" class="btn btn-outline-secondary">
    </form>
</div>

<?php
require_once("inc/footer.php");
?>