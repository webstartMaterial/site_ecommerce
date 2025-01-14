<?php

require_once("inc/init.php");

if ($_POST) {

    // La fonction htmlspecialchars convertit les caractères spéciaux en entités HTML pour empêcher 
    // l'exécution de code malveillant (comme des scripts) et protéger contre les attaques XSS (Cross-Site Scripting).

    $pseudo = htmlspecialchars(trim($_POST["pseudo"]));
    $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT);
    $first_name = htmlspecialchars(trim($_POST["firstName"]));
    $last_name = htmlspecialchars(trim($_POST["lastName"]));
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) ? htmlspecialchars(trim($_POST["email"])) : null;
    $sexe = ($_POST["sexe"] == "m" || $_POST["sexe"] == "f") ? htmlspecialchars(trim($_POST["sexe"])) : "m";
    $address = htmlspecialchars(trim($_POST["address"]));
    $city = htmlspecialchars(trim($_POST["city"]));
    $postal_code = htmlspecialchars(trim($_POST["postalCode"]));
    $status = 0;

    if ($pseudo && $password && $first_name && $last_name && $email && $sexe && $address && $city && $postal_code) {

        try {
            $sql = "INSERT INTO member (pseudo, password, first_name, last_name, email, sexe, address, city, postal_code, status)
        VALUES(:pseudo, :password, :first_name, :last_name, :email, :sexe, :address, :city, :postal_code, :status)";

            $stmt = $pdo->prepare($sql); // objet pdo statement

            $stmt->execute(
                [
                    ':pseudo' => $pseudo,
                    ':password' => $password,
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':email' => $email,
                    ':sexe' => $sexe,
                    ':address' => $address,
                    ':city' => $city,
                    ':postal_code' => $postal_code,
                    ':status' => $status
                ]
            );

            $nbrInsertedLines = $stmt->rowCount();

            if ($nbrInsertedLines > 0) {

                header("location:connection.php");

            }
        } catch (PDOException $e) {
            $msg = "<div class='alert bg-danger'>
            Une erreur est survenue : " . $e->getMessage() . "
        </div>";
        }


    } else {
        $msg = "<div class='alert bg-warning'>
            Veuillez remplir tous les champs !
        </div>";
    }


}

require_once("inc/header.php");

?>

<!-- Body content -->

<div class="col-md-12">

    <?= $msg; ?>

    <form method="POST" class="form-row">
        <!-- PSEUDO -->
        <div class="form-group col-md-6">
            <label for="pseudo">Pseudo:</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" aria-describedby="pseudo"
                placeholder="Enter your pseudo">
        </div>
        <!-- PASSWORD -->
        <div class="form-group col-md-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
        </div>
        <!-- Last Name -->
        <div class="form-group col-md-3">
            <label for="lasttName">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name">
        </div>
        <!-- First Name -->
        <div class="form-group col-md-3">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter your first name">
        </div>
        <!-- Email -->
        <div class="form-group col-md-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your first email">
        </div>

        <div class="form-grou col-md-3">
            <label for="sexe">Sexe:</label>
            <div class="form-check">
                <input class="form-check-input" name="sexe" type="radio" value="m" id="sexem">
                <label class="form-check-label" for="sexem">
                    Male
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" name="sexe" type="radio" value="f" id="sexef">
                <label class="form-check-label" for="sexef">
                    Female
                </label>
            </div>

        </div>

        <!-- Address -->
        <div class="form-group col-md-12">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter your first address">
        </div>

        <!-- CITY -->
        <div class="form-group col-md-6">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Enter your first city">
        </div>

        <!-- POSTAL CODE -->
        <div class="form-group col-md-6">
            <label for="postalCode">Postal Code</label>
            <input type="text" class="form-control" id="postalCode" name="postalCode"
                placeholder="Enter your first postal code">
        </div>

        <div class="form-group col-md-3">
            <button type="submit" class="btn btn-dark">Create my account</button>
        </div>
    </form>
</div>

<?php
require_once("inc/footer.php");
?>