<?php 

require_once('db.php');
require_once('../model/Sucursal.php');
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

    if(array_key_exists("idSucursal",$_GET)){

       
        $idSucursal= $_GET['idSucursal'];
      
        
        if($idSucursal =='' || !is_numeric($idSucursal)){
    
            
            $response= new Response();
            $response->setSucess(false);
            $response->setHtttpStatusCode(500);
            $response->addMessage("Id Sucursal no valido");
            $response->send();
        
            exit;
        
    
        }

        if($_SERVER['REQUEST_METHOD']==='GET'){

            
    try{
        
        
        $query = $db->prepare('select id_sucursal,nom_sucursal,dir_sucursal,tel_sucursal,id_ciudad from sucursales where  id_sucursal= :idSucursal');
        $query->bindParam(':idSucursal', $idSucursal);
        $query->execute();

        $rowCount= $query->rowCount();
        
        if($rowCount === 0){

            $response= new Response();
            $response->setSucess(false);
            $response->setHtttpStatusCode(404);
            $response->addMessage("Sucursal no encontrada");
            $response->send();
        
            exit;


        }

        while($row = $query->fetch(PDO::FETCH_ASSOC)){

            $sucursal = new Sucursal ($row['id_sucursal'], $row['nom_sucursal'],$row['dir_sucursal'], $row['tel_sucursal']);
            $sucursalArray[]= $sucursal->returnSucursalAsArray($row['id_ciudad']);

        }

        

            try{
                $returnData = array();
        $returnData['nro_filas']= $rowCount;
        $returnData['sucursales']= $sucursalArray;

        $response= new Response();

            $response->setSucess(true);
            $response->setHtttpStatusCode(200);
            $response->setData($returnData);
            $response->send();
        
            exit;

            } catch(SucursalException $ex){

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
            
                $query= $db->prepare('delete from sucursales where id_sucursal= :idSucursal');
                $query->bindParam(':idSucursal',$idSucursal);
                $query->execute();
            
                $rowCount = $query->rowCount();
            
                if($rowCount===0){
            
                    $response= new Response();
                    $response->setSucess(false);
                    $response->setHtttpStatusCode(404);
                    $response->addMessage('Sucursal no encontrada');
                    $response->send();
                    exit;
            
                }
                $response= new Response();
                    $response->setSucess(true);
                    $response->setHtttpStatusCode(200);
                    $response->addMessage('Sucursal Eliminada');
                    $response->send();
                    exit;
            }catch(PDOException $ex){
            
            $response= new Response();
                    $response->setSucess(false);
                    $response->setHtttpStatusCode(500);
                    $response->addMessage('Error eliminando Sucursal,no puedes eliminar una sucursal que tenga una relacion con otra entidad');
                    $response->send();
                    exit;
            }
            }



    }



?>