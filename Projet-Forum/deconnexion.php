<?php

    session_name("session_connexion");
    session_start();
    session_unset();
    session_destroy();
    header('Location: register.php?keyword=connexion');
    exit;
