<?php
require './autoleader.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arnolds Viguls Scandiweb Intern Test</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <header>
        <div class="top-region">
            <div class="h0-title">Product Add</div>
            <nav class="top-nav">
                <a id="save-product-btn" onclick="trySubmit()" href="#">Save</a>
                <a id="cancel-product-btn" href="<?php echo "http://" . $_SERVER['HTTP_HOST']; ?>">Cancel</a>
            </nav>
            <div style="clear:both"></div>
        </div>
    </header>
    <body>
        <form id="editProduct" class="wrapper" onsubmit="event.preventDefault();">
        </form>
    </body>
    <script src="https://unpkg.com/vue@3"></script>
    <script>
        /*
         *        SCRIPT
         */
        
        
      let me = Vue.createApp({
        data: function() {
          return {
            inputs: [{name: 'sku',label: 'SKU',type: 'text',preval: 'XXXXXXXX',val: '',isValid: true},
                     {name: 'title',label: 'Name',type: 'text',preval: 'Product name',val: '',isValid: true},
                     {name: 'price',label: 'Price ($)',type: 'decimal',preval:'0.00',val: '',isValid: true}],
            selected: 'DVD',
            sub_msg: {'DVD': "Please, provide DVD's size in megabytes (MB). 1 GB = 1000 MB",
                      'Furniture': "Please, provide dimensions in HxWxL format in centimetres",
                      'Book': "Please, provide book's weight in kilograms (KG)"},
            type_msg: "Please, provide DVD's size in megabytes (MB). 1 GB = 1000 MB",
            sel_name: "productType"
            
          }; 
        },
        template:
`
<div  v-for="(arr, i) in inputs" :key="i"  class="form_line" >
    <label>{{arr.label}}</label>
    <input-field :name="arr.name" v-model="arr.val" :type="arr.type" :preval="arr.preval" />
</div>
<div class="form_line">
    <selector :select_name="sel_name" @option_changed="update_selected" v-model="selected" :id="sel_name"/>
</div>
<div class="procut-message">{{type_msg}}</div>
`,
        methods: {
            update_selected (event) {
                this.selected = event;
                this.type_msg = this.sub_msg[event];
            }
        },
        components: ['input-field', 'selector']
      });
      
      /*
       *     SELECT
       */
    
    me.component('selector', {
        template:
`
<label>{{dropdown_label}}</label>
<select :name="select_name" @change="selectChanged" v-model="selected" :id="select_name">
    <option v-for="(a, o) in case_inputs" :key="o" :value="o">{{o}}</option>
</select>
<div  v-for="(input_arr, o) in case_inputs[this.selected]" :key="o"  class="form_line" >
    <label>{{input_arr.label}}</label>
    <input-field  :name="input_arr.name" v-model="input_arr.val" :type="input_arr.type" :preval="input_arr.preval" />
</div>
`,
    props: ['modelValue','select_name'],
    methods: {
        selectChanged() {
            this.$emit('option_changed', this.selected);
        }
    },
    data: function () {
        return {
            dropdown_label: 'Type switcher',
            case_inputs: {
                'DVD':       [ {name: 'size', label: 'Size (Mb)', type: 'decimal', preval: '0', val: ''}],
                
                'Furniture': [ {name: 'height', label: 'Height', type: 'decimal', preval: '0', val: ''},
                               {name: 'width', label: 'Width', type: 'decimal', preval: '0', val: ''},
                               {name: 'length', label: 'Length', type: 'decimal', preval: '0', val: ''}
                             ],
                'Book':      [ {name: 'weight', label: 'Weight', type: 'decimal', preval: '0', val: ''}]
            },
            selected: 'DVD'
        };
    },
    components: ['input-field']
    });
    
    me.component('input-field', {
        template:
`
<input :name="name" v-model="inputVal" :type="inputType" :min="(inputType==='number'?'0':null)" :placeholder="preval" required />
`,
    props: ['name','type','preval','isValid','modelValue'],
    computed: {
        inputVal: {
            get(){
                return this.modelValue;
                },
            set(value){
                this.$emit('update:modelValue', value);
            }
        },
        inputType: {
            get(){
                return (this.type === 'decimal')?'number':this.type;
            }
        }
    },
    methods: {
            minNumber: (inputType) => {
                return (inputType === 'number')?'minNumber="0.01"':'';
            }
        },
    data: function () {
        return {
        };
        }
    });
      
    me.mount('#editProduct');
      function trySubmit(){
         let data = new FormData(document.getElementById('editProduct'));
         let prepared_data = Object.fromEntries(data.entries());
         prepared_data.name = prepared_data.title;
         prepared_data.action = 'add_product';
         
         let requestOptions = {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(prepared_data)
         };

          //fetch api, only modern browsers
        
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
      
//function from internet, returns array as string
var print = function(o){
    var str='';
    for(var p in o){
        if(typeof o[p] == 'string'){
            str+= p + ': ' + o[p]+'; </br>';
        }else{
            str+= p + ': { </br>' + print(o[p]) + '}';
        }
    }

    return str;
};
    </script>
</html>