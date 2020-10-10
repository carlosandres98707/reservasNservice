<?php 

//Clase para errores
class DepartamentoException extends Exception {}

class Departamento{


private $_idDepto;
private $_nomDepto;

//constructor



public function __construct($idDepto,$nomDepto)
{
    $this->_idDepto=$idDepto;
    $this->_nomDepto=$nomDepto;
}

//Metodos getters y setters
public function getIdDepto(){


    return $this->_idDepto;
}
 public function setIdDepto($idDepto){

    if($idDepto !==null && !is_numeric($idDepto)){

        throw new DepartamentoException("Error en Id de Departamento ");
    

    }
    $this->_idDepto=$idDepto;
 }

 public function getNomDepto(){

    return $this->_nomDepto;
 }

 public function setNomDepto($nomDepto){

    if($nomDepto !== null && strlen($nomDepto)>50){

        throw new DepartamentoException("Error en Nombre de Departamento ");
    }
    $this->_nomDepto=$nomDepto;


 }

//Metodo para retornar los departamentos dentro de un arreglo
 public function returnDepartamentoAsArray(){


    $depto = array();

    $depto['idDepto'] = $this->getIdDepto();
    $depto['nomDepto'] = $this->getNomDepto();

    return $depto;
}
}

?>
