<?php

class DB{

    private static $dbConnection;

    public static function conectarDB(){
        
        //verificar que no hayan conexiones abiertas
        if(self::$dbConnection === null){

            //Cadena conexion
           self::$dbConnection = new PDO('mysql:dbname=reservas;host=localhost;charset=utf8;','reservasUser','reservasUser');
           //Error connexion
           self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           self::$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
           return self::$dbConnection;
           //retornar conexion 

        }
    }



}



?>