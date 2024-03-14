<?php
class DefaultController{

    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }    

    public function Index()  {
        $products = $this->productModel->readAll();
        //trang chủ mặc định của website
        include_once 'app/views/share/index.php';        
    }
}