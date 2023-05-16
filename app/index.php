<?php

    // Constante pour protéger l'accès des autres scripts
    define('INCLUDED', true);

    // Validate file path before including timerchk.php
    $timerchk_path = realpath(__DIR__ . '/timerchk.php');
    if ($timerchk_path === false || !is_readable($timerchk_path)) {
        die('timerchk.php file not found or not readable');
    }
    include($timerchk_path);
    
    // Définition du type de contenu sur JSON
    header("Content-Type: application/json");

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
            $convert_path = realpath(__DIR__ . '/convert.php');
            if ($convert_path === false || !is_readable($convert_path)) {
                die('convert.php file not found or not readable');
            }
            include($convert_path);
            break;
        
        // Validate file path before including pdfproc.php
        case "pdfcheck" :
            $pdfproc_path = realpath(__DIR__ . '/pdfproc.php');
            if ($pdfproc_path === false || !is_readable($pdfproc_path)) {
                die('pdfproc.php file not found or not readable');
            }
            include($pdfproc_path);
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