<?php

    // Constante pour protéger l'accès des autres scripts
    define('INCLUDED', true);

    // Validate file path before including timerchk.php
    require('timerchk.php');
    
    // Définition du type de contenu sur JSON
    header("Content-Type: application/json");

    // URL pour le client si succès
    $parsed_url = parse_url($_SERVER['REQUEST_URI']);
    $uri = htmlspecialchars($parsed_url['path'], ENT_QUOTES, 'UTF-8');

    // Array qui contiendra la structure de la réponse en JSON
    $return = [

        "status" => "",
        "message" => "",
        "content" => []

    ];

    // Si la méthode n'est pas la méthode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        $return["status"] = "ERROR";
        $return["message"] = "Wrong request method given.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // Si le type de requête à l'API n'a pas été renseigné
    if (!isset($_POST['type'])) {

        $return["status"] = "ERROR";
        $return["message"] = "API request type is missing.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    switch ($_POST['type']) {

        // Validate file path before including convert.php
        case "convert" :
            require('convert.php');
            break;
        
        // Validate file path before including pdfproc.php
        case "pdfcheck" :
            require('pdfcheck.php');
            break;

        // Si le type renseigné ne correspond à aucune méthode listé ci-dessus    
        default :
            $return["status"] = "ERROR";
            $return["message"] = "Wront API request type given.";

            echo json_encode($return, JSON_PRETTY_PRINT);
            die();
            break;

    }

?>