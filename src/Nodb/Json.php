<?php namespace App\Nodb;
class Json{
    protected $path;
    protected $content;
    protected $lastId=false;
    public function __construct($path='certificates.json'){
        $this->path = $path;
        $this->content = json_decode( file_get_contents($path) );
        // print_r($this->content);
    }
    public function get(){
        return $this->content;
    }
    public function random(){
        // $idx = random_int(0,count($this->content)-1);
        if($this->lastId === false) $this->lastId = 0;
        else $this->lastId++;
        if( $this->lastId >= count($this->content) ) $this->lastId = 0;
        $idx = $this->lastId;
        return $this->content[$idx]->value;
    }
    public function add($value){
        $cc = json_decode(json_encode($this->content),true);
        $number = count($cc);
        $cc[]=[
            "id"=>$number,
            "name"=>"name_{$number}",
            "value"=>$value,
        ];
        $this->content = json_decode(json_encode($cc),true);
        $this->_store();
    }
    public function delete($value){
        $cc = json_decode(json_encode($this->content),true);
        unset($cc[$value]);
        $this->content = json_decode(json_encode($cc),true);
        $this->_store();
    }
    protected function _store(){
        file_put_contents($this->path,json_encode($this->content,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }
}
