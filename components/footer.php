<?php

class Footer {

    // Méthode pour afficher les liens rapides
    public static function quickLinks() {
        return "
        <div class='box'>
            <h3>quick links</h3>
            <a href='home.php'> <i class='fas fa-angle-right'></i> home</a>
            <a href='about.php'> <i class='fas fa-angle-right'></i> about</a>
            <a href='shop.php'> <i class='fas fa-angle-right'></i> shop</a>
            <a href='contact.php'> <i class='fas fa-angle-right'></i> contact</a>
        </div>";
    }

    // Méthode pour afficher les liens supplémentaires
    public static function extraLinks() {
        return "
        <div class='box'>
            <h3>extra links</h3>
            <a href='user_login.php'> <i class='fas fa-angle-right'></i> login</a>
            <a href='user_register.php'> <i class='fas fa-angle-right'></i> register</a>
            <a href='cart.php'> <i class='fas fa-angle-right'></i> cart</a>
            <a href='orders.php'> <i class='fas fa-angle-right'></i> orders</a>
        </div>";
    }

    // Méthode pour afficher les informations de contact
    public static function contactUs() {
        return "
        <div class='box'>
            <h3>contact us</h3>
            <a href='tel:1234567890'><i class='fas fa-phone'></i> +22093152</a>
            <a href='tel:11122233333'><i class='fas fa-phone'></i> +216 5470 451</a>
            <a href='mailto:shaikh@gmail.com'><i class='fas fa-envelope'></i> bloomera@gmail.com</a>
            <a href='https://www.google.com/myplace'><i class='fas fa-map-marker-alt'></i> Tunis, cité ghazella - ariana </a>
        </div>";
    }

    // Méthode pour afficher les liens de réseaux sociaux
    public static function followUs() {
        return "
        <div class='box'>
            <h3>follow us</h3>
            <a href='#'><i class='fab fa-facebook-f'></i>facebook</a>
            <a href='#'><i class='fab fa-twitter'></i>twitter</a>
            <a href='#'><i class='fab fa-instagram'></i>instagram</a>
            <a href='#'><i class='fab fa-linkedin'></i>linkedin</a>
        </div>";
    }

    // Méthode pour afficher la section du copyright
    public static function copyright() {
        return "<div class='credit'>&copy; copyright @ " . date('Y') . " by <span>ARIJ AND OUMAYMA</span> | all rights reserved!</div>";
    }

    // Méthode pour afficher l'ensemble du footer
    public static function displayFooter() {
        return "
        <footer class='footer'>
            <section class='grid'>
                " . self::quickLinks() . "
                " . self::extraLinks() . "
                " . self::contactUs() . "
                " . self::followUs() . "
            </section>
            " . self::copyright() . "
        </footer>";
    }
}

// Affichage du footer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Page</title>
    <!-- Lien vers ton fichier CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Ici, le footer sera affiché -->
    <?php
    echo Footer::displayFooter();
    ?>

</body>
</html>
