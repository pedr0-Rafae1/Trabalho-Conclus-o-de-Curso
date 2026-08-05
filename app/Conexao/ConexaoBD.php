<?php

class ConexaoBD{
    
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $db   = "tcc";

    public static function getConnection() {
        $conn = new mysqli(self::$host, self::$user, self::$pass, self::$db);
        
        if ($conn->connect_error) {
            die("Erro: " . $conn->connect_error);
        }
        return $conn;
    }
}

?>