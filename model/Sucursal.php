<?php 


class SucursalException extends Exception {}

class Sucursal {

private $_idSucursal;
private $_nomSucursal;
private $_dirSucursal;
private $_telSucursal;


public function __construct($idSucursal,$nomSucursal,$dirSucursal,$telSucursal)
{
    $this->_idSucursal=$idSucursal;
    $this->_nomSucursal=$nomSucursal;
    $this->_dirSucursal=$dirSucursal;
    $this->_telSucursal=$telSucursal;
    
   
    
}

public function getIdSucursal(){

    
    return $this->_idSucursal;
}
 
public function setIdSucursal($idSucursal){
    if($idSucursal !==null && !is_numeric($idSucursal)){

        throw new SucursalException("Error en Id de Sucursal ");
    

    }
    $this->_idSucursal=$idSucursal;
}

public function getNomSucursal(){

    
    return $this->_nomSucursal;
}

public function setNomSucursal($nomSucursal){
    if($nomSucursal!== null && strlen($nomSucursal)>50){

        throw new SucursalException("Error en Nombre de Sucursal ");
    }
    $this->_nomSucursal=$nomSucursal;
    
}
 
public function getDirSucursal(){

    
    return $this->_dirSucursal;
}
 
public function setDirSucursal($dirSucursal){
    if($dirSucursal!== null && strlen($dirSucursal)>50){

        throw new SucursalException("Error en Direccion de Sucursal ");
    }
    $this->_dirSucursal=$dirSucursal;
}

public function getTelSucursal(){

    
    return $this->_telSucursal;
}
 
public function setTelSucursal($telSucursal){
    if($telSucursal !==null && !is_numeric($telSucursal)){

        throw new SucursalException("Error en Telefono de Sucursal ");
    

    }
    $this->_telSucursal=$telSucursal;
}
 
public function returnSucursalAsArray($idCiudad){

 

    $sucursal = array();

    $sucursal['idSucursal'] = $this->getIdSucursal();
    $sucursal['nomSucursal'] = $this->getNomSucursal();
    $sucursal['dirSucursal'] = $this->getDirSucursal();
    $sucursal['telSucursal'] = $this->getTelSucursal();
    $sucursal['idCiudad'] = $idCiudad;
    
    

    return $sucursal;
}



}

?>