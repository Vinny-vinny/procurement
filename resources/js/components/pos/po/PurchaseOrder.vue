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
                            <select class="form-control" v-model="form.item_type" required>
                                <option value="stock">Stock Item</option>
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
                                                <option :value="stock.id" v-for="stock in filtered_stock_items" :key="stock.id">{{stock.description}}</option>
                                            </select></td>                                          

                                            <td><input type="number" class="form-control cost" v-model="m.qty"
                                                       placeholder="Qty" disabled>                                                
                                                   </td>
                                            <td>
                                                <input type="text" class="form-control" v-model="m.uom" placeholder="UOM" disabled>
                                              </td>                                    
                                                       <td>
                                                       <input type="text" class="form-control" v-model="m.scheduled_date" disabled placeholder="Scheduled Date" disabled>
                                                       </td>
                                                     <td><input type="number" class="form-control cost" v-model="m.rate"
                                                       placeholder="Delivery Rate" disabled>                                                
                                                   </td>
                                                    <td><datepicker v-model="m.delivery_date" placeholder="Delivery Date" disabled></datepicker>                 
                                                   </td>
                                                    <td><input type="number" class="form-control cost" v-model="m.max_qty"
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
                                              <td><input type="text" class="form-control" v-model="m.scheduled_date" disabled placeholder="Scheduled Date" disabled>
                                              </td>
                                               <td><input type="number" class="form-control cost" v-model="m.rate"
                                                       placeholder="Delivery Rate" disabled>                                                
                                               </td>
                                                    <td><datepicker v-model="m.delivery_date" placeholder="Delivery Date"></datepicker disabled>                 
                                                   </td>
                                                    <td><input type="number" class="form-control cost" v-model="m.max_qty"
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
                    id:''
                },
                edit_po: this.edit,
                quotations:{},
                show_asset:false,
                show_stock:false,
                filtered_stock_items:[],
                filtered_assets:[],
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

            }  
            
        },           
        fetchItems(){          
                let budget_item = {};
                let stk_items = {};
                let asset_item = {};
          if (this.form.quotation_id !=='' && this.form.item_type !=='') {    
          
           setTimeout(()=>{
          let quotations = this.quotations.find(q => q.id == this.form.quotation_id);                      
         if (quotations ==undefined) {
           this.show_asset = false;
           this.show_stock = false;                      
           return this.$toastr.e(`The selected Quotation does not have budgeted ${this.form.item_type}.`);  
         }    
             if (this.form.item_type=='asset') {
                    if(quotations['item_asset'][0]['item_id'] != null){
                        asset_item = quotations['item_asset'];
                    }
                     if (Custom.isEmpty(asset_item)) {
                        this.show_asset = false;
                        this.show_stock = false;                
                      return this.$toastr.e(`The selected Quotation does not have budgeted Assets.`);
                     }  
                this.show_stock = false;
                this.show_asset = true;              
                this.filtered_assets = [];           
               for(let i=0;i<asset_item.length;i++){
                for(let j=0;j<this.assets.length;j++){
                    if (asset_item[i]['item_id'] == this.assets[j]['id']) {
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
                
                } 
                 if (this.form.item_type=='stock') {                                 
                    if(quotations['item_stock'][0]['item_id'] != null){
                        stk_items = quotations['item_stock'];
                    }  

                     if (Custom.isEmpty(stk_items)) { 
                        this.show_asset = false;
                        this.show_stock = false;                       
                      return this.$toastr.e(`The selected Quotation does not have budgeted Stock Items.`);
                     }
                this.show_stock = true;
                this.show_asset = false;                       
                this.filtered_stock_items = [];
                for(let i=0;i<stk_items.length;i++){
                    for(let j=0;j<this.stock_items.length;j++){                     
                        if (stk_items[i]['item_id'] == this.stock_items[j]['id']) {
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
        return [this.stk_id,this.form.item_stock,this.form.item_asset].join();
        }
        },
        methods:{
            getSupplier(){
             let supplier_id = this.quotations.find(q => q.id == this.form.quotation_id).supplier_id;
             let supplier = this.suppliers.find(s => s.id == supplier_id);
             this.form.supplier_id = supplier.id;
             this.supplier = supplier.name; 
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
            getpos(){
            axios.get('purchase-order')
            .then(res => {
            this.quotations = res.data.quotations;
            this.stock_items = res.data.stock_items;
            this.assets = res.data.assets;
            this.suppliers = res.data.suppliers;

            })
            },
            savePo(){
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

                    setTimeout(()=>{
                     this.getSupplier();
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
