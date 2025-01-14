<?php
require_once("inc/init.php");

 
if(!isset($_SESSION["user"])) {
    header("location:connection.php");
    exit();
}

$id = $_SESSION["user"]["id"];
$stmt = $pdo->query("SELECT * FROM orders WHERE member_id = '$id' ");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once("inc/header.php");

// var_dump($_SESSION["user"]);
?>

<!-- Body content -->

<div class="col-md-12 mb-5">
    <h2 class="text-center">Hi <?= $_SESSION["user"]["last_name"] . " " . $_SESSION["user"]["first_name"]; ?>, welcome to your profile !</h2>
</div>

<div class="card col-md-4">
    <?php if($_SESSION["user"]["sexe"] == "m") { ?>

        <img src="pictures/avatar_male.png" class="card-img-top" alt="...">

    <?php } else { ?>
        
        <img src="pictures/avatar_female.png" class="card-img-top" alt="...">

    <?php } ?>

    <div class="card-body">
        <h5 class="card-title"><?= $_SESSION["user"]["last_name"] . " " . $_SESSION["user"]["first_name"]; ?></h5>
    </div>

    <ul class="list-group list-group-flush">
        <li class="list-group-item text-center"><?= $_SESSION["user"]["email"]; ?></li>
        <li class="list-group-item text-center"><?= $_SESSION["user"]["address"]; ?></li>
        <li class="list-group-item text-center"><?= $_SESSION["user"]["postal_code"] . " " . $_SESSION["user"]["city"]; ?></li>
    </ul>
</div>

<div class="col-md-4">
    <ul class="list-group">
        <li class="list-group-item text-center">
            <h5>My orders</h5>
        </li>

        <?php 
            foreach ($orders as $order) {
                
                if($order["state"] == "in progress") { ?>
                    <li class="list-group-item text-center">
                        <p>Order n°<?= $order["id"]; ?> from the <?= $order["created_at"]; ?></p>
                        <p class="badge badge-warning"> <?= $order["state"]; ?></p>
                    </li>
                <?php }

            }
        ?>
    </ul>

    <ul class="list-group mt-5">
        <li class="list-group-item text-center">
            <h5>All my orders</h5>
        </li>

        <?php 
            foreach ($orders as $order) {
                
                if($order["state"] != "in progress") { ?>
                    <li class="list-group-item text-center">
                        <p>Order n°<?= $order["id"]; ?> from the <?= $order["created_at"]; ?></p>
                        <p class="badge badge-success"> <?= $order["state"]; ?></p>
                    </li>
                <?php }
            }
        ?>

    </ul>
</div>


<?php
require_once("inc/footer.php");
?>