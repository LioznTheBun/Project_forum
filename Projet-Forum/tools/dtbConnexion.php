<?php

class dtbConnexion {


    //Incrémentation des identifiants
    private static $URL = 'mysql:host=localhost; dbname=forum; charset=utf8';
    private static $username = 'root';
    private static $password = '';
    private static $dtb;


    //Fonction getConnexion
    static function getConnexion() {
        if (dtbConnexion::$dtb == NULL) {
            dtbConnexion::$dtb = new PDO(dtbConnexion::$URL, dtbConnexion::$username, dtbConnexion::$password);
            
        }
        
        return dtbConnexion::$dtb;
    }
  
    

}

?>