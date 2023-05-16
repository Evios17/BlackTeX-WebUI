<?php

    // Si on accède à au script via url
    if (!defined('INCLUDED')) {

        header('HTTP/1.1 403 Forbidden');
        echo "You don't have access to this page.";
        die();

    }

    /// ÉTAPE 1 -  Vérification des options de requête de base (méthode, fichier présent)

    // Si le fichier "dropzone-file" n'existe pas
    if (!isset($_FILES['dropzone-file'])) {

        $return['status'] = "ERROR";
        $return['message'] = "File missing";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // Si il y a une erreur avec le fichier
    if ($_FILES['dropzone-file']['error']) {

        switch ($_FILES['dropzone-file']['error']){
            case 1: // UPLOAD_ERR_INI_SIZE
                $err = "File is exceeding max file size policy from server.";
                break;
            case 2: // UPLOAD_ERR_FORM_SIZE
                $err = "File is exceeding max file size policy from HTML form.";
                break;
            case 3: // UPLOAD_ERR_PARTIAL
                $err = "The upload process of the file has been interrupted.";
                break;
            case 4: // UPLOAD_ERR_NO_FILE
                $err = "The file that was send as a null size.";
                break;
        }
        
        $return['status'] = "ERROR";
        $return['message'] = $err;

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    /// À PARTIR D'ICI, LES PRÉ-REQUIS DE BASE DE LA REQUÊTE SONT VALIDÉS

    // Initiation des variables d'arguments
    $invalid = false;
    $pdf = false;
    $nonags = false;
    $counts = 4;
    $nonags = "";

    /// Tester la présence de chaque variable (pas dérangeante si non présent) ; si présente, utiliser la valeure contenu dans la variable
    /// On teste aussi si le contenu de la variable est valide pour éviter les injections lors de l'appel des commandes système

    // Remplacement des valeurs si définit
    if (isset($_POST['pdf'])) {

        // Tester si le contenu correspond au même type de variable
        if (!is_bool($_POST['pdf'])) $invalid = true;
        $pdf = $_POST['pdf'];

    }

    if (isset($_POST['nonags'])) {

        if (!is_bool($_POST['nonags'])) $invalid = true;
        $nonags = $_POST['nonags'];

    }

    if (isset($_POST['counts'])) {

        if (!is_integer($_POST['counts'])) $invalid = true;
        $counts = $_POST['counts'];

    }

    // Si l'une des variables a un contenu non-valide, ne pas continuer
    if ($invalid) {

        $return['status'] = "ERROR";
        $return['message'] = "One or more parameter is invalid.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // Si $nonags == true, alors définir l'option --no-nags
    if ($nonags) $nonags = "--no-nags";

    /// ÉTAPE 2 - Création du dossier de traitement et déplacement du fichier

    // Noms des fichiers

    do {

        $tmp_name = uniqid();                                                   // Génération d'un nom du fichier temporaire à placer dans data/

    } while (file_exists(__DIR__.'/data/'.$tmp_name.'/'));                      // Si le nom de dossier existe déjà

    $input = __DIR__.'/data/'.$tmp_name.'/input.pgn';                           // Chemin vers l'input
    $output = __DIR__.'/data/'.$tmp_name.'/output.tex';                         // Chemin vers l'ouput

    // Création du dossier temporaire
    if (!mkdir("data/".$tmp_name."/")) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying create the processing folder.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    /// On créer un "fichier témoin" pour logger la date de création du traitement, qui sera utilisé plus tard pour supprimer le dossier si le temps est dépassé
    // Array JSON du fichier témoin
    $info = [

        "status" => "IMCOMPLETE",
        "created" => time()

    ];

    // On place le fichier témoin dans le dossier de traitement
    file_put_contents('data/'.$tmp_name.'/info.json', json_encode($info, JSON_PRETTY_PRINT));
    fclose(fopen('data/'.$tmp_name.'/info.json', 'a'));

    // Tentation de déplacement du fichier
    if (!move_uploaded_file($_FILES['dropzone-file']['tmp_name'], $input)) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying to move the file to the processing folder.";

        

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    /// ÉTAPE 3 - Convertion du fichier PGN

    // Tentation de convertion PGN vers LaTeX
    if (!exec(__DIR__. '/exe/blacktex --input ' . $input . ' --output ' . $output . ' --counts ' . $counts . ' '. $nonags, $cmdoutput, $rvalue)) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying to convert the PGN file.";
        $return['content'] = [

            "return-value" => $rvalue,
            "return-message" => $cmdoutput

        ];

        switch ($rvalue) {

            case 1:
                $return['content']['return-message'] = "Failed to convert the file, the content of the file might not be a valid.";
                break;

            case 127 :
                $return['content']['return-message'] = "Blacktex converter binary is missing";
                break;

            case 126 :
                $return['content']['return-message'] = "Can't exec binary or wrong file";
                break;

            default :
                break;

        }

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // Si l'option de convertion en PDF n'a pas été sélectionné, donner le lien vers le fichier TeX et finir l'exécution du script
    if (!$pdf) {

        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        $uri = $parsed_url['path'];

        $info['status'] = "COMPLETE";
        $info['created'] = time();

        file_put_contents('data/'.$tmp_name.'/info.json', json_encode($info, JSON_PRETTY_PRINT));
        fclose(fopen('data/'.$tmp_name.'/info.json', 'a'));

        $return['status'] = "SUCCESS";
        $return['message'] = "Your file has been successfully converted to LaTeX.";
        $return['content'] = [

            "links" => [

                "tex" => $_SERVER['SERVER_NAME'].$uri.'data/'.$tmp_name.'/output.tex',
                "pdf" => false

            ]

        ];

        echo json_encode($return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit();

    }

    

    // À supprimer
    $return['status'] = "END";
    $return['message'] = "FIN DU SCRIPT";

    echo json_encode($return, JSON_PRETTY_PRINT);

?>