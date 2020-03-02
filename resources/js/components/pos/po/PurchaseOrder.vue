<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_po ? 'Update Purchase Order' : 'New Purchase Order'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="savePo()">
                        <div class="form-group">
                            <label>PO Date</label>
                            <datepicker v-model="form.po_date" required></datepicker>
                        </div>
                        <div class="form-group">
                            <label>PO Description</label>
                            <input type="text" class="form-control" v-model="form.po_description" required>
                        </div>
                        <div class="form-group">
                            <label>Quotation</label>
                            <select class="form-control" v-model="form.quotation_id" required @change="getSupplier()">
                                <option :value="q.id" v-for="q in quotations" :key="q.id">{{q.quote_no}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Supplier</label>
                            <input type="text" class="form-control" :value="supplier" disabled>
                        </div>
                        <div class="form-group">
                            <label>Item Type</label>
                            <select class="form-control" v-model="form.item_type" required @change="populateItems()">
                                <option value="stock">Stock Item</option>
                                <option value="asset">Asset</option>                              
                                <option value="service">Service</option>
                            </select>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" v-if="show_stock">
                                <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Stock Items</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Uom</th>    
                                            <th>Scheduled Date</th>
                                            <th>Quote Rate</th> 
                                            <th>Delivery Date</th> 
                                            <th>Max. Delivery Qty</th>  
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">                 

                                            <td>
                                      <model-select :options="all_stks"
                                        v-model="m.item_id"  
                                        @input="stk_id = m.item_id"                                             
                                        class="i_p_3"
                                        >
                                        </model-select>
                                          </td>                                          

                                            <td><input type="number" class="form-control mb" v-model="m.qty"
                                                       placeholder="Qty" disabled>                                                
                                                   </td>
                                            <td>
                                                <input type="text" class="form-control mb" v-model="m.uom" placeholder="UOM" disabled>
                                              </td>                                    
                                                       <td>
                                                       <input type="text" class="form-control mb" v-model="m.scheduled_date" disabled placeholder="Scheduled Date" disabled>
                                                       </td>
                                                     <td><input type="number" class="form-control mb" v-model="m.rate"
                                                       placeholder="Delivery Rate" disabled>                                                
                                                   </td>
                                                    <td><datepicker v-model="m.delivery_date" placeholder="Delivery Date" disabled class="mb"></datepicker>                 
                                                   </td>
                                                    <td><input type="number" class="form-control mb" v-model="m.max_qty"
                                                       placeholder="Max. Delivery Qty" disabled>                                               
                                                   </td>
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeItem(i)"
                                                   v-show="i || (!i && form.item_stock.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addItem(i)"
                                                   v-show="i == form.item_stock.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                                       
                                </div>
                                <div class="form-group" v-if="show_asset">
                                      <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Assets</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Uom</th>    
                                            <th>Scheduled Date</th>
                                            <th>Quote Rate</th> 
                                            <th>Delivery Date</th> 
                                            <th>Max. Delivery Qty</th>  
                                            <th></th>                         
                                        </tr>                                        
                                        <tr v-for="(m,i) in form.item_asset">                           
                                            <td>
                                           <model-select :options="all_assets"
                                        v-model="m.item_id"  
                                        @input="stk_id = m.item_id"                                             
                                        class="i_p_3 qq"
                                        >
                                        </model-select>
                                          </td>
                                            <td><input type="number" class="form-control mb" v-model="m.qty"
                                                       placeholder="Qty" disabled>                                               
                                                   </td>
                                            <td>
                                                 <input type="text" class="form-control mb" v-model="m.uom" placeholder="UOM" disabled>
                                            </td>                                    
                                              <td><input type="text" class="form-control mb" v-model="m.scheduled_date" disabled placeholder="Scheduled Date" disabled>
                                              </td>
                                               <td><input type="number" class="form-control mb" v-model="m.rate"
                                                       placeholder="Delivery Rate" disabled>                                                
                                               </td>
                                                    <td><datepicker v-model="m.delivery_date" class="mb" placeholder="Delivery Date"></datepicker disabled>                 
                                                   </td>
                                                    <td><input type="number" class="form-control mb" v-model="m.max_qty"
                                                       placeholder="Max. Delivery Qty" disabled>                                               
                                                   </td>
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeAsset(i)"
                                                   v-show="i || (!i && form.item_asset.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addAsset(i)"
                                                   v-show="i == form.item_asset.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                                </div>

                                   <div class="form-group" v-if="show_service">
                                    <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Services</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Description</th>
                                            <th>Amount</th> 
                                            <th>Scheduled Date</th> 
                                            <th>Quote Rate</th>
                                            <th>Service Delivery Date</th>
                                            <th></th>                         
                                        </tr>                                       
                                        <tr v-for="(m,i) in form.item_service">                           
                                            <td>
                                         <model-select :options="all_services"
                                        v-model="m.item_id" 
                                        @input="stk_id = m.item_id"                    
                                        class="i_p_3 qq"
                                        >
                                        </model-select>
                                        </td>
                                        <td>
                                         <input type="text" class="form-control mb" v-model="m.description"
                                                       placeholder="Description" disabled></td>

                                         <td><input type="number" class="form-control mb" v-model="m.amount"
                                                       placeholder="Amount" disabled></td>
                                         <td><datepicker v-model="m.scheduled_date" placeholder="Scheduled Date" class="mb"></datepicker></td>
                                         <td><input type="number" class="form-control mb" v-model="m.rate"
                                                       placeholder="Quote Rate"></td>
                                                        <td><datepicker v-model="m.delivery_date" placeholder="Scheduled Date" class="mb"></datepicker></td>

                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeService(i)"
                                                   v-show="i || (!i && form.item_service.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addService(i)"
                                                   v-show="i == form.item_service.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                                </div>

                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_po ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
import datepicker from 'vuejs-datepicker';
import { ModelSelect } from 'vue-search-select';
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    po_date:'',
                    po_description:'',
                    quotation_id:'',
                    item_type:'',
                    supplier_id:'',
                    item_stock: [{item_id: '',qty:'',uom: '',scheduled_date:'',rate:'',delivery_date:'',max_qty:''}],
                    item_asset: [{item_id: '',qty:'',uom: '',scheduled_date:'',rate:'',delivery_date:'',max_qty:''}],
                    item_service: [{item_id: '',description: '',amount:'',scheduled_date:'',rate:'',delivery_date:''}],
                    id:''
                },
                edit_po: this.edit,
                quotations:{},
                show_asset:false,
                show_stock:false,
                show_service:false,
                filtered_stock_items:[],
                filtered_assets:[],
                filtered_services:[],
                all_assets:[],
                all_stks:[],
                all_services:[],
                services:{},
                supplier:'',
                stk_id:'',
                stock_items:{},
                assets:{},
                suppliers:{}
            }
        },
        created(){
            this.listen();
            this.getpos();


        },
        watch:{            
            'form.item_type'(){
            if (this.form.quotation_id =='') {
            this.form.item_type ='';
            return this.$toastr.e('Please choose quotation first');
           }
            },
        reqs(){
            if (this.stk_id !=='') {
                for(let i=0;i<this.filtered_assets.length;i++){
               if (Object.values(this.form.item_asset[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_asset.length; j++) {
                        if (this.form.item_asset[j]['item_id'] == this.filtered_assets[i]['id']) {
                            this.form.item_asset[j]['qty'] = this.filtered_assets[i]['qty']
                            this.form.item_asset[j]['uom'] = this.filtered_assets[i]['uom'] 
                            this.form.item_asset[j]['scheduled_date'] = this.filtered_assets[i]['scheduled_date']
                            this.form.item_asset[j]['rate'] = this.filtered_assets[i]['rate']  
                            this.form.item_asset[j]['delivery_date'] = this.filtered_assets[i]['delivery_date']  
                            this.form.item_asset[j]['max_qty'] = this.filtered_assets[i]['max_qty']    
                        }
                    }
                }                
               
            }
              for(let i=0;i<this.filtered_stock_items.length;i++){
               if (Object.values(this.form.item_stock[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_stock.length; j++) {
                        if (this.form.item_stock[j]['item_id'] == this.filtered_stock_items[i]['id']) {    
                        console.log(this.filtered_stock_items[i])                
                            this.form.item_stock[j]['qty'] = this.filtered_stock_items[i]['qty']
                            this.form.item_stock[j]['uom'] = this.filtered_stock_items[i]['uom'] 
                            this.form.item_stock[j]['scheduled_date'] = this.filtered_stock_items[i]['scheduled_date'] 
                            this.form.item_stock[j]['rate'] = this.filtered_stock_items[i]['rate']  
                            this.form.item_stock[j]['delivery_date'] = this.filtered_stock_items[i]['delivery_date']  
                            this.form.item_stock[j]['max_qty'] = this.filtered_stock_items[i]['max_qty']   
                        }
                    }
                }                 
               
            }

               for(let i=0;i<this.filtered_services.length;i++){
               if (Object.values(this.form.item_service[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_service.length; j++) {
                        if (this.form.item_service[j]['item_id'] == this.filtered_services[i]['id']) {                                  
                            
                            this.form.item_service[j]['description'] = this.filtered_services[i]['description'] 
                            this.form.item_service[j]['scheduled_date'] = this.filtered_services[i]['scheduled_date'] 
                            this.form.item_service[j]['rate'] = this.filtered_services[i]['rate']  
                            this.form.item_service[j]['delivery_date'] = this.filtered_services[i]['delivery_date']  
                            this.form.item_service[j]['amount'] = this.filtered_services[i]['amount']   
                        }
                    }
                }                 
               
            }

            }  
            
        },           
       fetchItems(){          
                let budget_item = {};
                let stk_items = {};
                let asset_item = {};
                let service_items = {};
          if (this.form.quotation_id !=='' && this.form.item_type !=='') {    
          
           setTimeout(()=>{
          let quotations = this.quotations.find(q => q.id == this.form.quotation_id);                      
         if (quotations ==undefined) {
           this.show_asset = false;
           this.show_stock = false;
           this.show_service = false;                      
           return this.$toastr.e(`The selected Quotation does not have budgeted ${this.form.item_type}.`);  
         }    
             if (this.form.item_type=='asset') {
                    if(quotations['item_asset'][0]['item_id'] != null){
                        asset_item = quotations['item_asset'];
                    }
                     if (Custom.isEmpty(asset_item)) {
                        this.show_asset = false;
                        this.show_stock = false;  
                        this.show_service = false;              
                      return this.$toastr.e(`The selected Quotation does not have budgeted Assets.`);
                     }  
                this.show_stock = false;
                this.show_asset = true; 
                 this.show_service = false;             
                this.filtered_assets = [];  
                this.all_assets = [];         
               for(let i=0;i<asset_item.length;i++){
                for(let j=0;j<this.assets.length;j++){
                    if (asset_item[i]['item_id'] == this.assets[j]['id']) {
                      this.all_assets.push({
                        'value':this.assets[j]['id'],
                        'text': this.assets[j]['code'] +'-'+this.assets[j]['description'] 
                      })
                          this.filtered_assets.push({
                            'id': this.assets[j]['id'],
                            'qty': asset_item[i]['qty'],
                            'uom': asset_item[i]['uom'],
                            'scheduled_date': asset_item[i]['scheduled_date'],
                            'rate': asset_item[i]['rate'],
                            'delivery_date': asset_item[i]['delivery_date'],
                            'max_qty': asset_item[i]['max_qty'],
                            'description': this.assets[j]['code'] +'-'+this.assets[j]['description']
                        });
                    }
                }
               } 

                if (this.filtered_assets.length) {
                    if (this.form.item_asset[0]['item_id'] =="") {
                     this.form.item_asset.splice(this.form.item_asset[0],1);
                    }
                    if (this.form.item_asset.length ==0) {
                    this.filtered_assets.forEach((asset) =>{                     
                           this.form.item_asset.push({
                            'item_id': asset['id'],
                            'qty': asset['qty'],
                            'uom': asset['uom'],
                            'scheduled_date': asset['scheduled_date'],
                            'rate': asset['rate'],
                            'delivery_date': asset['delivery_date'],
                            'max_qty': asset['max_qty']                           
                        }) 
                     
                    })
                }

                } 
                
                } 
                 if (this.form.item_type=='stock') {                                 
                    if(quotations['item_stock'][0]['item_id'] != null){
                        stk_items = quotations['item_stock'];
                    }  

                     if (Custom.isEmpty(stk_items)) { 
                        this.show_asset = false;
                        this.show_stock = false;  
                         this.show_service = false;                     
                      return this.$toastr.e(`The selected Quotation does not have budgeted Stock Items.`);
                     }
                this.show_stock = true;
                this.show_asset = false;
                 this.show_service = false;                       
                this.filtered_stock_items = [];
                this.all_stks = [];
                for(let i=0;i<stk_items.length;i++){
                    for(let j=0;j<this.stock_items.length;j++){                     
                        if (stk_items[i]['item_id'] == this.stock_items[j]['id']) {
                          this.all_stks.push({
                            'value': this.stock_items[j]['id'],
                            'text': this.stock_items[j]['code'] +'-'+this.stock_items[j]['description']
                          })
                            this.filtered_stock_items.push({
                            'id': this.stock_items[j]['id'],
                            'qty': stk_items[i]['qty'],
                            'uom': stk_items[i]['uom'],
                            'scheduled_date': stk_items[i]['scheduled_date'],
                            'rate': stk_items[i]['rate'],
                            'delivery_date': stk_items[i]['delivery_date'],
                            'max_qty': stk_items[i]['max_qty'],
                            'description': this.stock_items[j]['code'] +'-'+this.stock_items[j]['description']

                        });
                        }
                    }
                }  
               
                 if (this.filtered_stock_items.length) {
                    if (this.form.item_stock[0]['item_id'] =="") {
                        this.form.item_stock.splice(this.form.item_stock[0],1);
                    }
                    if (this.form.item_stock.length ==0) {
                    this.filtered_stock_items.forEach((stk) =>{                     
                           this.form.item_stock.push({
                            'item_id': stk['id'],
                            'qty': stk['qty'],
                            'uom': stk['uom'],
                            'scheduled_date': stk['scheduled_date'],
                            'rate': stk['rate'],
                            'delivery_date': stk['delivery_date'],
                            'max_qty': stk['max_qty']                           
                        }) 
                     
                    })
                }

                } 
               console.log(this.form.item_stock)
                } 

                 if (this.form.item_type=='service') {                                 
                    if(quotations['item_service'][0]['item_id'] != null){
                        service_items = quotations['item_service'];
                    }  

                     if (Custom.isEmpty(service_items)) { 
                        this.show_asset = false;
                        this.show_stock = false;   
                        this.show_service = false;                      
                      return this.$toastr.e(`The selected Quotation does not have budgeted Service Items.`);
                     }
                this.show_stock = false;
                this.show_asset = false; 
                this.show_service = true;                      
                this.filtered_services = [];
                this.all_services = [];
                for(let i=0;i<service_items.length;i++){
                    for(let j=0;j<this.services.length;j++){                     
                        if (service_items[i]['item_id'] == this.services[j]['id']) {
                          this.all_services.push({
                            'value': this.stock_items[j]['id'],
                            'text': this.services[j]['name'] 
                          })
                            this.filtered_services.push({
                            'id': this.stock_items[j]['id'],                          
                            'description': service_items[i]['description'],
                            'scheduled_date': service_items[i]['scheduled_date'],
                            'rate': service_items[i]['rate'],
                            'delivery_date': service_items[i]['delivery_date'],
                            'amount': service_items[i]['amount']                            

                        });
                        }
                    }
                }            
               
                if (this.filtered_services.length) {                  
                    if (this.form.item_service[0]['item_id'] =="") {                     
                     this.form.item_service.splice(this.form.item_service[0],1);
                    }                    
                    if (this.form.item_service.length ==0) {

                    this.filtered_services.forEach((service) =>{                     
                           this.form.item_service.push({
                            'item_id': service['id'],                           
                            'description': service['description'],
                            'scheduled_date': service['scheduled_date'],
                            'rate': service['rate'],
                            'delivery_date': service['delivery_date'],
                            'amount': service['amount']                           
                        })                       
                     
                    })
                }
                }       
                }

         },1000)                   
                     
            }
        }
        },
        computed:{
       fetchItems(){
        return [this.form.item_type,this.form.quotation_id].join();
       },
        reqs(){
        return [this.stk_id,this.form.item_stock,this.form.item_asset,this.form.item_service].join();
        }
        },
        methods:{  
        populateItems(){
         
         },      
            getSupplier(){
             let supplier_id = this.quotations.find(q => q.id == this.form.quotation_id).supplier_id;
             let supplier = this.suppliers.find(s => s.id == supplier_id);
             this.form.supplier_id = supplier.id;
             this.supplier = supplier.name; 

             setTimeout(()=>{
             console.log(this.filtered_stock_items);
             },2000)
            },
             addItem(i) {
                this.form.item_stock.push({item_id: '',qty:'',uom: '',scheduled_date:''});
            },
            removeItem(i) {
                this.form.item_stock.splice(i, 1);
            },
             addAsset(i){
            this.form.item_asset.push({item_id: '',qty:'',uom: '',scheduled_date:''});            
            },
            removeAsset(i){
            this.form.item_asset.splice(i,1);
            },
            addService(i){
            this.form.item_service.push({item_id: '',description: '',amount:'',scheduled_date:'',rate:'',delivery_date:''});            
            },
            removeService(i){
            this.form.item_service.splice(i,1);
            },
            getpos(){
            axios.get('purchase-order')
            .then(res => {
            this.quotations = res.data.quotations;
            this.stock_items = res.data.stock_items;
            this.assets = res.data.assets;
            this.suppliers = res.data.suppliers;
            this.services = res.data.services;

            })
            },
            savePo(){
                 let service_obj = {};
                  let stk_obj = {};
                  let asset_obj = {};
                 if (Object.values(this.form.item_stock[0])[0] !== '' || Object.values(this.form.item_stock[0])[1] !== '' || Object.values(this.form.item_stock[0])[2] !== '' || Object.values(this.form.item_stock[0])[3] !== '' || Object.values(this.form.item_stock[0])[4] !== '' || Object.values(this.form.item_stock[0])[5] !== '' || Object.values(this.form.item_stock[0])[6] !== '') {
                    for (let i = 0; i < this.form.item_stock.length; i++) {
                        if (this.form.item_stock[i]['item_id'] === '' || this.form.item_stock[i]['rate'] === '' || this.form.item_stock[i]['delivery_date'] === '' || this.form.item_stock[i]['max_qty'] === '') {
                            return this.$toastr.e('Please all Stock Items fields are required.');
                        }
                    }

                 for(let i=0;i<this.form.item_stock.length;i++){        
                if(!stk_obj[this.form.item_stock[i]['item_id']]){
                    stk_obj[this.form.item_stock[i]['item_id']] = this.form.item_stock[i];
                } 
                else if(stk_obj[this.form.item_stock[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${this.stock_items.find(s => s.id ==stk_obj[this.form.item_stock[i]['item_id']]['item_id']).code} twice,Please check before proceeding.`);
                } 
            }
                }

                 if (Object.values(this.form.item_asset[0])[0] !== '' || Object.values(this.form.item_asset[0])[1] !== '' || Object.values(this.form.item_asset[0])[2] !== '' || Object.values(this.form.item_asset[0])[3] !== '' || Object.values(this.form.item_asset[0])[4] !== '' || Object.values(this.form.item_asset[0])[4] !== '' || Object.values(this.form.item_asset[0])[5] !== '' || Object.values(this.form.item_asset[0])[6] !== '') {
                    for (let i = 0; i < this.form.item_asset.length; i++) {
                        if (this.form.item_asset[i]['item_id'] === '' || this.form.item_asset[i]['rate'] === '' || this.form.item_asset[i]['delivery_date'] === '' || this.form.item_asset[i]['max_qty'] === '') {
                            return this.$toastr.e('Please all Assets fields are required.');
                        }
                    }

                for(let i=0;i<this.form.item_asset.length;i++){        
                if(!asset_obj[this.form.item_asset[i]['item_id']]){
                    asset_obj[this.form.item_asset[i]['item_id']] = this.form.item_asset[i];
                } 
                else if(asset_obj[this.form.item_asset[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${this.assets.find(a => a.id ==asset_obj[this.form.item_asset[i]['item_id']]['item_id']).code} twice,Please check before proceeding.`);
                } 
            } 

                } 


                 if (Object.values(this.form.item_service[0])[0] !== '' || Object.values(this.form.item_service[0])[1] !== '' || Object.values(this.form.item_service[0])[2] !== '' || Object.values(this.form.item_service[0])[3] !== '' || Object.values(this.form.item_service[0])[4] !== '' || Object.values(this.form.item_service[0])[4] !== '' || Object.values(this.form.item_service[0])[5] !== '' || Object.values(this.form.item_service[0])[6] !== '') {
                    for (let i = 0; i < this.form.item_service.length; i++) {
                        if (this.form.item_service[i]['item_id'] === '' || this.form.item_service[i]['rate'] === '' || this.form.item_service[i]['delivery_date'] === '') {
                            return this.$toastr.e('Please all Services fields are required.');
                        }
                    }

                for(let i=0;i<this.form.item_service.length;i++){        
                if(!service_obj[this.form.item_service[i]['item_id']]){
                    service_obj[this.form.item_service[i]['item_id']] = this.form.item_service[i];
                } 
                else if(service_obj[this.form.item_service[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${service_obj[this.form.item_service[i]['item_id']]['description']} twice,Please check before proceeding.`);
                } 
            } 

                }
                this.form.po_date = DateConverter.convertDate(this.form.po_date);
                this.edit_po ? this.update() : this.save();
            },
            save(){
                delete this.form.id;                
                axios.post('purchase-order',this.form)
                    .then(res => {
                        eventBus.$emit('listPurchaseOrders',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`purchase-order/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_po = false;
                        eventBus.$emit('updatePurchaseOrder',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.purchase_orders
                    console.log(this.form)
                    setTimeout(()=>{
                     this.getSupplier();
                    },1000)
                }
            },
        },
        components:{
            datepicker,
            ModelSelect
        }
    }
</script>

<style scoped>
 .i_p_3{
    width:180px !important;
 }
 .qq{
    margin-bottom:5px;
 }
 .mb{
    margin-bottom:5px;
 }
</style>
