<?php

require_once("../inc/init.php");

////// SUPPRESSION
if(isset($_GET['action']) && $_GET['action'] == 'delete') {

    $id = $_GET["id_product"];
    $count = $pdo->exec("DELETE FROM product WHERE id = '$id'");

    if($count > 0) {
        $msg = "<div class='alert alert-warning'>
            Votre produit a bien été supprimé !
        </div>";
    }


}

////// PRÉ-REMPLISSAGE DU FORMULAIRE POUR MODIFICATION
if(isset($_GET['action']) && $_GET['action'] == 'modify') {

    $id = $_GET["id_product"];

    // pré-remplissage du formulaire

    $stmt = $pdo->query("SELECT * FROM product WHERE id = '$id'");
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($product);

}


if($_POST) {

    if (!empty($_FILES["myPicture"]["name"])) {

    
        // nom pour la photo
        $pictureName = addslashes($_FILES["myPicture"]["name"]);

        // copie le chemin de la photo sur le serveur en BDD
        // $urlPictureForDB = URL . "pictures/" . $pictureName;

        // dossier où copier l'image sur le serveur
        // /Applications/MAMP/htdocs//AFPA_2025/11_onlineShop_project_static_version/pictures/NOM_IMAGE.extension
        $pathFolder = SITE_ROOT . "pictures/" . $pictureName;

        // copier l'image sur le serveur
        copy($_FILES["myPicture"]["tmp_name"], $pathFolder);
    }

    // échapper les caractères spéciaux
    foreach ($_POST as $key => $value) {
        $_POST[$key] = addslashes($value);
    }

    // variabiliser chaque index de mon array($_POST) en variable
    extract($_POST);

    ////// MODIFICATION
    if(isset($_POST['modifyProduct'])) {
    
        $sql = "UPDATE product
                SET reference = '$reference',
                category = '$category',
                title = '$title',
                description = '$description',
                color = '$color',
                size = '$size',
                public = '$public',
                price = '$price',
                stock = '$stock' ";

        // ça veut dire que j'ai chargé une nouvelle photo pour la modification
        if(isset($pictureName)) {
            $sql .= ",picture = '$pictureName'";
        }
        $sql .= "WHERE id = '$id'";

        $count = $pdo->exec($sql);


        if($count > 0) {

            // $_SESSION["msg"] = "<div class='alert alert-warning'>
            //     Votre produit a bien été modifié !
            // </div>";
            header("location:products.php");
            // $msg = "<div class='alert alert-warning'>
            //     Votre produit a bien été modifié !
            // </div>";
        }
    
    ////// AJOUT
    } else {

        $count = $pdo->exec("INSERT INTO product (reference, category, title, description, color, size, public, price, picture, stock)
        VALUES('$reference', '$category', '$title', '$description', '$color', '$size', '$public', '$price', '$pictureName', '$stock')");
    
        if($count > 0) {
            $msg = "<div class='alert alert-warning'>
                Votre produit a bien été inséré !
            </div>";
        }
    
    }
}

// NE PAS OUBLIER DE RÉCUPÉRER LES PRODUITS A AFFICHER APRÈS TOUTE MODIFICATION
$stmt = $pdo->query("SELECT * FROM product");

$id_product = isset($_GET['action']) && $_GET["action"] == "modify" && isset($id) ? stripslashes($id) : "";
$reference = isset($_GET['action']) && $_GET["action"] == "modify" && isset($reference) ? stripslashes($reference) : "";
$price = isset($_GET['action']) && $_GET["action"] == "modify" && isset($price) ? stripslashes($price) : "";
$stock = isset($_GET['action']) && $_GET["action"] == "modify" && isset($stock) ? stripslashes($stock) : "";
$category = isset($_GET['action']) && $_GET["action"] == "modify" && isset($category) ? stripslashes($category) : "";
$public = isset($_GET['action']) && $_GET["action"] == "modify" && isset($public) ? stripslashes($public) : "";
$size = isset($_GET['action']) && $_GET["action"] == "modify" && isset($size) ? stripslashes($size) : "";
$picture = isset($_GET['action']) && $_GET["action"] == "modify" && isset($picture) ? stripslashes($picture) : "";
$color = isset($_GET['action']) && $_GET["action"] == "modify" && isset($color) ? stripslashes($color) : "";
$title = isset($_GET['action']) && $_GET["action"] == "modify" && isset($title) ? stripslashes($title) : "";
$description = isset($_GET['action']) && $_GET["action"] == "modify" && isset($description) ? stripslashes($description) : "";

require_once("inc/header.php");

?>

<?= $msg; ?>

<table class="table">
  <thead>
    <tr>

        <?php

            for($i = 0; $i < $stmt->columnCount(); $i++) {
                $infoColumn = $stmt->getColumnMeta($i);
                echo "<th>" . $infoColumn['name'] . "</th>";
            }
        ?>

        <th>Modifier</th>
        <th>Supprimer</th>

    </tr>
  </thead>
  <tbody>

    <?php foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $key => $product) {?>
        <tr>
            <?php 
                foreach ($product as $index => $info) {
                    if($index == "picture") {
                        echo "<td> <img src=../pictures/" . $info . " width='50' height='50' alt='' title='' /> </td>";
                    } else {
                        echo "<td> $info </td>";
                    }
                }

                echo "<td><a href='?id_product=" . $product["id"] . "&action=modify#myForm'>Modifier</a></td>";
                echo "<td><a href='?id_product=" . $product["id"] . "&action=delete'>Supprimer</a></td>";
            ?>
        </tr>
    <?php } ?>


  </tbody>
</table>


<br>
<br>
<br>
<br>
<br>
<form id="myForm" action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_product" value="<?= $id_product; ?>">
    <input type="hidden" name="prevPicture">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="reference">Reference</label>
            <input type="text" class="form-control" id="reference" value="<?= $reference; ?>" name="reference">
        </div>
        <div class="form-group col-md-3">
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" value="<?= $category; ?>" name="category">
        </div>
        <div class="form-group col-md-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" value="<?= $title; ?>" name="title">
        </div>
        <div class="form-group col-md-3">
            <label for="color">Color</label>
            <input type="text" class="form-control" id="color" value="<?= $color; ?>" name="color">
        </div>
        <div class="form-group col-md-3">
            <label for="size">Size</label>
            <input type="text" class="form-control" id="size" value="<?= $size; ?>" name="size">
        </div>
        <div class="form-group col-md-3">
            <label for="price">Price</label>
            <input type="text" class="form-control" id="price" value="<?= $price; ?>" name="price">
        </div>
        <div class="form-group col-md-3">
            <label for="stock">Stock</label>
            <input type="text" class="form-control" id="stock" value="<?= $stock; ?>" name="stock">
        </div>
        <div class="w-100"></div>

        <!-- FAIRE VARIABLED LE SELECTED DES INPUTS -->

        <div class="form-group col-md-2">
            <label for="public_m">Public</label>
            <div class="custom-control custom-radio">
                <input type="radio" id="public_m" name="public" class="custom-control-input" value="m" checked="">
                <label class="custom-control-label" for="public_m">Male</label>
            </div>
        </div>
        <div class="form-group col-md-2">
            <label for="public_f" style="color:transparent">Public</label>
            <div class="custom-control custom-radio">
                <input type="radio" id="public_f" name="public" class="custom-control-input" value="f">
                <label class="custom-control-label" for="public_f">Female</label>
            </div>
        </div>

        <div class="custom-file mb-5">

            <input type="file" class="custom-file-input" id="myPicture" name="myPicture">
            <label class="custom-file-label" for="myPicture">Choose a picture</label>
            <?php if (isset($_GET["action"]) && $_GET["action"] == "modify") { ?>
                <div>
                    <img width="50px" src="../pictures/<?= $picture; ?>" alt="<?= $title; ?>">
                </div>
            <?php } ?>
        </div>
        <div class="form-group col-md-12">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="<?= $description; ?>">
        </div>

        <?php if (isset($_GET["action"]) && $_GET["action"] == "modify") { ?>
            <button type="submit" class="btn btn-secondary" name="modifyProduct">Modify a product</button>
        <?php } else { ?>
            <button type="submit" class="btn btn-secondary" name="addProduct">Add a product</button>
        <?php } ?>

    </div>

</form>

<?php

require_once("inc/footer.php");

?>