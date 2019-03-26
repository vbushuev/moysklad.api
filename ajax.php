<?php
require __DIR__.'/vendor/autoload.php';
use \App\Common\Log;
use \App\Nodb\Json;
use \App\Moysklad\Products;
use \App\Moysklad\ProductsMetadata;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$requestType = isset($_REQUEST["type"])?$_REQUEST["type"]:"";
$response = [
    "request"=>$requestType,
    "success"=>true,
    "reload"=>true,
    "data"=>false
];
if($requestType == 'certificate.add'){
    $value = isset($_REQUEST["value"])?$_REQUEST["value"]:"";
    if(!empty($value)){
        $certificates = new Json();
        $certificates->add($value);
        $response["reload"]=true;
    }
}
else if($requestType == 'certificate.delete'){
    $value = isset($_REQUEST["value"])?$_REQUEST["value"]:"";
    if(!empty($value)){
        $certificates = new Json();
        $certificates->delete($value);
        $response["reload"]=true;
    }
}
else if($requestType == 'random'){
    $certificates = new Json;
    $products = new Products;
    $productsMeta = new ProductsMetadata;
    $attribute = null;
    $response["reload"]=false;
    foreach($productsMeta->get()->toStd()->attributes as $attr){
        if($attr->name=="Сертификат"){
            $attribute = $attr;
            break;
        }
    }
    if(is_null($attribute)){
        $response["success"]=false;
        $response["data"]=[
            "error"=>"no field certificate"
        ];
    }
    $dowhile = true;
    $circle = 1;
    while($dowhile){
        foreach ($products->get(["query"=>["filter=http://online.moysklad.ru/api/remap/1.1/entity/product/metadata/attributes/{$attribute->id}=;","limit=1000"]])->toStd()->rows as $pp) {
            $cert = $certificates->random();
            $add = [
                "meta"=> [
                    "href"=>$attribute->meta->href,
                    "type"=> "attributemetadata",
                    "mediaType"=> "application\/json"
                ],
                "id"=> $attribute->id,
                "name"=> "Сертификат",
                "type"=> "string",
                "value"=> $cert
            ];
            // Log::log($add);
            $product = json_decode(json_encode($pp),true);
            $product['description']=(isset($product['description'])?$product['description']:'').'#ср';
            $product['attributes']=isset($product['attributes'])?$product['attributes']:[];
            $product['attributes'][] = $add;

            $pp = [
                "product" => $product,
                "certificate" => $cert,
                "response" =>false
            ];
            try{
                $pp["response"] = $products->put($product["id"],$product);
            }
            catch(\Exception $e){
                $pp["response"] = $e->getMessage();
            }
            $response["data"][]= $pp;
        }
        if(($circle--) <=0)$dowhile = false;
    }
}
header("Content-type:application/json");
echo json_encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
