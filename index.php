<?php

require_once("inc/init.php");
require_once("inc/header.php");

//////////////////////// RÉCUPÉRER LES CATÉGORIES

$stmt = $pdo->query("SELECT DISTINCT(category) FROM product");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


if(isset($_GET["category"])) {

    $category = $_GET["category"];
    $stmt = $pdo->query("SELECT * FROM product WHERE category = '$category' ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

}

// echo '<pre>';
// var_dump($categories);
// echo '</pre>';

?>

<!-- Body content -->

<div class="col-md-3">
    <ul class="list-group">

        <?php foreach($categories as $category) { ?>

            <li class="list-group-item">
                <a class="text-dark" href="?category=<?= $category["category"]; ?>"> <?= $category["category"]; ?> </a>
            </li>

        <?php } ?>

    </ul>
</div>

<div class="row col-md-9">

    

    <?php 

        if(isset($_GET['category'])) {

            foreach ($products as $product) { ?>

                <div class="col-md-4 pr-2 pl-2 pb-2">
                    <div class="card">
                        <img src="pictures/<?= $product["picture"]; ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title text-center"><?= $product["title"]; ?></h5>
                            <p class="card-text text-center"><?= $product["description"]; ?></p>
                            <a href="product_info.php?id_product=<?= $product["id"]; ?>" class="btn btn-dark d-flex justify-content-center">See product</a>
                        </div>
                    </div>
                </div>

            <?php }
        
        } else {
            echo "<p> Veuillez sélectionner une catégorie !</p>";
        } ?>
        
        

</div>

<?php
require_once("inc/footer.php");
?>