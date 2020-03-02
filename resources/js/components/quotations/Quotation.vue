<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_quotation ? 'Update Quotation' : 'New Quotation'}}</h3>
                </div>
                <div class="box-body">

                    <form @submit.prevent="saveQuotation()">
                        <div class="form-group">
                            <label>Quotation Date</label>
                            <datepicker v-model="form.quote_date" required></datepicker>                          
                        </div>
                        <div class="form-group">
                            <label>Enquiry Number</label>                        
                              <model-select :options="all_enquiries"
                                        v-model="form.enquiry_id"  
                                        :is-disabled="edit"
                                        @input="getSuppliers()"  
                                        >
                               </model-select>
                               
                        </div>
                        <div class="form-group">
                            <label>Quotation Description</label>
                            <input type="text" v-model="form.description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Supplier</label>
                              <model-select :options="filtered_suppliers"
                              :is-disabled="edit"
                               v-model="form.supplier_id"> 
                               </model-select>
                        </div>
                         <div class="form-group">
                            <label>Item Type</label>
                            <select v-model="form.item_type" class="form-control" required @change="getItems()">
                                <option value="stock">Stock</option>
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
                                        class="i_p_4 qq"
                                        >
                                        </model-select>
                                        </td>                                          

                                            <td><input type="number" class="form-control cost" v-model="m.qty"
                                                       placeholder="Qty" disabled>                                                
                                                   </td>
                                            <td>
                                                <input type="text" class="form-control" v-model="m.uom" placeholder="UOM" disabled>
                                              </td>                                    
                                                       <td>
                                                       <input type="text" class="form-control" v-model="m.scheduled_date" disabled placeholder="Scheduled Date">
                                                       </td>
                                                     <td><input type="number" class="form-control cost" v-model="m.rate"
                                                       placeholder="Delivery Rate">                                                
                                                   </td>
                                                    <td><datepicker v-model="m.delivery_date" placeholder="Delivery Date"></datepicker>                 
                                                   </td>
                                                    <td><input type="number" class="form-control cost" v-model="m.max_qty"
                                                       placeholder="Max. Delivery Qty">                                               
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
                                        class="i_p_4 qq"
                                        >
                                        </model-select>
                                        </td>
                                            <td><input type="number" class="form-control cost" v-model="m.qty"
                                                       placeholder="Qty" disabled>                                               
                                                   </td>
                                            <td>
                                                 <input type="text" class="form-control" v-model="m.uom" placeholder="UOM" disabled>
                                            </td>                                    
                                              <td><input type="text" class="form-control" v-model="m.scheduled_date" disabled placeholder="Scheduled Date">
                                              </td>
                                               <td><input type="number" class="form-control cost" v-model="m.rate"
                                                       placeholder="Delivery Rate">                                                
                                               </td>
                                                    <td><datepicker v-model="m.delivery_date" placeholder="Delivery Date"></datepicker>                 
                                                   </td>
                                                    <td><input type="number" class="form-control cost" v-model="m.max_qty"
                                                       placeholder="Max. Delivery Qty">                                               
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
                                        class="i_p_4 qq"
                                        >
                                        </model-select>
                                        </td>
                                        <td>
                                         <input type="text" class="form-control cost" v-model="m.description"
                                                       placeholder="Description" disabled></td>

                                         <td><input type="number" class="form-control cost" v-model="m.amount"
                                                       placeholder="Amount" disabled></td>
                                         <td><datepicker v-model="m.scheduled_date" placeholder="Scheduled Date"></datepicker></td>
                                         <td><input type="number" class="form-control cost" v-model="m.rate"
                                                       placeholder="Quote Rate"></td>
                                                        <td><datepicker v-model="m.delivery_date" placeholder="Scheduled Date"></datepicker></td>

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
                        <button type="submit" class="btn btn-primary">{{edit_quotation ? 'Update' : 'Save'}}</button>
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
                    quote_date:'',
                    description:'',
                    supplier_id:'',
                    enquiry_id:'',  
                    item_type:'',                  
                    item_stock: [{item_id: '',qty:'',uom: '',scheduled_date:'',rate:'',delivery_date:'',max_qty:''}],
                    item_asset: [{item_id: '',qty:'',uom: '',scheduled_date:'',rate:'',delivery_date:'',max_qty:''}],
                    item_service: [{item_id: '',description: '',amount:'',scheduled_date:'',rate:'',delivery_date:''}],
                    id:''
                },
                edit_quotation: this.edit,
                quotations:{},
                show_stock:false,
                show_asset:false,
                show_service:false,
                supplers:{},
                enquiries:{},
                all_enquiries:[],
                filtered_suppliers:[],
                filtered_assets:[],
                filtered_stocks:[],
                filtered_services:[],
                all_assets:[],
                all_stks:[],
                all_services:[],
                stocks:{},
                assets:{},
                services:{},
                stk_id:'',
                filtered_enquiries:{},
                filtered_quotations:{}                
            }
        },

        created(){
            this.listen();
            this.getQuotations();
        },
        watch:{
        reqs(){      
            for(let i=0;i<this.filtered_assets.length;i++){
               if (Object.values(this.form.item_asset[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_asset.length; j++) {
                        if (this.form.item_asset[j]['item_id'] == this.filtered_assets[i]['id']) {
                            this.form.item_asset[j]['qty'] = this.filtered_assets[i]['qty']
                            this.form.item_asset[j]['uom'] = this.filtered_assets[i]['uom'] 
                            this.form.item_asset[j]['scheduled_date'] = this.filtered_assets[i]['scheduled_date']  
                        }
                    }
                }                
               
            }

            for(let i=0;i<this.filtered_stocks.length;i++){
               if (Object.values(this.form.item_stock[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_stock.length; j++) {
                        if (this.form.item_stock[j]['item_id'] == this.filtered_stocks[i]['id']) {
                            this.form.item_stock[j]['qty'] = this.filtered_stocks[i]['qty']
                            this.form.item_stock[j]['uom'] = this.filtered_stocks[i]['uom'] 
                            this.form.item_stock[j]['scheduled_date'] = this.filtered_stocks[i]['scheduled_date']  
                        }
                    }
                }                 
               
            }

             for(let i=0;i<this.filtered_services.length;i++){
               if (Object.values(this.form.item_service[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_service.length; j++) {
                        if (this.form.item_service[j]['item_id'] == this.filtered_services[i]['id']) {                          
                            this.form.item_service[j]['amount'] = this.filtered_services[i]['amount'] 
                            this.form.item_service[j]['description'] = this.filtered_services[i]['description'] 
                            this.form.item_service[j]['scheduled_date'] = this.filtered_services[i]['scheduled_date']  
                        }
                    }
                }                 
               
            }

            },
        },
        computed:{
         reqs(){
        return [this.stk_id,this.form.item_stock,this.form.item_asset,this.form.item_service].join();
        }
        },
        methods:{
            getItems(){          
            if (this.form.enquiry_id =='') {                
             this.form.item_type =='';
             return this.$toastr.e('Please select Enquiry No first.');
            }
            if (this.form.item_type =='asset') {
                setTimeout(()=>{
                if (Custom.isEmpty(this.filtered_assets)) {
                this.show_asset = false;
                this.show_stock = false;
                this.show_service = false;
              return this.$toastr.e('Sorry,you do not have assets for the selected Enquiry No.');
             }
             },500)
              setTimeout(()=>{
              this.show_asset = true;
             this.show_stock = false;
             this.show_service = false;
                },501)            
            
            }
             if (this.form.item_type =='stock') {
               setTimeout(()=>{
                if (Custom.isEmpty(this.filtered_stocks)) {
                this.show_asset = false;
                this.show_stock = false;
                this.show_service = false;
              return this.$toastr.e('Sorry,you do not have stock items for the selected Enquiry No.');
             } 
               },500)
              setTimeout(()=>{
             this.show_asset = false;
             this.show_stock = true; 
             this.show_service = false;
              },501) 
            
            }

            if (this.form.item_type =='service') {
               setTimeout(()=>{
                if (Custom.isEmpty(this.filtered_services)) {
                this.show_asset = false;
                this.show_stock = false;
                this.show_service = false;
              return this.$toastr.e('Sorry,you do not have Service items for the selected Enquiry No.');
             } 
               },500)
              setTimeout(()=>{
             this.show_asset = false;
             this.show_stock = false;
             this.show_service = true; 
              },501) 
            
            }
            
            },
            getSuppliers(){   
            this.filtered_suppliers = [];  
            let suppliers = [];         
            let enquiry;
            
             if (this.edit) {
             this.suppliers.forEach(sp => {
              this.filtered_suppliers.push({
                'value': sp.id,
                'text': sp.name
              })
             })
              
             }
             //console.log(this.filtered_quotations)
             let s_ids;
            if (this.filtered_enquiries.length) {
              enquiry = this.filtered_enquiries.find(e => e.id ==this.form.enquiry_id);       
            
             if (this.filtered_quotations.length) {
              s_ids = this.filtered_quotations.map(sup => {
              return sup.supplier_id;
             })
           
              for(let s=0; s<enquiry.supplier_id.length; s++){             
                if (!s_ids.includes(enquiry.supplier_id[s])) {
                  suppliers.push(enquiry.supplier_id[s]);
                }
              }
             }

             else if(this.filtered_quotations.length ==0) {
                for(let sup=0; sup<this.suppliers.length; sup++){                
                if (enquiry.supplier_id.includes(this.suppliers[sup]['id'])) {
                  this.filtered_suppliers.push({
                  'value': this.suppliers[sup]['id'],
                  'text': this.suppliers[sup]['name']
                });
                }
              }   
             }
    
             if (suppliers.length) {
              for(let p=0;p<this.suppliers.length;p++){
                if(suppliers.includes(this.suppliers[p]['id'])){
                this.filtered_suppliers.push({
                  'value': this.suppliers[p]['id'],
                  'text': this.suppliers[p]['name']
                });
                }
              }
             }   
            }        
     

            let filtered_assets = [];
            let filtered_stocks = [];
            let filtered_services = [];
            this.filtered_assets = [];
            this.filtered_stocks = [];
            this.all_assets = [];
            this.all_stks = [];
            this.all_services = [];
            if (enquiry.item_asset[0]['item_id'] !==null) {
                for(let i=0;i<enquiry.item_asset.length;i++){
                filtered_assets.push(enquiry.item_asset[i]) 
                } 
                for(let i=0;i<filtered_assets.length;i++){
                    for(let j=0;j<this.assets.length;j++){
                        if (filtered_assets[i]['item_id'] == this.assets[j]['id']) {
                            this.all_assets.push({
                                'value': filtered_assets[i]['item_id'],
                                'text': this.assets[j]['code'] +'-'+ this.assets[j]['description']  
                            })
                            this.filtered_assets.push({
                                'id' : filtered_assets[i]['item_id'],
                                'qty' : filtered_assets[i]['qty'],
                                'uom' : filtered_assets[i]['uom'],
                                'scheduled_date' : filtered_assets[i]['scheduled_date'],
                                'description' : this.assets[j]['code'] +'-'+ this.assets[j]['description'] 
                            })
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
                            'rate': '',
                            'scheduled_date': asset['scheduled_date'],
                            'delivery_date': '',
                             'max_qty': ''                       
                        })                       
                     
                    })
                }
                }
             
            }
            if (enquiry.item_stock[0]['item_id'] !==null) {
                for(let i=0;i<enquiry.item_stock.length;i++){
                filtered_stocks.push(enquiry.item_stock[i]);
                }  
             
                for(let k=0;k<filtered_stocks.length;k++){
                    for(let j=0;j<this.stocks.length;j++){
                        if (filtered_stocks[k]['item_id'] == this.stocks[j]['id']) {
                            this.all_stks.push({
                                'value': filtered_stocks[k]['item_id'],
                                'text': this.stocks[j]['code'] +'-'+ this.stocks[j]['description']  
                            })
                            this.filtered_stocks.push({
                                'id' : filtered_stocks[k]['item_id'],
                                'qty' : filtered_stocks[k]['qty'],
                                'uom' : filtered_stocks[k]['uom'],
                                'scheduled_date' : filtered_stocks[k]['scheduled_date'],
                                'description' : this.stocks[j]['code'] +'-'+ this.stocks[j]['description'] 
                            })
                        }
                    }
                } 
                // item_stock: [{item_id: '',qty:'',uom: '',scheduled_date:'',rate:'',delivery_date:'',max_qty:''}],

                   if (this.filtered_stocks.length) {                  
                    if (this.form.item_stock[0]['item_id'] =="") {                     
                     this.form.item_stock.splice(this.form.item_stock[0],1);
                    }                    
                    if (this.form.item_stock.length ==0) {

                    this.filtered_stocks.forEach((stk) =>{                     
                           this.form.item_stock.push({
                            'item_id': stk['id'],                           
                            'qty': stk['qty'],
                            'uom': stk['uom'],
                            'rate': '',
                            'scheduled_date': stk['scheduled_date'],
                            'delivery_date': '',
                             'max_qty': ''                       
                        })                       
                     
                    })
                }
                }
          
            }

             if (enquiry.item_service[0]['item_id'] !==null) {
                for(let i=0;i<enquiry.item_service.length;i++){
                filtered_services.push(enquiry.item_service[i]);
                }  
             
                for(let k=0;k<filtered_services.length;k++){
                    for(let j=0;j<this.services.length;j++){
                        if (filtered_services[k]['item_id'] == this.services[j]['id']) {
                            this.all_services.push({
                                'value': filtered_services[k]['item_id'],
                                'text': this.services[j]['name']  
                            })
                            this.filtered_services.push({
                                'id' : filtered_services[k]['item_id'],                        
                                'amount' : filtered_services[k]['amount'],
                                'description' : filtered_services[k]['description'],
                                'scheduled_date' : filtered_services[k]['scheduled_date']                                
                            })
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
                            'amount': service['amount'],
                            'rate': '',
                            'scheduled_date': service['scheduled_date'],
                            'delivery_date': '',                                                
                        })                       
                     
                    })
                }
                }
          
            }
            },
            getQuotations(){
            axios.get('quotations')
            .then(res => {
                this.quotations = res.data.quotations;
                this.suppliers = res.data.suppliers;
                this.enquiries =  res.data.enquiries;
                this.stocks = res.data.stock_items;
                this.assets = res.data.assets;
                this.services = res.data.services;
                this.filtered_enquiries = res.data.filtered_enquiries;
                this.filtered_quotations = res.data.filtered_quotations;
             
                 res.data.enquiries.forEach(e => {
                    this.all_enquiries.push({
                        'value': e.id,
                        'text': e.enquiry_no
                    })
                 })
            })
            },
             addItem(i) {
                this.form.item_stock.push({item_id: '',qty:'',uom: '',scheduled_date:'',rate:'',delivery_date:'',max_qty:''});
            },
            removeItem(i) {
                this.form.item_stock.splice(i, 1);
            },
             addAsset(i){
            this.form.item_asset.push({item_id: '',qty:'',uom: '',scheduled_date:'',rate:'',delivery_date:'',max_qty:''});            
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
            saveQuotation(){
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
                  return this.$toastr.e(`Sorry, You have entered an item ${this.stocks.find(s => s.id ==stk_obj[this.form.item_stock[i]['item_id']]['item_id']).code} twice,Please check before proceeding.`);
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


                 if (Object.values(this.form.item_service[0])[0] !== '' || Object.values(this.form.item_service[0])[1] !== '' || Object.values(this.form.item_service[0])[2] !== '' || Object.values(this.form.item_service[0])[3] !== '' || Object.values(this.form.item_service[0])[4] !== '' || Object.values(this.form.item_service[0])[4] !== '' || Object.values(this.form.item_service[0])[5] !== '') {
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

                if (this.form.quote_date =='') {
                  return this.$toastr.e('Please fill in the quotation date.')
                }
                if (this.form.supplier_id =='') {
                  return this.$toastr.e('You must select supplier first');
                }
                this.form.quote_date = DateConverter.convertDate(this.form.quote_date);
                this.edit_quotation ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('quotations',this.form)
                    .then(res => {
                        eventBus.$emit('listQuotations',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`quotations/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_quotation = false;
                        eventBus.$emit('updateQuotation',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.quotations
                    console.log(this.form)
                    setTimeout(()=>{
                    this.getItems();
                    this.getSuppliers();
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
.i_p_4{
    width: 180px !important;    
}
.qq{
  margin-bottom:5px;
}
</style>
