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
                                   </select>    
                                   </div>
                               </div>
                     </div>
                      <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" v-if="show_stock">
                                    <label>Stock Items</label>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Uom</th>    
                                            <th>Scheduled Date</th> 
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">                 

                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Stock Item">
                                                <option selected disabled>Select Stock Item</option>
                                                <option :value="stock.id" v-for="stock in filtered_stocks" :key="stock.id">{{stock.description}}</option>
                                            </select></td>                                          

                                            <td><input type="number" class="form-control cost" v-model="m.qty"
                                                       placeholder="Qty">                                                
                                                   </td>
                                            <td><select class="form-control item" v-model="m.uom"
                                                        placeholder="UOM">
                                                <option selected disabled>Select UOM</option>
                                                <option :value="u.id" v-for="u in uoms" :key="u.id">{{u.name}}</option>
                                            </select></td>                                    
                                                       <td><datepicker v-model="m.scheduled_date" placeholder="Scheduled Date"></datepicker></td>
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeItem(i)"
                                                   v-show="i || (!i && form.item_stock.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addItem(i)"
                                                   v-show="i == form.item_stock.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                       
                                </div>
                                <div class="form-group" v-if="show_asset">
                                    <label>Assets</label>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Uom</th>    
                                            <th>Scheduled Date</th> 
                                            <th></th>                         
                                        </tr>
                                        <tr v-for="(m,i) in form.item_asset">                           
                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Asset">
                                                <option selected disabled>Select Asset</option>
                                                <option :value="asset.id" v-for="asset in filtered_assets" :key="asset.id">{{asset.description}}</option>
                                            </select></td>
                                            <td><input type="number" class="form-control cost" v-model="m.qty"
                                                       placeholder="Qty">                                                
                                                   </td>
                                            <td><select class="form-control item" v-model="m.uom"
                                                        placeholder="UOM">
                                                <option selected disabled>Select UOM</option>
                                                <option :value="u.id" v-for="u in uoms" :key="u.id">{{u.name}}</option>
                                            </select></td>                                    
                                                       <td><datepicker v-model="m.scheduled_date" placeholder="Scheduled Date"></datepicker></td>
                                            <td>
                                            <i class="fa fa-minus-circle remove" @click="removeAsset(i)"
                                                   v-show="i || (!i && form.item_asset.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addAsset(i)"
                                                   v-show="i == form.item_asset.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
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
                    req_date_from:'',
                    req_date_to:'',
                    item_type:'',
                    item_stock: [{item_id: '',qty:'',uom: '',scheduled_date:''}],
                    item_asset: [{item_id: '',qty:'',uom: '',scheduled_date:''}],
                    id:''
                },
                edit_enquiry: this.edit,
                value:[],
                suppliers:[],
                stocks:{},
                assets:{},
                show_asset:false,
                show_stock:false,
                uoms:{},
                filtered_assets:[],
                filtered_stocks:[]

            }
        },
        watch:{
        req_dates(){            
            let assets = [];
            let stocks = [];
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
                  }
                }   
            let stk_items = [];
            let asset_items = [];
            let stk_items_2 = [];
            let asset_items_2 = [];

              if (this.form.item_type =='asset') {                     
                    if (assets.length ==0) {
                        this.show_asset = false;
                        this.show_stock = false;
                    return this.$toastr.e('Sorry, you do not have Asset requisitions between the selected dates.')
                    }                
                if (assets.length > 0) {
                        this.show_asset = true;
                        this.show_stock = false;
                        this.filtered_assets = [];
                    for(let i=0;i<assets.length;i++){                       
                     asset_items.push(assets[i]['item_asset']);                     
                    }
                    for(let i=0;i<asset_items.length;i++){
                        for(let k=0;k<asset_items[i].length;k++){
                            asset_items_2.push(asset_items[i][k]);
                        }
                    }
                    for(let p=0;p<asset_items_2.length;p++){
                       for(let q=0;q<this.assets.length;q++){
                        if (asset_items_2[p]['item_id'] === this.assets[q]['id']) {
                          this.filtered_assets.push({
                            'id':asset_items_2[p]['item_id'],
                            'qty':asset_items_2[p]['qty'],
                            'uom':this.uoms.find(u => u.id == asset_items_2[p]['uom']).name,
                            'scheduled_date':DateConverter.conversion(asset_items_2[p]['scheduled_date']),
                            'description':`${this.assets[q]['code']}-${this.assets[q]['description']}`
                          });
                        }                       
                       }
                    }
                }
             
            }

              if (this.form.item_type =='stock') {   
                 if (stocks.length ==0) {
                        this.show_asset = false;
                        this.show_stock = false;
                    return this.$toastr.e('Sorry, you do not have Stock Items requisitions between the selected dates.')
                    }                        
                if (stocks.length > 0) {
                        this.show_asset = false;
                        this.show_stock = true;
                        this.filtered_stocks = [];
                    for(let i=0;i<stocks.length;i++){                       
                     stk_items.push(stocks[i]['item_stock']);                     
                    }
                    for(let i=0;i<stk_items.length;i++){
                        for(let k=0;k<stk_items[i].length;k++){
                            stk_items_2.push(stk_items[i][k]);
                        }
                    }
                    for(let i=0;i<stk_items_2.length;i++){
                       for(let j=0;j<this.stocks.length;j++){
                        if (stk_items_2[i]['item_id'] == this.stocks[j]['id']) {
                          this.filtered_stocks.push({
                            'id':this.stocks[j]['id'],
                            'qty':stk_items_2[i]['qty'],
                            'uom':this.uoms.find(u => u.id == stk_items_2[i]['uom']).name,
                            'scheduled_date':DateConverter.conversion(stk_items_2[i]['scheduled_date']),
                            'description':`${this.stocks[j]['code']}-${this.stocks[j]['description']}`
                        });
                        }                       
                       }
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
         getEnquiries(){
          axios.get('enquiry')
          .then(res => {
            this.suppliers = res.data.suppliers;
            this.stocks = res.data.stocks;
            this.assets = res.data.assets;
            this.uoms = res.data.uoms;
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
            this.form.req_date_from = DateConverter.convertDate(this.form.req_date_from);
            this.form.req_date_to = DateConverter.convertDate(this.form.req_date_to);
                this.edit_enquiry ? this.update() : this.save();
            },
            save(){
                return console.log(this.form);
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
                    this.form = this.$store.state.enquiries
                }
            },
        },
        components:{
            datepicker,
            Multiselect
        }
    }
</script>

<style scoped>

</style>
