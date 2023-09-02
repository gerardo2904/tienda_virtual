<?php

class Conexion{
    private $conect;

    public function __construct(){
        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET; 
        try{
            $this->conect = new PDO($dsn, DB_USER, DB_PASSWORD);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(Exception $e){
            $this->conect='Error de Conexion...';
            echo "ERROR: ".$e->getMessage();
        }
    }

    public function conect(){
        return $this->conect;
    }
}


?>
