<?php

// Inclusion du fichier s'occupant de la connexion à la DB
require __DIR__.'/inc/db.php';

// Initialisation de variables (évite les "NOTICE - variable inexistante")
$videogameList = array();
$platformList = array();
$name = '';
$editor = '';
$release_date = '';
$platform = '';

// On initialise les variables pour la gestion des erreurs
$erreur = false;
$message = '';

// Si le formulaire a été soumis
if (!empty($_POST)) {
    // Récupération des valeurs du formulaire dans des variables
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $editor = isset($_POST['editor']) ? $_POST['editor'] : '';
    $release_date = isset($_POST['release_date']) ? $_POST['release_date'] : '';
    $platform = isset($_POST['platform']) ? intval($_POST['platform']) : 0;

    // On effectue des tests pour savoir si les données sont cohérentes
    // avec notre schema de base de doonées
    if (strlen($name) == 0) {
        // On défini un booléen à true pour signifier au reste du programme
        // Que les données sont incohérentes
        $erreur = true;
        // On explique ce qui ne va pas.
        $message = 'Merci de renseigner un nom';
    }

    // On teste si les données vont pouvoir s'insérer en base
    if (strlen($name) > 64) {
        $erreur = true;
        $message = 'Nom trop long !! 64 charactères max';
    }

    if ($erreur === false) {       
        // Insertion en DB du jeu video
        $insertQuery = "
            INSERT INTO videogame (name, editor, release_date, platform_id)
            VALUES ('{$name}', '{$editor}', '{$release_date}', {$platform})
        ";
        // Exec permet d'envoyer des données vers la base de données.
        $pdo->exec($insertQuery);

        // Une fois inséré, redirection vers la page "index.php" 
        // Après avoir traiter des données, on se remet dans un état "stable"
        header("Location: index.php");
        exit();
           
    }

}

// Liste des consoles de jeux
// On récupère la liste depuis la base de données
// On créé une requête SQL
$requeteSQListeConsoles = 'SELECT id, name FROM platform';

// On l'execute sur le serveur
$statementPlateform = $pdo->query($requeteSQListeConsoles);
// On convertit tout ça en un tableau de tableaux associatifs
$tempPlatformList = $statementPlateform->fetchAll(PDO::FETCH_ASSOC);

$platformList = [];
// On boucle dessus pour récréer un tableau un peu plus simple
foreach($tempPlatformList as $platform){
    $platformList[$platform['id']] = $platform['name'];
}

// On récupère les jeux vidéos en base de données
// On récupère tous les champs de la base de données
// $sql = '
//     SELECT * FROM videogame;
// ';

// Ou on les selectionne manuelement.
$sql = '
    SELECT id, name, editor, release_date, platform_id FROM videogame;
';

// Si un tri a été demandé, on réécrit la requête
if (!empty($_GET['order'])) {
    // Récupération du tri choisi
    $order = trim($_GET['order']);
    if ($order == 'name') {
        // On écrit la requête avec un tri par nom croissant
        $sql = 'SELECT id, name, editor, release_date, platform_id FROM videogame ORDER BY name ASC';

    }
    else if ($order == 'editor') {
        // On écrit la requête avec un tri par editeur croissant
        $sql = 'SELECT id, name, editor, release_date, platform_id FROM videogame ORDER BY editor ASC';

    }
}
// On exécute la requête contenue dans $sql et on récupère les valeurs dans la variable $videogameList
// Pour ceci on utilise notre objet $pdo de type PDO.
// cette classe nous fournit une methode query() nous permettant
// d'executer une requete sur la base de données.

$statementListVideoGames = $pdo->query($sql);

// Je convertis les données en un tableau associatif.
// Afin de pouvoir exploiter en PHP ces données.
$videogameList = $statementListVideoGames->fetchAll(PDO::FETCH_ASSOC);

// Inclusion du fichier s'occupant d'afficher le code HTML
// Je fais cela car mon fichier actuel est déjà assez gros
require __DIR__.'/view/videogame.php';
