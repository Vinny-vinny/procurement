<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_enquiry ? 'Update Enquiry' : 'New Enquiry'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveEnquiry()">
                     <div class="row">
                          <div class="col-md-12">
                                <div class="form-group">
                            <label>Enquiry Date</label>
                            <datepicker v-model="form.enquiry_date" required></datepicker>
                        </div>
                         <div class="form-group">
                            <label>Enquiry Title</label>
                            <input type="text" class="form-control" v-model="form.title" required>
                        </div>
                        <div class="form-group">
                            <label>Enquiry Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>                       
                         <div class="form-group">
                            <label>Suppliers</label>
                               <multiselect
                                        v-model="value"
                                        :options="suppliers"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        placeholder="Select Suppliers"
                                        @input="updateSuppliers"
                                    >
                                    </multiselect>                            
                        </div>

                          </div>
                     </div>
                     <div class="row">
                          <div class="col-md-4">
                                 <div class="form-group">
                                     <label>Requistion Date From:</label>
                                     <datepicker v-model="form.req_date_from" required></datepicker>
                                 </div>
                             </div>
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>Requistion Date To:</label>
                                       <datepicker v-model="form.req_date_to" ref="date_to" required></datepicker>
                                  </div>
                              </div>
                              
                               <div class="col-md-4">
                                   <div class="form-group">
                                    <label>Item Type</label>
                                   <select v-model="form.item_type" class="form-control" required>
                                       <option value="asset">Asset</option>
                                       <option value="stock">Stock Item</option>
                                       <option value="service">Service</option>
                                   </select>    
                                   </div>
                               </div>
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
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">               

                                            <td>                                             
                                        <model-select :options="all_stks"
                                        v-model="m.item_id"  
                                        @input="stk_id = m.item_id"                                             
                                        class="i_p_3 qq"
                                        >
                                        </model-select>
                                          </td>                                          

                                            <td><input type="number" class="form-control cost" v-model="m.qty"
                                                       placeholder="Qty" disabled>                                                
                                                   </td>
                                            <td>
                                              <input type="text" class="form-control item" v-model="m.uom" disabled>
                                              </td>                                    
                                                       <td>
                                                <input type="text" class="form-control item" v-model="m.scheduled_date" disabled>
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
                                            <td><input type="number" class="form-control cost" v-model="m.qty"
                                                       placeholder="Qty" disabled>                                                
                                                   </td>
                                            <td>
                                                <input type="text" class="form-control item" v-model="m.uom" disabled>
                                                </td>                                    
                                                       <td> 
                                                        <input type="text" class="form-control item" v-model="m.scheduled_date" disabled></td>
                                                       
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
                                            <th></th>                         
                                        </tr>
                                        <tr v-for="(m,i) in form.item_service">                           
                                            <td>
                                         <model-select :options="all_services"
                                        v-model="m.item_id" 
                                        @input="stk_id = m.item_id"                    
                                        class="i_p qq"
                                        >
                                        </model-select>
                                        </td>
                                        <td>
                                         <input type="text" class="form-control cost" v-model="m.description"
                                                       placeholder="Description" disabled></td>

                                         <td><input type="number" class="form-control cost" v-model="m.amount"
                                                       placeholder="Amount" disabled></td>
                                         <td><datepicker v-model="m.scheduled_date" placeholder="Scheduled Date"></datepicker></td>
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
                        <button type="submit" class="btn btn-primary">{{edit_enquiry ? 'Update' : 'Save'}}</button>
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
  import Multiselect from 'vue-multiselect';
  import 'vue-multiselect/dist/vue-multiselect.min.css';
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    title:'',
                    description:'',
                    supplier_id:[],
                    enquiry_date:'',
                    req_date_from:'',
                    req_date_to:'',
                    item_type:'',
                    item_stock: [{item_id: '',qty:'',uom: '',scheduled_date:''}],
                    item_asset: [{item_id: '',qty:'',uom: '',scheduled_date:''}],
                    item_service: [{item_id: '',name:'',description: '',amount:'',scheduled_date:''}],
                    id:''
                },
                edit_enquiry: this.edit,
                value:[],
                suppliers:[],
                stocks:{},
                assets:{},
                show_asset:false,
                show_stock:false,
                show_service:false,
                uoms:{},
                filtered_assets:[],
                filtered_stocks:[],
                filtered_services:[],
                all_services:[],
                all_stks:[],
                all_assets:[],
                stk_id:'',
                sch_date:'',
                qty:''

            }
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
                            this.form.item_service[j]['name'] = this.filtered_services[i]['name']
                            this.form.item_service[j]['amount'] = this.filtered_services[i]['amount']
                            this.form.item_service[j]['description'] = this.filtered_services[i]['description'] 
                            this.form.item_service[j]['scheduled_date'] = this.filtered_services[i]['scheduled_date']  
                        }
                    }
                }                 
               
            }
            },
        req_dates(){            
            let assets = [];
            let stocks = [];
            let services = [];
            if (this.form.req_date_from !=='' && this.form.req_date_to !=='') {
            if (DateConverter.convertDate(this.form.req_date_from) > DateConverter.convertDate(this.form.req_date_to) ) {
                  this.$refs.date_to.clearDate();
                  this.form.req_date_to = '';
                return this.$toastr.e('Sorry,Requistion start date cannot be greater than end date.')
            }
            }
            if (this.form.req_date_from !=='' && this.form.req_date_to !=='' && this.form.item_type !=='') {
              axios.post(`custom-requistions`,{from:DateConverter.convertDate(this.form.req_date_from),to:DateConverter.convertDate(this.form.req_date_to)})
              .then(res => {             
                if (res.data.length > 0) {
                  for(let i=0;i<res.data.length;i++){
                    if (this.form.item_type =='asset') {                      
                        if (res.data[i]['item_asset'][0]['item_id'] !=null) {
                            assets.push(res.data[i]); 
                        }                                  
                    }
                    if (this.form.item_type =='stock') {
                        if (res.data[i]['item_stock'][0]['item_id'] !=null) {
                        stocks.push(res.data[i]);
                        }                                            
                    }
                    if (this.form.item_type =='service') {
                        if (res.data[i]['item_service'][0]['item_id'] !=null) {
                        services.push(res.data[i]);
                        }                                            
                    }
                    
                  }
                }   
            let stk_items = [];
            let asset_items = [];
            let stk_items_2 = [];
            let asset_items_2 = [];
            let service_items = [];
            let service_items_2 = [];

              if (this.form.item_type =='asset') {                     
                    if (assets.length ==0) {
                        this.show_asset = false;
                        this.show_stock = false;
                        this.show_service = false;
                    return this.$toastr.e('Sorry, you do not have Asset requisitions between the selected dates.')
                    }                
                if (assets.length > 0){
                        this.show_asset = true;
                        this.show_stock = false;
                        this.show_service = false;
                        this.filtered_assets = [];
                        this.all_assets = [];
                    for(let i=0;i<assets.length;i++){                       
                     asset_items.push(assets[i]['item_asset']);                     
                    }
                    for(let i=0;i<asset_items.length;i++){
                        for(let k=0;k<asset_items[i].length;k++){
                            asset_items_2.push(asset_items[i][k]);
                        }
                    }

                    let asset_obj = {};                   
                    for(let i=0;i<asset_items_2.length;i++){
                       if (!asset_obj[asset_items_2[i]['item_id']]) {
                        asset_obj[asset_items_2[i]['item_id']] = asset_items_2[i]; 
                       }
                       else if (asset_obj[asset_items_2[i]['item_id']]) { 
                         asset_obj[asset_items_2[i]['item_id']]['qty'] =parseFloat(asset_obj[asset_items_2[i]['item_id']]['qty']) + parseFloat(asset_items_2[i]['qty'])                                
                       }
                    }

                    for(var p in asset_obj){
                      if(asset_obj.hasOwnProperty(p)){
                      for(let q=0;q<this.assets.length;q++){
                        if (asset_obj[p]['item_id'] === this.assets[q]['id']) {
                          this.all_assets.push({
                          'value':this.assets[q]['id'],
                          'text':`${this.assets[q]['code']}-${this.assets[q]['description']}`
                        })
                          this.filtered_assets.push({
                            'id':asset_obj[p]['item_id'],
                            'qty':asset_obj[p]['qty'],
                            'uom':this.uoms.find(u => u.id == asset_obj[p]['uom']).name,
                            'scheduled_date':DateConverter.conversion(asset_obj[p]['scheduled_date']),
                            'description':`${this.assets[q]['code']}-${this.assets[q]['description']}`
                          });
                        }                       
                       }
                      }                       
                    }
                }
// item_asset: [{item_id: '',qty:'',uom: '',scheduled_date:''}],
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
                            'rate': asset['rate'],
                            'scheduled_date': asset['scheduled_date']                        
                        })                       
                     
                    })
                }
                }
             
            }

              if (this.form.item_type =='stock') {   
                 if (stocks.length ==0) {
                        this.show_asset = false;
                        this.show_stock = false;
                        this.show_service = false;
                    return this.$toastr.e('Sorry, you do not have Stock Items requisitions between the selected dates.')
                    }                        
                if (stocks.length > 0) {
                        this.show_asset = false;
                        this.show_stock = true;
                        this.show_service = false;
                        this.filtered_stocks = [];
                        this.all_stks = [];
                    for(let i=0;i<stocks.length;i++){                       
                     stk_items.push(stocks[i]['item_stock']);                     
                    }

                    for(let i=0;i<stk_items.length;i++){
                        for(let k=0;k<stk_items[i].length;k++){                          
                            stk_items_2.push(stk_items[i][k]);
                        }
                    }

                    let stk_obj = {};                   
                    for(let i=0;i<stk_items_2.length;i++){
                       if (!stk_obj[stk_items_2[i]['item_id']]) {
                        stk_obj[stk_items_2[i]['item_id']] = stk_items_2[i]; 
                       }
                       else if (stk_obj[stk_items_2[i]['item_id']]) { 
                         stk_obj[stk_items_2[i]['item_id']]['qty'] =parseFloat(stk_obj[stk_items_2[i]['item_id']]['qty']) + parseFloat(stk_items_2[i]['qty'])                                       
                       }
                    }
                      for(var i in stk_obj){
                        if(stk_obj.hasOwnProperty(i)){
                        for(let j=0;j<this.stocks.length;j++){
                        if (stk_obj[i]['item_id'] == this.stocks[j]['id']) { 
                        this.all_stks.push({
                          'value':this.stocks[j]['id'],
                          'text':`${this.stocks[j]['code']}-${this.stocks[j]['description']}`
                        })                       
                          this.filtered_stocks.push({
                            'id':this.stocks[j]['id'],
                            'qty':stk_obj[i]['qty'],
                            'uom':this.uoms.find(u => u.id == stk_obj[i]['uom']).name,
                            'scheduled_date':DateConverter.conversion(stk_obj[i]['scheduled_date']),
                            'description':`${this.stocks[j]['code']}-${this.stocks[j]['description']}`
                        });
                        }                       
                       }
                      }                       
                    }                 
                 } 

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
                            'rate': stk['rate'],
                            'scheduled_date': stk['scheduled_date']                        
                        })                       
                     
                    })
                }
                }             
              }

               if (this.form.item_type =='service') {   
                 if (services.length ==0) {
                        this.show_asset = false;
                        this.show_stock = false;
                        this.show_service = false;
                    return this.$toastr.e('Sorry, you do not have Service Items requisitions between the selected dates.')
                    }                        
                if (services.length > 0) {
                        this.show_asset = false;
                        this.show_stock = false;
                        this.show_service = true;
                        this.filtered_services = [];
                        this.all_services = [];
                    for(let i=0;i<services.length;i++){                       
                     service_items.push(services[i]['item_service']);                     
                    }

                    for(let i=0;i<service_items.length;i++){
                        for(let k=0;k<service_items[i].length;k++){                          
                            service_items_2.push(service_items[i][k]);
                        }
                    }

                    let service_obj = {};                   
                    for(let i=0;i<service_items_2.length;i++){
                       if (!service_obj[service_items_2[i]['item_id']]) {
                        service_obj[service_items_2[i]['item_id']] = service_items_2[i]; 
                       }
                       else if (service_obj[service_items_2[i]['item_id']]) { 
                         service_obj[service_items_2[i]['item_id']]['qty'] =parseFloat(service_obj[service_items_2[i]['item_id']]['qty']) + parseFloat(service_items_2[i]['qty'])                                       
                       }
                    }
                      for(var i in service_obj){

                        if(service_obj.hasOwnProperty(i)){
                        for(let j=0;j<this.services.length;j++){
                        if (service_obj[i]['item_id'] == this.services[j]['id']) {                     
                        this.all_services.push({
                          'value':this.services[j]['id'],
                          'text': this.services[j]['name']
                        })                       
                          this.filtered_services.push({
                            'id':this.services[j]['id'],
                            'name':service_obj[i]['name'],
                            'description':service_obj[i]['description'], 
                            'amount':service_obj[i]['amount'],                            
                            'scheduled_date':service_obj[i]['scheduled_date'],
                            
                        });
                        }                       
                       }
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
                            'scheduled_date': service['scheduled_date']                        
                        })                       
                     
                    })
                }
                }              
              }
              })

            }
        }
        },
        created(){
            this.listen();
            this.getEnquiries();
        },
        computed:{
       req_dates(){
        return [this.form.req_date_from,this.form.req_date_to,this.form.item_type].join();
       },
        reqs(){
        return [this.stk_id,this.form.item_stock,this.form.item_asset,this.form.item_service].join();
        }
        },
        methods:{
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
            this.form.item_service.push({item_id: '',name:'',description: '',amount:'',scheduled_date:''});            
            },
            removeService(i){
             this.form.item_service.splice(i,1);
            },
         getEnquiries(){
          axios.get('enquiry')
          .then(res => {
            this.suppliers = res.data.suppliers;
            this.stocks = res.data.stocks;
            this.assets = res.data.assets;
            this.uoms = res.data.uoms;
            this.services = res.data.services;
          })
           },
           updateSuppliers(value){
             let suppliers = [];
           value.forEach((val) =>  {
               suppliers.push(val.id);
           }); 
            this.form.supplier_id = suppliers;
           },
           customLabel(option){
            return `${option.name}`;
           },
          saveEnquiry(){
               let service_obj = {};
                  let stk_obj = {};
                  let asset_obj = {};
                  if (Object.values(this.form.item_stock[0])[0] !== '' || Object.values(this.form.item_stock[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_stock.length; i++) {
                        if (this.form.item_stock[i]['item_id'] === '' || this.form.item_stock[i]['amount'] === '' ) {
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
                if (Object.values(this.form.item_asset[0])[0] !== '' || Object.values(this.form.item_asset[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_asset.length; i++) {
                        if (this.form.item_asset[i]['item_id'] === '' || this.form.item_asset[i]['amount'] === '' ) {
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

            if (Object.values(this.form.item_service[0])[0] !== '' || Object.values(this.form.item_service[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_service.length; i++) {
                        if (this.form.item_service[i]['item_id'] === '' || this.form.item_service[i]['amount'] === '' ) {
                            return this.$toastr.e('Please all Services fields are required.');
                        }
                    }
                 
                 for(let i=0;i<this.form.item_service.length;i++){        
                if(!service_obj[this.form.item_service[i]['item_id']]){
                    service_obj[this.form.item_service[i]['item_id']] = this.form.item_service[i];
                } 
                else if(service_obj[this.form.item_service[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${service_obj[this.form.item_service[i]['item_id']]['name']} twice,Please check before proceeding.`);
                } 
                
            } 
            
           }

            this.form.req_date_from = DateConverter.convertDate(this.form.req_date_from);
            this.form.req_date_to = DateConverter.convertDate(this.form.req_date_to);
            this.form.enquiry_date = DateConverter.convertDate(this.form.enquiry_date);
                this.edit_enquiry ? this.update() : this.save();
            },
            save(){               
                delete this.form.id;
                axios.post('enquiry',this.form)
                    .then(res => {
                        eventBus.$emit('listEnquiry',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`enquiry/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_enquiry = false;
                        eventBus.$emit('updateEnquiry',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.enquiries;                   
                    setTimeout(()=>{
                        for(let i=0;i<this.form.supplier_id.length;i++){
                          for(let j=0;j<this.suppliers.length;j++){
                            if(this.form.supplier_id[i] == this.suppliers[j]['id']){
                                this.value.push(this.suppliers[i]);
                            }
                          }
                        }                    
                    
                    },1000)                    

                }
            },
        },
        components:{
            datepicker,
            ModelSelect,
            Multiselect
        }
    }
</script>

<style scoped>
.qq{
  margin-bottom:5px;
}
</style>
