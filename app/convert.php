<?php

    // Si on accède au script via url
    if (!defined('INCLUDED')) {

        header('HTTP/1.1 403 Forbidden');
        echo "You don't have access to this page.";
        die();

    }

    // ÉTAPE 1 -  Vérification des options de requête de base (méthode, fichier présent)

    // Si le fichier "dropzone-file" n'existe pas
    if (!isset($_FILES['dropzone-file'])) {

        $return['status'] = "ERROR";
        $return['message'] = "File is missing";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // S'il y a une erreur avec le fichier
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

    $uploadext = substr($_FILES['dropzone-file']['name'], -4);

    if ($uploadext !== ".pgn") {

        $return['status'] = "ERROR";
        $return['message'] = "Wrong file extension type.";

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

        switch ($_POST['pdf']) {

            case 1 :
            case "true" :
                $pdf = true;
                break;
            case 0 :
            case "false" :
                $pdf = false;
                break;
            default :
                $invalid = true;
                break;

        }

    }

    if (isset($_POST['nonags'])) {

        switch ($_POST['nonags']) {

            case 1 :
            case "true" :
                $nonags = true;
                break;
            case 0 :
            case "false" :
                $nonags = false;
                break;
            default :
                $invalid = true;
                break;

        }

    }

    if (isset($_POST['counts'])) {

        $counts = intval($_POST['counts']);
        
        if ($counts <= 0) $invalid = true;

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

    $name = basename($_FILES['dropzone-file']['name'], ".pgn");                 // Nom de fichier sans l'extension

    // Création du dossier temporaire
    if (!mkdir(__DIR__.'/data/'.$tmp_name.'/')) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying create the processing folder.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    /// On créer un "fichier témoin" pour logger la date de création du traitement, qui sera utilisé plus tard pour supprimer le dossier si le temps est dépassé
    // Array JSON du fichier témoin
    $info = [

        "status" => "IMCOMPLETE",
        "pdf" => null,
        "name" => $name,
        "created" => time()

    ];

    // On place le fichier témoin dans le dossier de traitement
    file_put_contents(__DIR__.'/data/'.$tmp_name.'/info.json', json_encode($info, JSON_PRETTY_PRINT));
    //fclose(fopen(__DIR__.'/data/'.$tmp_name.'/info.json', 'a'));

    // Tentation de déplacement du fichier
    if (!move_uploaded_file($_FILES['dropzone-file']['tmp_name'], $input)) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying to move the file to the processing folder.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    /// ÉTAPE 3 - Conversion du fichier PGN

    // Tentation de conversion de PGN vers LaTeX
    if (!exec(__DIR__. '/exe/blacktex --input ' . $input . ' --output ' . $output . ' --counts ' . $counts . ' ' . $nonags, $cmdoutput, $rvalue)) {

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

    // Si l'option de conversion en PDF n'a pas été sélectionné, donner le lien vers le fichier TeX et finir l'exécution du script
    if (!$pdf) {

        rename(__DIR__ . '/data/' . $tmp_name . '/output.tex', __DIR__ . '/data/' . $tmp_name . '/' . $name . '.tex');

        // Misee à jour du fichier témoin
        $info['status'] = "COMPLETE";
        $info['created'] = time();

        file_put_contents(__DIR__.'/data/'.$tmp_name.'/info.json', json_encode($info, JSON_PRETTY_PRINT));

        $return['status'] = "SUCCESS";
        $return['message'] = "Your file has been successfully converted to LaTeX.";
        $return['content'] = [

            "links" => [

                "tex" => $uri . 'data/' . $tmp_name . '/' . $name . '.tex',
                "pdf" => false

            ]

        ];

        echo json_encode($return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit();

    }

    // On continue si l'option de conversion en PDF a été sélectionné
    
    /// Partie compliquée, on créé un processus parallèle qui convertira le fichier TeX en PDF
    /// Le processus prend du temps, on doit donc le placer dans un processus parallèle.
    /// Le client fetchera l'api avec type "pdfcheck" pour voir l'avancée de la conversion

    $descriptorspec = array(

        0 => array('pipe', "r"),
        1 => array('pipe', 'w')

    );

    // Si l'utilisateur souhaite afficher les NAGs, on utilise le compilateur qui supporte les caractères unicodes
    if (!$nonags) $process = proc_open('xelatex -output-directory=' . __DIR__ . '/data/' . $tmp_name . '/ ' . __DIR__ . '/data/' . $tmp_name . '/output.tex | tee ' . __DIR__ . '/data/' . $tmp_name . '/cmdoutput.log', $descriptorspec, $pipes);

    // Si l'utilisateur ne veut pas afficher les NAGs, on utilise le compilateur par défaut
    if ($nonags) $process = proc_open('pdflatex -output-directory=' . __DIR__ . '/data/' . $tmp_name . '/ ' . __DIR__ . '/data/' . $tmp_name . '/output.tex | tee ' . __DIR__ . '/data/' . $tmp_name . '/cmdoutput.log', $descriptorspec, $pipes);

    // Si le procéssus n'a pas été créé
    if (!$process) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying to convert the TeX file to PDF.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    rename(__DIR__ . '/data/' . $tmp_name . '/output.tex', __DIR__ . '/data/' . $tmp_name . '/' . $name . '.tex');

    // Mis à jour du fichier témoin
    $info['status'] = "IMCOMPLETE";
    $info['created'] = time();

    // Écriture
    file_put_contents(__DIR__ . '/data/' . $tmp_name . '/info.json', json_encode($info, JSON_PRETTY_PRINT));

    // On envoie au client le chemin vers le fichier tex ainsi que l'id du processus pour la convertion pdf
    $return['status'] = "SUCCESS";
    $return['message'] = "The TeX file has been created, PDF is on the way.";
    $return['content'] = [

        "links" => [

            "tex" => $uri . 'data/' . $tmp_name . '/' . $name . '.tex',
            "pdf" => $tmp_name

        ]

    ];

    echo json_encode($return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit();

?>