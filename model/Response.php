<?php

class Response{

    //Variables para las respuestas
    private $_success;
    private $_httpStatusCode;
    private $_messages = array();
    private $_data;
    private $_toCache= false;
    private $_responseData = array();


    
    public function setSucess($success){
     
        $this->_success=$success;

    }

    public function setHtttpStatusCode($httpStatusCode){
     
        $this->_httpStatusCode=$httpStatusCode;

    }

   public function addMessage($message){

    $this->_messages [] =$message;
   }

   public function setData($data){

    $this->_data=$data;
   }
  
   public function toCache($toCache){

    $this->_toCache=$toCache;
   }
 
   //Funcion que nos da las respuestas en formato json
   
   public function send(){

    header('Content-type: aplication/json;charset=utf-8');

    if($this->_toCache==true){

        header('Cache-control: max-age=60');

    }else {

        header('Cache-control: no cache, no-store');
    }

    if($this->_success == false || !is_numeric($this->_httpStatusCode)){

      http_response_code(500);
      $this->_responseData['statusCode']=500;
      $this->_responseData['success']=false;
      $this->addMessage("Respuesta incorrecta");
      $this->_responseData['messages']=$this->_messages;
    }else{

        http_response_code($this->_httpStatusCode);
        $this->_responseData['statusCode']=$this->_httpStatusCode;
        $this->_responseData['success']=$this->_success;
        $this->_responseData['messages']=$this->_messages;
        $this->_responseData['data']= $this->_data;

    }

   echo json_encode($this->_responseData);
   }


}
