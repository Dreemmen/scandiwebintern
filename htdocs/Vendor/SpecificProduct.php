<?php
class SpecificProduct extends Product {
    public $type;
    public $params;
    public $params_template = array(
        'DVD' => ['size' => ''],
        'Furniture' => ['height' => '', 'width' => '', 'length' => ''],
        'Book' => ['weight' => '']
    );
    
    //produktu klase, kurā produkts nav vienkārši produkts, bet ir ar papilddatiem
    public function set($data){
        $data = (array) $data;
        // data validation? (prepared statemnet + input type number & requried)
        $this->type = $data['productType'];
        
        //$this->params = keep only $data indekses that matches indekses in array of specified type; keep it an dstore as json string
        $this->params = array_intersect_key($data, $this->params_template[$data['productType']]);
        $data['params'] = json_encode($this->params);
        
        return parent::set($data);
    }
    
    public function get(){
        parent::get();
    }
}
