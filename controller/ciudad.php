<?php 

require_once('db.php');
require_once('../model/Ciudad.php');
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

if(array_key_exists("idCiudad",$_GET)){

       
      $idCiudad= $_GET['idCiudad'];
    
      
      if($idCiudad =='' || !is_numeric($idCiudad)){
  
          
          $response= new Response();
          $response->setSucess(false);
          $response->setHtttpStatusCode(500);
          $response->addMessage("Id Ciudad no valido");
          $response->send();
      
          exit;
      
  
      }
  
      
if($_SERVER['REQUEST_METHOD']==='GET'){

    try{
        
        
        $query = $db->prepare('select id_ciudad, nom_ciudad,id_depto from ciudades where  id_ciudad= :idCiudad');
        $query->bindParam(':idCiudad', $idCiudad);
        $query->execute();
      
        $rowCount= $query->rowCount();
        
        if($rowCount === 0){
          
            $response= new Response();
            $response->setSucess(false);
            $response->setHtttpStatusCode(404);
            $response->addMessage("Ciudad no encontrada");
            $response->send();
        
            exit;
           

        }

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
           
            $ciudad = new Ciudad ($row['id_ciudad'], $row['nom_ciudad']);
            $ciudadArray[]= $ciudad->returnCiudadAsArray($row['id_depto']);

        }

        

            try{
                $returnData = array();
        $returnData['nro_filas']= $rowCount;
        $returnData['ciudades']= $ciudadArray;

        $response= new Response();
       
            $response->setSucess(true);
            $response->setHtttpStatusCode(200);
            $response->setData($returnData);
            $response->send();
        
            exit;

            } catch(CiudadException $ex){

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
    }catch(\Throwable $th){
 
       // throw $th;

    }



}elseif($_SERVER['REQUEST_METHOD']==='DELETE'){

try{

    $query= $db->prepare('delete from ciudades where id_ciudad= :idCiudad');
    $query->bindParam(':idCiudad',$idCiudad);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount===0){

        $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(404);
        $response->addMessage('Ciudad no encontrado');
        $response->send();
        exit;

    }
    $response= new Response();
        $response->setSucess(true);
        $response->setHtttpStatusCode(200);
        $response->addMessage('Ciudad Eliminada');
        $response->send();
        exit;
}catch(PDOException $ex){

 $response= new Response();
        $response->setSucess(false);
        $response->setHtttpStatusCode(500);
        $response->addMessage('Error eliminando ciudad,no puedes eliminar una ciudad que mantenga una realacion con un registro en otra entidad');
        $response->send();
        exit;
}
}
    }


?>