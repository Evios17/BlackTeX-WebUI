<?php

    // Si on accède à au script via url
    if (!defined('INCLUDED')) {

        header('HTTP/1.1 403 Forbidden');
        echo "You don't have access to this page.";
        die();

    }

    // Si l'id de convetion n'a pas été donné
    if (!isset($_POST['id'])) {

        $return["status"] = "ERROR";
        $return["message"] = "PDF converter ID is missing.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // Si le dossier n'existe pas et que le fichier n'est pas un dossier ou que le fichier info.json n'est pas présent
    if (!file_exists(__DIR__ . '/data/' . $_POST['id'] . '/') && !is_dir(__DIR__ . '/data/' . $_POST['id'] . '/')) {

        $return["status"] = "ERROR";
        $return["message"] = "The given ID does not exists.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    

    $return['status'] = "END";
    $return['message'] = "FIN DU SCRIPT";

    echo json_encode($return, JSON_PRETTY_PRINT);
    exit();

?>