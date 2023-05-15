<?php

    header("Content-Type: application/json");

    $return = [

        "status" => "",
        "message" => "",
        "content" => []

    ];

    // Si la méthode n'est pas la méthode POST
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {

        $return["status"] = "ERROR";
        $return["message"] = "Wrong request method given.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }


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


    /// À PARTIR D'ICI, TOUTES LES CONDITIONS DE BASE SONT REMPLIES, ON TENTE DE DÉPLACER ET CONVERTIR LE FICHIER

    $invalid = false;
    $pdf = false;
    $nonags = false;
    $counts = 4;
    $nonags = "";

    // Remplacement des valeurs si définit
    if (isset($_POST['pdf'])) {

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

    if ($invalid) {

        $return['status'] = "ERROR";
        $return['message'] = "Invalid parameters given.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    if ($nonags) $nonags = "--no-nags";

    // Noms des fichiers
    $tmp_name = uniqid();
    $final_name = $_FILES['dropzone-file']['name'];
    $input = __DIR__.'/data/'.$tmp_name.'/input.pgn';
    $output = __DIR__.'/data/'.$tmp_name.'/output.tex';

    // Création du dossier temporaire
    if (!mkdir("data/".$tmp_name."/")) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying create the processing folder.";

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

    // Json du fichier témoin
    $info = [

        "status" => "IMCOMPLETE",
        "created" => time()

    ];

    file_put_contents('data/'.$tmp_name.'/info.json', json_encode($info, JSON_PRETTY_PRINT));
    fclose(fopen('data/'.$tmp_name.'/info.json', 'a'));

    // Tentation de déplacement du fichier
    if (!move_uploaded_file($_FILES['dropzone-file']['tmp_name'], $input)) {

        $return['status'] = "ERROR";
        $return['message'] = "An error happened while trying to move the file to the processing folder.";

        

        echo json_encode($return, JSON_PRETTY_PRINT);
        die();

    }

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

    if (!$pdf) {

        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        $uri = $parsed_url['path'];

        $info['status'] = "COMPLETE";
        $info['created'] = time();

        file_put_contents('data/'.$tmp_name.'/info.json', json_encode($info, JSON_PRETTY_PRINT));
        fclose(fopen('data/'.$tmp_name.'/info.json', 'a'));

        $return['status'] = "OK";
        $return['message'] = "Your file has been converted to LaTeX.";
        $return['content'] = [

            "links" => [

                "tex" => $_SERVER['SERVER_NAME'].$uri.'/data/'.$tmpname.'/output.tex',
                "pdf" => false

            ]

        ];

        echo json_encode($return, JSON_PRETTY_PRINT);
        exit();

    }



    $return['status'] = "END";
    $return['message'] = "FIN DU SCRIPT";

    echo json_encode($return, JSON_PRETTY_PRINT);

?>