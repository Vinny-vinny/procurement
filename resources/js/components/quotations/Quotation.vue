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
                            <select v-model="form.enquiry_id" class="form-control" required @change="getSuppliers()">
                               <option :value="enq.id" v-for="enq in enquiries" :key="enq.id">{{enq.enquiry_no}}</option> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quotation Description</label>
                            <input type="text" v-model="form.description" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Supplier</label>
                            <select v-model="form.supplier_id" class="form-control" required>
                                <option :value="supplier.id" v-for="supplier in filtered_suppliers" :key="supplier.id">{{supplier.name}}</option>
                            </select>
                        </div>
                         <div class="form-group">
                            <label>Item Type</label>
                            <select v-model="form.item_type" class="form-control" required @change="getItems()">
                                <option value="stock">Stock</option>
                                <option value="asset">Asset</option>
                            </select>
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
                                            <th>Quote Rate</th> 
                                            <th>Delivery Date</th> 
                                            <th>Max. Delivery Qty</th>  
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">                 

                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Stock Item" @change="stk_id = m.item_id">
                                                <option selected disabled>Select Stock Item</option>
                                                <option :value="stock.id" v-for="stock in filtered_stocks" :key="stock.id">{{stock.description}}</option>
                                            </select></td>                                          

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
                                       
                                </div>
                                <div class="form-group" v-if="show_asset">
                                    <label>Assets</label>
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
                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Asset" @change="stk_id = m.item_id">
                                                <option selected disabled>Select Asset</option>
                                                <option :value="asset.id" v-for="asset in filtered_assets" :key="asset.id">{{asset.description}}</option>
                                            </select></td>
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
                    id:''
                },
                edit_quotation: this.edit,
                quotations:{},
                show_stock:false,
                show_asset:false,
                supplers:{},
                enquiries:{},
                filtered_suppliers:[],
                filtered_assets:[],
                filtered_stocks:[],
                stocks:{},
                assets:{},
                stk_id:''
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
            },
        },
        computed:{
         reqs(){
        return [this.stk_id,this.form.item_stock,this.form.item_asset].join();
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
              return this.$toastr.e('Sorry,you do not have assets for the selected Enquiry No.');
             }
             },500)
              setTimeout(()=>{
              this.show_asset = true;
             this.show_stock = false;
                },501)            
            
            }
             if (this.form.item_type =='stock') {
               setTimeout(()=>{
                if (Custom.isEmpty(this.filtered_stocks)) {
                this.show_asset = false;
                this.show_stock = false;
              return this.$toastr.e('Sorry,you do not have stock items for the selected Enquiry No.');
             } 
               },500)
              setTimeout(()=>{
             this.show_asset = false;
             this.show_stock = true; 
              },501) 
            
            }
            
            },
            getSuppliers(){   
            this.filtered_suppliers = [];      
            let enquiry = this.enquiries.find(e => e.id ==this.form.enquiry_id);          
            for(let i=0;i<enquiry.supplier_id.length;i++){
           for(let j=0;j<this.suppliers.length;j++){            
            if (enquiry.supplier_id[i] == this.suppliers[j]['id']) {
              this.filtered_suppliers.push(this.suppliers[j]);
            }
           }
           }
            let filtered_assets = [];
            let filtered_stocks = [];
            this.filtered_assets = [];
            this.filtered_stocks = [];
            if (enquiry.item_asset[0]['item_id'] !==null) {
                for(let i=0;i<enquiry.item_asset.length;i++){
                filtered_assets.push(enquiry.item_asset[i]) 
                } 
                for(let i=0;i<filtered_assets.length;i++){
                    for(let j=0;j<this.assets.length;j++){
                        if (filtered_assets[i]['item_id'] == this.assets[j]['id']) {
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
             
            }
            if (enquiry.item_stock[0]['item_id'] !==null) {
                for(let i=0;i<enquiry.item_stock.length;i++){
                filtered_stocks.push(enquiry.item_stock[i]);
                }  
             
                for(let k=0;k<filtered_stocks.length;k++){
                    for(let j=0;j<this.stocks.length;j++){
                        if (filtered_stocks[k]['item_id'] == this.stocks[j]['id']) {
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
            saveQuotation(){
                 if (Object.values(this.form.item_stock[0])[0] !== '' || Object.values(this.form.item_stock[0])[1] !== '' || Object.values(this.form.item_stock[0])[2] !== '' || Object.values(this.form.item_stock[0])[3] !== '' || Object.values(this.form.item_stock[0])[4] !== '' || Object.values(this.form.item_stock[0])[5] !== '' || Object.values(this.form.item_stock[0])[6] !== '') {
                    for (let i = 0; i < this.form.item_stock.length; i++) {
                        if (this.form.item_stock[i]['item_id'] === '' || this.form.item_stock[i]['rate'] === '' || this.form.item_stock[i]['delivery_date'] === '' || this.form.item_stock[i]['max_qty'] === '') {
                            return this.$toastr.e('Please all Stock Items fields are required.');
                        }
                    }
                }

                 if (Object.values(this.form.item_asset[0])[0] !== '' || Object.values(this.form.item_asset[0])[1] !== '' || Object.values(this.form.item_asset[0])[2] !== '' || Object.values(this.form.item_asset[0])[3] !== '' || Object.values(this.form.item_asset[0])[4] !== '' || Object.values(this.form.item_asset[0])[4] !== '' || Object.values(this.form.item_asset[0])[5] !== '' || Object.values(this.form.item_asset[0])[6] !== '') {
                    for (let i = 0; i < this.form.item_asset.length; i++) {
                        if (this.form.item_asset[i]['item_id'] === '' || this.form.item_asset[i]['rate'] === '' || this.form.item_asset[i]['delivery_date'] === '' || this.form.item_asset[i]['max_qty'] === '') {
                            return this.$toastr.e('Please all Assets fields are required.');
                        }
                    }
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
                    setTimeout(()=>{
                    this.getItems();
                    this.getSuppliers();
                    },1000)
                }
            },
        },
        components:{
            datepicker
        }
    }
</script>

<style scoped>

</style>
