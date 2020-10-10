<?php 


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

require_once('db.php');
require_once('../model/Area.php');
require_once('../model/Response.php');





try{
    
    $db= DB::conectarDB();
    
    }catch(PDOException $ex){
    
        $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(500);
        $response->addMessage("Error en conexion a Bd".$ex);
        $response->send();
    
        exit;
    
    
    }

if(array_key_exists("idArea",$_GET)){

       
      $idArea= $_GET['idArea'];
    
      
      if($idArea =='' || !is_numeric($idArea)){
  
          
          $response= new Response();
          $response->setSucess(false);
          $response->setHtttpStatusCode(500);
          $response->addMessage("Id Area no valido");
          $response->send();
      
          exit;
      
  
      }
  
    }    


if($_SERVER['REQUEST_METHOD']==='GET'){
   if(array_key_exists("idDepto",$_GET)){
    try{
        
        
        $query = $db->prepare('select id_area, nom_area,id_sucursal from areas where  id_area= :idArea');
        $query->bindParam(':idArea', $idArea);
        $query->execute();
      
        $rowCount= $query->rowCount();
        
        if($rowCount === 0){
          
            $response= new Response();
            $response->setSucess(false);
            $response->setHtttpStatusCode(404);
            $response->addMessage("Area no encontrada");
            $response->send();
        
            exit;
           

        }

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
           
            $area = new Area ($row['id_area'], $row['nom_area']);
            $areaArray[]= $area->returnAreaAsArray($row['id_sucursal' ]);
            
    
        }

        

           
        $returnData = array();
        $returnData['nro_filas']= $rowCount;
        $returnData['areas']= $areaArray;
        

        $response= new Response();
       
            $response->setSucess(true);
            $response->setHtttpStatusCode(200);
            $response->setData($returnData);
            $response->send();
        
            exit;

            } catch(AreaException $ex){

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
                $query= $db->prepare('select id_area, nom_area, id_sucursal from areas');
                $query->execute();
                $rowCount= $query->rowCount();
                $areaArray = array();

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                $area = new Area($row['id_area'],$row['nom_area']);
                $areaArray[]= $area->returnAreaAsArray($row['id_sucursal']);
                
                }
                $returnData = array();
                $returnData['filas_retornadas']= $rowCount;
                $returnData['areas']= $areaArray;

                    $response = new Response();
                    $response->setSucess(true);
                    $response->setHtttpStatusCode(200);
                    $response->toCache(true);
                    $response->setData($returnData);
                    $response->send();
                
                    exit;
                
            }catch(AreaException $ex){
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

    $query= $db->prepare('delete from areas where id_area= :idArea');
    $query->bindParam(':idArea',$idArea);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount===0){

        $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(404);
        $response->addMessage('Area no encontrada');
        $response->send();
        exit;

    }
    $response= new Response();
        $response->setSucess(true);
        $response->setHtttpStatusCode(200);
        $response->addMessage('Area Eliminada');
        $response->send();
        exit;
}catch(PDOException $ex){

 $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(500);
        $response->addMessage('Error eliminando area,no puedes eliminar un area que mantenga una realacion con un registro en otra entidad');
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
   
       if(!isset($jsonData->nomArea)){
   
       $response = new Response();
       $response->setSucess(false);
       $response->setHtttpStatusCode(400);
       $response->addMessage('Nombre de Area es obligatorio');
       $response->send();
       exit;
       }
   
       $newArea = new Area(null,$jsonData->nomArea);
   
       $query = $db->prepare('insert into areas (nom_area) values(:nomArea)');
       $query->bindParam(':nomArea',$jsonData->nomArea, PDO::PARAM_STR);
       $query->execute();
       $rowCount = $query->rowCount();
   
       if($rowCount===0){
           $response = new Response();
           $response->setSucess(false);
           $response->setHtttpStatusCode(400);
           $response->addMessage('Fallo creacion de Area');
           $response->send();
           exit;
   
   
   
       }
   
       $lasIdArea = $db->lastInsertId();
       $response = new Response();
       $response->setSucess(true);
       $response->setHtttpStatusCode(201);
       $response->addMessage('Area creada');
       $response->setData($lasIdArea);
       $response->send();
       exit;

    }catch(AreaException $ex){
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

