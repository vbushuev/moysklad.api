<?php namespace App\Moysklad\Core;
use App\Common\Log;
use App\Moysklad\Core\Response;
class Api{
    protected $host;
    protected $user;
    protected $password;
    protected $endpoint;
    protected $http;
    public function __construct($args=[]){
        $this->host = isset($args["host"])?$args["host"]:getenv('MOYSKLAD_HOST');
        $this->user = isset($args["user"])?$args["user"]:getenv('MOYSKLAD_USER');
        $this->password = isset($args["password"])?$args["password"]:getenv('MOYSKLAD_PASSWORD');
        $this->headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Basic '.base64_encode( $this->user.':'.$this->password)
        ];
        $this->http = new \GuzzleHttp\Client([
            'verify' => false,
            'base_uri' => $this->host
        ]);
    }
    public function get($args=[]){
        $query = (isset($args["query"]))?join($args["query"],"&"):"";
        $response = $this->http->get($this->host.$this->endpoint."?".$query,[
            'headers' => $this->headers
        ]);
        $responseObject = new Response((string)$response->getBody());
        return $responseObject;
    }
    public function put($id,$args=[]){
        $response = $this->http->put($this->host.$this->endpoint."/{$id}",[
            'headers' => $this->headers,
            \GuzzleHttp\RequestOptions::JSON => $args
        ]);
        $responseObject = new Response((string)$response->getBody());
        return $responseObject;
    }
    public function post($args=[]){
        
        $response = $this->http->post($this->host.$this->endpoint,[
            'headers' => $this->headers,
            \GuzzleHttp\RequestOptions::JSON => $args
        ]);
        $responseObject = new Response((string)$response->getBody());
        return $responseObject;
    }
}
