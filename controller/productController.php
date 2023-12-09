<?php
class ProductController{
    private $_method;
    private $_data;

    function __construct($method, $data){
        $this ->_method = $method;
        $this ->_data = $data;
    }

    public function index(){
        switch($this->_method){
            case "GET":
                header('Content-Type: application/json');
                $getProduct = ProductModel::getProduct();
                $result = $getProduct;
                header('Content-Type: application/json');
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                return;

            case "POST":
                $createProduct = ProductModel::createProduct($this->_data);
                $result = $createProduct;
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                return;

            case "PUT":
                $updateProduct = ProductModel::updateProduct($this->_data);
                $result = $updateProduct;
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                return;

            case "DELETE":
                $deleteProduct = ProductModel::deleteProduct($this->_data);
                $result = $deleteProduct;
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                return;
            
            default:
                $json = array(
                    "ruta:" => "not found"
                );
                echo json_encode($json, true);
                return;
        }
    } 
}
?>