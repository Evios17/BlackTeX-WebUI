<?php

    // Si on accède à au script via url
    if (!defined('INCLUDED')) {

        header('HTTP/1.1 403 Forbidden');
        echo "You don't have access to this page.";
        die();

    }

    // Si l'id de conversion n'a pas été donné
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

    // Si le fichier cmdoutput.log n'existe pas dans le dossier, il n'y a pas eu de demande de convertion en PDF
    if (!file_exists(__DIR__ . '/data/' . $_POST['id'] . '/cmdoutput.log')) {

        $return["status"] = "ERROR";
        $return["message"] = "No TeX to PDF converting process was initied with this ID.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    //$inforaw = file_get_contents(__DIR__ . '/data/' . $_POST['id'] . '/info.json');

    //$info = json_decode($inforaw, true);

    // Récupération du fichier input pour compter le nombre de ligne du fichier TeX
    $inputfile = file(__DIR__ . '/data/' . $_POST['id'] . '/output.tex');

    // Récupartion du fichier output de la commande du convertisseur LaTeX to PDF
    $cmdoutput = file(__DIR__ . '/data/' . $_POST['id'] . '/cmdoutput.log');

    $found = false;
    $string = "";
    $end = false;

    // On parcoure les dernières lignes du fichier cmdoutput.log jusqu'à trouver une référence du progrès de la conversion
    for ($i=count($cmdoutput)-1 ; $i > 0 ; $i--) {

        if ($found) break;

        // Prendre le premier mot de la ligne actuelle
        $word = strstr($cmdoutput[$i], " ", true);

        switch ($word) {

            case "Underfull":
                $string = $cmdoutput[$i];
                $found = true;
                break;
            case "Transcript":
                $end =  true;
                $found = true;
                break;
            default :
                // Ne rien faire et passer à la ligne précédente
                break;

        }

    }

    // Si on a parcouru l'output entier et qu'on a rien trouvé d'interessant, on considère que le convertisseur n'a pas encore donné de rapport.
    if (!$found) {

        $return['status'] = "WAIT";
        $return['message'] = "No output was given yet from the TeX to PDF convertor.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // Si on a trouvé le mot "Transcript" dans l'une des dernières lignes, la convertion a été effectuée.
    if ($end) {

        //rename(__DIR__ . '/data/' . $_POST['id'] . '/output.pdf', __DIR__ . '/data/' . $_POST['id'] . '/' . $name . '.pdf');

        $info['status'] = "COMPLETE";
        $info['created'] = time();

        file_put_contents(__DIR__ . '/data/' . $_POST['id'] . '/info.json');

        $return['status'] = "SUCCESS";
        $return['message'] = "The PDF has been successfully converted.";
        $return['content'] = [
    
            "links" => [
    
                "tex" => $uri . 'data/' . $_POST['id'] . '/' . $info['name'] . '.tex',
                "pdf" => $uri . 'data/' . $_POST['id'] . '/' . $info['name'] . '.pdf'
    
            ]
    
        ];

        echo json_encode($return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit();

    }

    /// À partir d'ici, on extrait les lignes et on calcule le pourcentage qui sera indicateur du progrès du convertisseur

    // On prend la ligne trouvée par le switch case et on le transforme en array avec comme délimitation chaque espace contenue dans la ligne
    $lastline = explode(" ", $string);

    // On prend le dernier élément de l'array qui doit normalement être "NOMBRE--NOMBRE" et le sépare encore dans un autre array avec comme délimitation "--"
    $actual = explode("--", $lastline[count($lastline)-1]);

    // On calcule le pourcentage sur 100 de l'avancée du convertisseur avec le nombre de lignes obtenu précédement
    $progress = ($actual[1] / count($inputfile)) * 100;

    // On envoie le progrès au client
    $return['status'] = "WAIT";
    $return['message'] = "PGN file is being converted.";
    $return['content'] = [

        "progress" => $progress

    ];

    echo json_encode($return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit();

?>