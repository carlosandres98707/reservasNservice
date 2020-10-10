<?php 


class CiudadException extends Exception {}

class Ciudad {

private $_idCiudad;
private $_nomCiudad;


public function __construct($idCiudad,$nomCiudad)
{
    $this->_idCiudad=$idCiudad;
    $this->_nomCiudad=$nomCiudad;
   
    
}

public function getIdCiudad(){


    return $this->_idCiudad;
}

public function setIdCiudad($idCiudad){

    if($idCiudad !==null && !is_numeric($idCiudad)){

        throw new CiudadException("Error en Id de Ciudad ");
    

    }
    $this->_idCiudad=$idCiudad;
}

public function getNomCiudad(){

    return $this->_nomCiudad;
}

public function setNomCiudad($nomCiudad){

    if($nomCiudad !== null && strlen($nomCiudad)>50){

        throw new CiudadException("Error en Nombre de Ciudad ");
    }
    $this->_nomCiudad=$nomCiudad;


}
 

public function returnCiudadAsArray($idDepto){

 

    $ciudad = array();

    $ciudad['idCiudad'] = $this->getIdCiudad();
    $ciudad['nomCiudad'] = $this->getNomCiudad();
    $ciudad['idDepto'] = $idDepto;
    

    return $ciudad;
}



}

?>