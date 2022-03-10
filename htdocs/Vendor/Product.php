<?php
class Product {
    private $db;
    private $fields = array('id' => '', 'sku' => '', 'name' => '', 'price' => '','productType' => '','params' => '');

    //produkts. lauki, kas ir visiem produktiem
    public function __construct($db_hook){
        $this->db = $db_hook;
    }
    public function validate($key, $value){
        switch ($key):
            case('id'):
                return (is_int($value))?$value:null;
                
            case('sku'):
                return (!empty($value))?$value:null;
                
            case('name'):
                return (!empty($value))?$value:null;
                
            case('price'):
                if(is_numeric($value)){
                    return (float) $value;
                }else{
                    return null;
                }
                
            case('productType'):
                $value = str_replace(' ', '_', $value);
                return (!empty($value))?$value:null;
                
            case('params'):
                return ($this->isJson($value))?$value:null;
                
        endswitch;
        return $value;
    }
    
    //function from internet
    public function isJson($str) {
        $json = json_decode($str);
        return $json && $str != $json;
    }
    
    public function set($data){
        //validate $data array for correct values
        foreach ($data as $key => $val){
            $val = $this->validate($key, $val);
            if($val == null){
                return null;
            }
        }
        
        //gutters $data, keeps only entries with keys present id $fields
        $this->fields = array_intersect_key($data, $this->fields);
        
        return $this->db->set($this->fields, 'products');
    }
    public function get(){
        return $this->fields();
    }
}
