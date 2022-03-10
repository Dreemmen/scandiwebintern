<?php
require './autoleader.php';
autoloader_register('Database');
autoloader_register('Product');
autoloader_register('SpecificProduct');
    
$db = new Database('mysql:host=localhost;dbname=arnolds1;charset=utf8mb4;', 'user', 'password');

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = (array) json_decode($json); 

if(isset($data['action']) && $data['action'] == 'add_product'){
    unset($data['action']);
    
    $product = new SpecificProduct($db); 

    // Takes raw data from the request

    if($product->set($data)){
        echo json_encode(array(1));
    }else{
        echo json_encode(array(0));
    }
}
if(isset($data['action']) && $data['action'] == 'mass_delete'){
    unset($data['action']);
    
    // first arg must be array (means may be multiple colums. OR statement) of (string colums name from table => array of values to check against)
    if($db->delete($data, 'products')){
        echo json_encode(array(1));
    }else{
        echo json_encode(array(0));
    }
}