<?php namespace App\Moysklad\Core;
class Response{
    
    protected $response;
    public function __construct($response){
        $this->response = $response;
    }
    public function __toString(){
        return $this->response;
    }
    public function toJson(){
        return $this->response;
        // return json_decode($this->response);
    }
    public function toStd(){
        return json_decode($this->response);
    }
    public function toArray(){
        return json_decode($this->response,true);
    }
}
