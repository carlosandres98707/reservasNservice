<?php 

class AreaException extends Exception {}

class Area  {

private $_idArea;
private $_nomArea;


public function __construct($idArea,$nomArea)
{
    $this->_idArea=$idArea;
    $this->_nomArea=$nomArea;
   
    
}

public function getIdArea(){


    return $this->_idArea;
}

public function setIdArea($idArea){

    if($idArea !==null && !is_numeric($idArea)){

        throw new AreaException("Error en Id de Area ");
    

    }
    $this->_idArea=$idArea;
}

public function getNomArea(){

    return $this->_nomArea;
}

public function setNomArea($nomArea){

    if($nomArea !== null && strlen($nomArea)>50){

        throw new AreaException("Error en Nombre del Area ");
    }
    $this->_nomArea=$nomArea;


}
 

public function returnAreaAsArray($idScucursal){

 

    $area = array();

    $area['idCiudad'] = $this->getIdArea();
    $area['nomCiudad'] = $this->getNomArea();
    $area['idSucursal']=$idScucursal;
   

    return $area;
}



}





?>