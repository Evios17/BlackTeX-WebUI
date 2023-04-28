<?php
                //if(isset($_POST['submit'])){
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if ($_FILES['dropzone-file']['error']) {
                        switch ($_FILES['dropzone-file']['error']){
                            case 1: // UPLOAD_ERR_INI_SIZE
                                echo "Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !";
                                break;
                            case 2: // UPLOAD_ERR_FORM_SIZE
                                echo "Le fichier dépasse la limite autorisée dans le formulaire HTML !";
                                break;
                            case 3: // UPLOAD_ERR_PARTIAL
                                echo "L'envoi du fichier a été interrompu pendant le transfert !";
                                break;
                            case 4: // UPLOAD_ERR_NO_FILE
                                echo "Le fichier que vous avez envoyé a une taille nulle !";
                                break;
                        }
                    }else{
                        $target_nom = $_FILES['dropzone-file']["tmp_name"];
                        $target_dir = '/var/www/html/cours/r208/r208-web/';
                        $target_path = $target_dir . basename($target_nom);
                        //echo getcwd()."/".$target_nom;
                        if(move_uploaded_file($target_nom, $target_path)){
                            echo "Le fichier que vous avez envoyé a bien était réceptioné !";
                            $target_nom = substr($target_nom, 5);
                            // Chemin vers l'exécutable en C
                            $executable = '/var/www/html/cours/r208/r208-web/blacktex';

                            // Argument à passer au programme
                            $argument = ' -n 5 -i ' . $target_nom;

                            // Exécute le programme en C avec l'argument
                            $resultat = exec("$executable $argument", $output, $status);

                            // Affiche le résultat
                            if ($status == 0) {
                                echo "Le programme a été exécuté avec succès. Résultat : $resultat";
                            } else {
                                echo "L'exécution du programme a rencontré une erreur.";
                            }

                            // Affiche la sortie du shell
                            echo "<pre>";
                            echo implode("\n", $output);
                            echo "</pre>";
                        } else {
                            echo "Le fichier que vous avez envoyé n'a pas bien était réceptioné !";
                        }
                        
                    }
                }

                // $output=null;
                // $retval=null;
                // exec('blacktex', $output, $retval);
                // echo "Returned with status $retval and output:\n";
                // print_r($output);
            ?>