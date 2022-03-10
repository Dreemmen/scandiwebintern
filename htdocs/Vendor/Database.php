<?php
class Database extends PDO {
    public function make_tables() {
        $this->query("
            CREATE TABLE IF NOT EXISTS `products` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sku` varchar(255) NOT NULL,
            `name` varchar(255),
            `price` FLOAT(10,2),
            `productType` ENUM('DVD', 'Furniture', 'Book') NOT NULL DEFAULT 'DVD',
            `params` text,
            PRIMARY KEY (`id`), UNIQUE (`sku`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");
    }
    
    //only valid for product table
    public function set(array $data, $table, $where = '') {
        
        if(  empty($data['id'])  ){
           $mode = 'INSERT INTO';
        }else{
           $mode =  'UPDATE';
           $where = (!empty($where))? $where : 'id =' . (int) $data['id'];
        }
        unset($data['id']); //dont need ID either way now
        $colums = array_keys($data);
        $values = array_values($data); 
        
         if($mode == 'INSERT INTO' ){
            $sql = $mode . ' `' . $table .'`(`' . join('`,`', $colums) . '`) VALUES (' . str_repeat('?,', count($values)-1) . '?)';
        }else if($mode == 'UPDATE' ){
            $sql = $mode . ' `'. $table .'` SET `' . join('` = ?, `', $colums) . '` = ? WHERE '. $where;
        }
        
        $request = $this->prepare($sql);
        
        if($request->execute($values))
            return $request->rowCount();
        else
            return 0;
    }
    
    public function get($table, $options = array()) {
        $colums = (!empty($options['colums'])) ? $options['colums'] : '*';
        $where = (!empty($options['where'])) ? ' WHERE ' . $options['where'] : '';
        $group = (!empty($options['group'])) ? ' GROUP BY ' . $options['group'] : '';
        $order = (!empty($options['order'])) ? ' ORDER BY ' . $options['order'] : '';
        $order_dir = (!empty($options['order_dir'])) ? ' ' . $options['order_dir'] : '';
        $limit = (!empty($options['limit'])) ? ' LIMIT ' . $options['limit'] : '';
        
        $sql = 'SELECT ' . $colums . ' FROM `'. $table .'` ' . $where . $group . $order . $order_dir . $limit; 
        
        $request = $this->prepare($sql);
        $request->execute();
        
        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete($data, $table) {
        $counter = 0;
        foreach ($data as $column => $values){
            $sql = 'DELETE FROM `' . $table . '` WHERE `'. $column .'` IN (' . implode(',', $values). ')';

            $request = $this->prepare($sql);
            if($request->execute()) $counter = $counter + $request->rowCount();
        }
        return $counter;
    }
}
