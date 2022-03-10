<?php
require './autoleader.php';
autoloader_register('Database');
autoloader_register('Product');
autoloader_register('SpecificProduct');
autoloader_register('ProductCatalog');

$db = new Database('mysql:host=localhost;dbname=arnolds1;charset=utf8mb4;', 'user', 'password');
$catalog = new ProductCatalog();

$catalog->setTypes($db->get('products', ['colums' => 'productType', 'group' => 'productType']));
$catalog->setProducts($db->get('products', ['order' => 'id', 'order_dir' => 'ASC']));

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arnolds Viguls Scandiweb Intern Test</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/e0f1484356.js" crossorigin="anonymous"></script>
    </head>
    <header>
        <div class="top-region">
            <div class="h0-title">Product List</div>
            <nav class="top-nav">
                <a id="add-product-btn" href="/add-product.php">ADD</a>
                <a id="delete-product-btn"  href="#">MASS DELETE</a>
            </nav>
            <div style="clear:both"></div>
        </div>
    </header>
    <body>
        <main id="displayProducts" class="wrapper">
            <nav class="type-menu">
                <a href="#" 
                   @click="
                <?php 
                foreach ($catalog->getTypes() as $type){
                    echo 'is' . $type . '=true;';
                }
                ?>" style="display: inline-block;padding: 3px;border: 1px solid black;margin-left: 2px;">ALL PRODUCTS</a>
                <?php
                foreach ($catalog->getTypes() as $type){
                    echo '<a href="#" @click="isDVD=';
                    echo ($type=='DVD')?'true':'false';
                    echo ';isFurniture=';
                    echo ($type=='Furniture')?'true':'false';
                    echo ';isBook=';
                    echo ($type=='Book')?'true':'false';
                    echo ';"  style="display: inline-block;padding: 3px;border: 1px solid black;margin-left: 2px;">' . $type . '</a>';
                } ?>
            </nav>
            <div id="PrdocutTab" class="flex-container">
            <?php
                    $i = 0;
                    foreach ($catalog->getProducts() as $prod){
             ?>
                    <div ref="flexBox" v-if="is<?=$prod['productType'];?>" class="flex-box">
                        <div>
                            <input type="checkbox" v-model="selected" name="nameOfChoice[<?=$i;?>]" class="delete-checkbox" value="<?=$prod['sku']; ?>">
                            <i class="fa fa-solid fa-<?=($prod['productType']=='DVD')?'compact-disc':(($prod['productType']=='Book')?'book':'chair');?>"></i>
                        </div>
                        <div><b><?=$prod['sku']; ?></b></div>
                        <div><?=$prod['name']; ?></div>
                        <div><?=$prod['productType']; ?></div>
                        <div><?=$prod['price']; ?></div>
                        <hr/>
                        <?php
                            $params = json_decode($prod['params']);
                            foreach($params as $key => $val){
                        ?>
                            <div><?=$key . ": " . $val; ?></div>
                        <?php 
                            }
                         ?>
                    </div>
                    <?php
                    $i++;
                  }
            ?>
            </div>
        </main>
    </body>
    <script src="https://unpkg.com/vue@3"></script>
    <script>
        /* self executing function */
        (function  (){
            //first fix margin style
            var flekses = document.getElementById('PrdocutTab').getElementsByClassName('flex-box');
            var computed_style = flekses[0].currentStyle || window.getComputedStyle(flekses[0]);
               Array.prototype.forEach.call(flekses, function(el) {
                  el.style.marginTop = computed_style.marginLeft;
                  el.style.marginBottom = computed_style.marginLeft;
              });
              
            //then init Vue framework
              var vm = Vue.createApp({
                      data: function() {
                        return {
                            isDVD: true,
                            isFurniture: true,
                            isBook: true,
                            selected: []
                        };
                      },
                      methods: {
                          mass_delete: function() {
                                    let data = {'action' : 'mass_delete', 'sku' : []};
                                    
                                    if(this.selected.length === 0) return null;
                                    
                                    this.selected.forEach((selected, i) => {
                                        data.sku.push(selected);
                                    });
                                    
                                    let requestOptions = {
                                       method: "POST",
                                       headers: { "Content-Type": "application/json" },
                                       body: JSON.stringify(data)
                                    };
                                    
//                                    fetch api, only modern browsers
                                    fetch("./fetch_API.php", requestOptions)
                                    .then( response => response.json() )
                                    .then( function(data) {
                                        if(data == 1){
                                            window.location.href = "<?php echo "http://" . $_SERVER['HTTP_HOST']; ?>";
                                       }else{
                                            console.log(data);
                                       }
                                   });
                           }
                        }
                    }).mount('#displayProducts');
                    
                    document.getElementById('delete-product-btn').addEventListener("click", function() {
                        vm.mass_delete();
                    });
        })();
    </script>
</html>