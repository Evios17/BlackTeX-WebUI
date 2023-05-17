<?php

    // Si on accède à au script via url
    if (!defined('INCLUDED')) {

        header('HTTP/1.1 403 Forbidden');
        echo "You don't have access to this page.";
        die();

    }

    /// Script qui vérifie le temps de création des dossiers de traitement et les supprimes si périmé

    // Analyse du contenu du dosser data/
    $dirs = scandir(__DIR__.'/data/');

    // Si il y a au moins un dossier (on commence à 3 par ce que la fonction retourne "." et ".." pour les deux premiers indices, comme dans un "ls -l")
    if (count($dirs) >= 3) {

        for ($i=2; $i < count($dirs) ; $i++) {

            $indirs = [];
            $inforaw = null;
            $info = [];

            // Si le fichier indexé est un dossier
            if (is_dir(__DIR__.'/data/'.$dirs[$i].'/')) {

                // Si le fichier info.json existe à l'intérieur du dossier actuel
                if (file_exists(__DIR__.'/data/'.$dirs[$i].'/info.json')) {

                    // Scanner les autres fichiers
                    $indirs = scandir(__DIR__.'/data/'.$dirs[$i].'/');

                    // Prendre le contenu du fichier json
                    $inforaw = file_get_contents(__DIR__.'/data/'.$dirs[$i].'/info.json');

                    // Encoder les données JSON dans un array
                    $info = json_decode($inforaw, true);

                    // Si la valeure de "created" est supérieur à 1 heure depuis sa création, supprimer le contenu du dossier et le dossier lui-même
                    if ($info['created']+3600 < time()) {

                        foreach ($indirs as $file) {

                            if ($file !== '.' && $file !== '..') {
                                
                                unlink(__DIR__.'/data/'.$dirs[$i].'/'.$file);
                            }

                        }

                        rmdir(__DIR__.'/data/'.$dirs[$i].'/');
                        
                    }

                }

            }

        }

    }

?>