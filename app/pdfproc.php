<?php

    // Si on accède à au script via url
    if (!defined('INCLUDED')) {

        header('HTTP/1.1 403 Forbidden');
        echo "You don't have access to this page.";
        die();

    }

    

?>