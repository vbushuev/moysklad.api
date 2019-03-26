<?php namespace App\Moysklad;
use App\Moysklad\Core\Api;
class ProductsMetadata extends Api{
    public function __construct($args=[]){
        parent::__construct($args);
        $this->endpoint = '/entity/product/metadata';
    }
}
?>
