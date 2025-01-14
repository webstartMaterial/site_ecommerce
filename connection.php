<?php

    require_once("inc/init.php");

    if(isset($_GET["action"]) && $_GET["action"] == "disconnection" ) {
        unset($_SESSION["user"]);
    }



    if($_POST) {

        $pseudo = htmlspecialchars(trim($_POST["pseudo"]));
        $password = htmlspecialchars(trim($_POST["password"]));

        try {
            $stmt = $pdo->query("SELECT * FROM member WHERE pseudo = '$pseudo' ");
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if(password_verify($password, $member["password"])) {
    
                $_SESSION["user"]["id"] = $member["id"];
                $_SESSION["user"]["pseudo"] = $member["pseudo"];
                $_SESSION["user"]["first_name"] = $member["first_name"];
                $_SESSION["user"]["last_name"] = $member["last_name"];
                $_SESSION["user"]["sexe"] = $member["sexe"];
                $_SESSION["user"]["email"] = $member["email"];
                $_SESSION["user"]["address"] = $member["address"];
                $_SESSION["user"]["status"] = $member["status"];
                $_SESSION["user"]["city"] = $member["city"];
                $_SESSION["user"]["postal_code"] = $member["postal_code"];
    
                header("location:profile.php");
                exit();
    
            } else {
    
                $msg = "<div class='alert alert-warning'>
                    Pseudo ou mot de passe incorrect
                </div>";
    
            }
        } catch (PDOException $e) {
            $msg = "<div class='alert alert-danger'>
                Erreur serveur :" . $e->getMessage() . "
            </div>";
        }


    }

    require_once("inc/header.php");

?>

<!-- Body content -->

<div class="col-md-12">
    <h3 class="text-center mb-5"> Get connected to access your profile !</h3>
</div>

<div class="col-md-5">
    <?= $msg; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="pseudo">Pseudo:</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" aria-describedby="pseudo" placeholder="Enter your pseudo">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-dark">Connection</button>
    </form>
</div>


<?php
require_once("inc/footer.php");
?>