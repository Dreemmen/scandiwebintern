<?php
    //ielādē masīvu ar specific produktiem un tos glabā
class ProductCatalog {
    private $products = array();
    private $types = array();
    private $type = '';
    
    public function setProducts($data){
        $data = (array) $data;
        
        $this->products = $data;
    }
    public function getProducts(){
        if($this->type != ''){
            $to_check = $this->products;
            $check_against_value = $this->type;
            //first return resturns getProducts filtered array
            return array_filter($to_check, function ($var) use ($check_against_value) {
                //second return if for function checking by condition
                return ($var['productType'] == $check_against_value);
            });
        }else{
            return $this->products;
        }
    }
    
    public function setTypes(array $data){
        //exptected $db result array, using map to un-nest array
        $this->types = array_map('end', $data);
    }
    public function getTypes(){
        return $this->types;
    }
    
    //one type - main for catalog
    public function setType($type){
        //exptected $db result array, using map to un-nest array
        if($type == '' || in_array($type, $this->types)){
            $this->type = $type;
            return true;
        }else{
            return false;
        }
    }
    //one type - main for catalog
    public function getType(){
        return $this->type;
    }
}
