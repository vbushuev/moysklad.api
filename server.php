<?php
require __DIR__.'/vendor/autoload.php';
phpinfo();exit;
use \App\Common\Log;
use \App\Moysklad\Products;
use \App\Moysklad\ProductsMetadata;
use \App\Moysklad\ProductsAttributes;
use \App\Nodb\Json;
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$products = new Products;
$productsMeta = new ProductsMetadata;

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
            Log::log('Done:',$pp);
        }
        catch(\Exception $e){
            Log::log('Error:',$e->getMessage());
        }
        $response["data"][]= $pp;
    }
    if(($circle--) <=0)$dowhile = false;
}
