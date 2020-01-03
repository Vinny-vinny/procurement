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
                                            <th>Supplier Quote Rate</th> 
                                            <th>Supplier Delivery Date</th> 
                                            <th>Max. Delivery Qty</th>  
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">                 

                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Stock Item">
                                                <option selected disabled>Select Stock Item</option>
                                                <option :value="stock.id" v-for="stock in filtered_stock_items" :key="stock.id">{{stock.code}}-{{stock.description}}</option>
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
                                            <th>Supplier Quote Rate</th> 
                                            <th>Supplier Delivery Date</th> 
                                            <th>Max. Delivery Qty</th>   
                                            <th>Scheduled Date</th> 
                                            <th></th>                         
                                        </tr>
                                        <tr v-for="(m,i) in form.item_asset">                           
                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Asset">
                                                <option selected disabled>Select Asset</option>
                                                <option :value="asset.id" v-for="asset in filtered_assets" :key="asset.id">{{asset.code}}-{{asset.description}}</option>
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
                filtered_assets:{},
                filtered_stocks:{}
            }
        },
        created(){
            this.listen();
            this.getQuotations();
        },
        methods:{
            getItems(){
            if (this.form.enquiry_id !=='') {
             this.form.item_type =='';
             return this.$toastr.e('Please select Enquiry No first.');
            }
            if (this.form.item_type =='asset') {
             if (Custom.isEmpty(this.filtered_assets)) {
                this.show_asset = false;
                this.show_stock = false;
              return this.$toastr.e('Sorry,you do not have assets for the selected Enquiry No.');
             }   
             this.show_asset = true;
             this.show_stock = false;
            }

             if (this.form.item_type =='stock') {
             if (Custom.isEmpty(this.filtered_stocks)) {
                this.show_asset = false;
                this.show_stock = false;
              return this.$toastr.e('Sorry,you do not have stock items for the selected Enquiry No.');
             }   
             this.show_asset = false;
             this.show_stock = true;
            }
            
            },
            getSuppliers(){         
            let enquiry = this.enquiries.find(e => e.id ==this.form.enquiry_id);
            console.log(enquiry)
            for(let i=0;i<enquiry.supplier_id.length;i++){
           for(let j=0;j<this.suppliers.length;j++){            
            if (enquiry.supplier_id[i] == this.suppliers[j]['id']) {
              this.filtered_suppliers.push(this.suppliers[j]);
            }
           }
           }
          
            if (enquiry.item_asset[0]['item_id'] !==null) {
              this.filtered_assets = enquiry.item_asset[0];
            }
            if (enquiry.item_stock[0]['item_id'] !==null) {
             this.filtered_stocks = enquiry.item_stock[0];
            }
            },
            getQuotations(){
            axios.get('quotations')
            .then(res => {
                this.quotations = res.data.quotations;
                this.suppliers = res.data.suppliers;
                this.enquiries =  res.data.enquiries;
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
