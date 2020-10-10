<?php

//importamos lo necesario para ejecutar la conexion
require_once('db.php');
require_once('../model/Response.php');


try{

    //llamamos el metodo conectarDb DE LA CLASE db.php
 $db= DB::conectarDB();

 
}catch(PDOException $ex){
//capturamos los errores si hay
    $response= new Response();
    $response->setSucess(false);
    $response->setHtttpStatusCode(500);
    $response->addMessage("Error en conexion a Bd".$ex);
    $response->send();

    exit;


}

?>