<?php namespace App\Moysklad;
use App\Moysklad\Core\Api;
class ProductsAttributes extends Api{
    public function __construct($args=[]){
        parent::__construct($args);
        $this->endpoint = '/entity/product/'.(isset($args["id"])?$args["id"]:'').'/attribute';
    }
}
?>
