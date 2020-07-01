<?php

// objet PDO permettant de se connecter à la base de données "videogame"

$pdo = new PDO(
    // data source name
    "mysql:dbname=videogame;host=localhost;charset=UTF8",
    // utilisateur
    "videogame",
    // Mot de passe 
    "umiOfUdCSuVXD10g",
    // On s'en occupe mais
    // Permet de mieux gérer les erreurs
    array(
        // Option to display an error when SQL syntax is incorrect
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    )
);
