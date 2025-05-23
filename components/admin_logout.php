<?php

// Inclusion de la classe de connexion
require_once '../models/Database.php'; 

// Définition de la classe Session pour gérer les opérations liées aux sessions
class Session {
    public static function start() {
        session_start();
    }

    public static function destroy() {
        session_unset();
        session_destroy();
    }

    public static function redirect($url) {
        header('Location: ' . $url);
        exit(); // Pour s'assurer que le script s'arrête ici
    }
}

// Appel des méthodes statiques pour gérer la session
Session::start();
Session::destroy();

// Redirection vers la page de login
Session::redirect('../views/admin_login.php');

?>
