<?php 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//importes correspondientes
require_once('db.php');
require_once('../model/Departamento.php');
require_once('../model/Response.php');



try{
//Conexion Base de datos
$db= DB::conectarDB();

}catch(PDOException $ex){
//Errores en la conexion a la BD
    $response= new Response();
    $response->setSucess(false);
    $response->setHtttpStatusCode(500);
    $response->addMessage("Error en conexion a Bd".$ex);
    $response->send();

    exit;


}

// Traemos el idDepto por el metodo get en postman
// Verfiicamos que exista ese valor
if(array_key_exists("idDepto",$_GET)){

      //Guardamos el valor  en la varibale idDepto
    $idDepto= $_GET['idDepto'];
  
    
    if($idDepto =='' || !is_numeric($idDepto)){

        //Si el id pasado por el metodo Get  esta vacio o esun  texto nos arrojara los siguientes mensajes de error
        $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(500);
        $response->addMessage("Id Departamento no valido");
        $response->send();
    
        exit;
    

    }

}

if($_SERVER['REQUEST_METHOD']==='GET'){
    if(array_key_exists("idDepto",$_GET)){
        try{
            
            //Ejecutamos la sentencia segun el parametro pasao por el metodo Get
            $query = $db->prepare('select id_depto, nom_depto from departamentos where  id_depto= :idDepto');
            $query->bindParam(':idDepto', $idDepto);
            $query->execute();
           //Encontar el valor en la base de datos
            $rowCount= $query->rowCount();
            
            if($rowCount === 0){
                //Si no se encuantra el numero  aparecera el siguiente mensaje
                $response= new Response();
                $response->setSucess(false);
                $response->setHtttpStatusCode(404);
                $response->addMessage("Departamento no encontrado");
                $response->send();
            
                exit;
            

            }

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
            //Guardamos los datos encontrados dentro de un arreglo
                $depto = new Departamento($row['id_depto'], $row['nom_depto']);
                $deptoArray[]= $depto->returnDepartamentoAsArray();

            }

            

            
            $returnData = array();
            $returnData['nro_filas']= $rowCount;//numero de filas encontradas
            $returnData['deptos']= $deptoArray;//llamamos el arreglo creado

            $response= new Response();
            //Instanciamos la clase de respuesta e imprimimos los valores
                $response->setSucess(true);
                $response->setHtttpStatusCode(200);
                $response->setData($returnData);
                $response->send();
            
                exit;

                } catch(DepartamentoException $ex){

                    $response= new Response();
                    $response->setSucess(false);
                    $response->setHtttpStatusCode(404);
                    $response->addMessage($ex->getMessage());
                    $response->send();
                
                    exit;


                }catch(PDOException $ex){

                    $response= new Response();
                    $response->setSucess(false);
                    $response->setHtttpStatusCode(404);
                    $response->addMessage("Error conectando a Base de datos");
                    $response->send();
                
                    exit;

                }
        }else{

            try{
                $query= $db->prepare('select id_depto, nom_depto from departamentos');
                $query->execute();
                $rowCount= $query->rowCount();
                $deptoArray = array();

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $depto = new Departamento($row['id_depto'],$row['nom_depto']);
                $deptoArray[]= $depto->returnDepartamentoAsArray();
                
                }
                $returnData = array();
                $returnData['filas_retornadas']= $rowCount;
                $returnData['deptos']= $deptoArray;

                    $response = new Response();
                    $response->setSucess(true);
                    $response->setHtttpStatusCode(200);
                    $response->toCache(true);
                    $response->setData($returnData);
                    $response->send();
                
                    exit;
                
            }catch(DepartamentoException $ex){
                $response= new Response();
                $response->setSucess(false);
                $response->setHtttpStatusCode(400);
                $response->addMessage($ex->getMessage());
                $response->send();
            
                exit;
            }catch(PDOException $ex){

                $response= new Response();
                $response->setSucess(false);
                $response->setHtttpStatusCode(500);
                $response->addMessage("Error conectando a Base de datos");
                $response->send();
            
                exit;

            }
        }
    
    }elseif($_SERVER['REQUEST_METHOD']==='DELETE'){

try{
    //Validar que el departamento no tenga ciudades relacionadas
    $query= $db->prepare('select count(*) as conteo  from ciudades where id_depto= :idDepto');
    $query->bindParam(':idDepto',$idDepto);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC)){

       $numrows = $row['conteo'];
        }

        if($numrows>0){
            
            $response= new Response();
            $response->setSucess(false);
            $response->setHtttpStatusCode(500);
            $response->addMessage("No es posible eliminar departamentos.Ciudades asosiadas");
            $response->send();
        
            exit;

        }

    $query= $db->prepare('delete from departamentos where id_depto= :idDepto');
    $query->bindParam(':idDepto',$idDepto);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount===0){

        $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(404);
        $response->addMessage('Departamento no encontrado');
        $response->send();
        exit;

    }
    $response= new Response();
        $response->setSucess(true);
        $response->setHtttpStatusCode(200);
        $response->addMessage('Departamento Eliminado');
        $response->send();
        exit;
}catch(PDOException $ex){

 $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(500);
        $response->addMessage('Error eliminando departamento');
        $response->send();
        exit;
}

}elseif($_SERVER['REQUEST_METHOD']==='POST'){

 try{

    if($_SERVER['CONTENT_TYPE'] !== 'application/json'){

    $response = new Response();
    $response->setSucess(false);
    $response->setHtttpStatusCode(400);
    $response->addMessage('Content Type no corresponde  a formato JSON');
    $response->send();
    exit;

    }

    $rawPOSTData= file_get_contents('php://input');

    if(!$jsonData = json_decode($rawPOSTData)){
        
    $response = new Response();
    $response->setSucess(false);
    $response->setHtttpStatusCode(400);
    $response->addMessage('Request Body no corresponde  a formato JSON');
    $response->send();
    exit;

    }

    if(!isset($jsonData->nomDepto)){

    $response = new Response();
    $response->setSucess(false);
    $response->setHtttpStatusCode(400);
    $response->addMessage('Nombre de departamento es obligatorio');
    $response->send();
    exit;
    }

    $newDepto = new Departamento(null,$jsonData->nomDepto);

    $query = $db->prepare('insert into departamentos (nom_depto) values(:nomDepto)');
    $query->bindParam(':nomDepto',$jsonData->nomDepto, PDO::PARAM_STR);
    $query->execute();
    $rowCount = $query->rowCount();

    if($rowCount===0){
        $response = new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(400);
        $response->addMessage('Fallo creacion de departamento');
        $response->send();
        exit;



    }

    $lasIdDepto = $db->lastInsertId();
    $response = new Response();
    $response->setSucess(true);
    $response->setHtttpStatusCode(201);
    $response->addMessage('Departamento creado');
    $response->setData($lasIdDepto);
    $response->send();
    exit;
 }catch(DepartamentoException $ex){
    $response = new Response();
    $response->setSucess(false);
    $response->setHtttpStatusCode(400);
    $response->addMessage($ex->getMessage());
    $response->send();
    exit;

 }catch(PDOException $ex){

    $response = new Response();
    $response->setSucess(false);
    $response->setHtttpStatusCode(500);
    $response->addMessage('Fallo conexion a BD');
    $response->send();
    exit;
 }
}

?>