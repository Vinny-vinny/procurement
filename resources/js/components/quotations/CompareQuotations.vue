<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div>
             <div class="card">
                <div class="card-header with-border">
                    <h5 class="card-title">Approve Quotations</h5>
                </div>
                <div class="card-body">                  
                        <div class="row">
                            <div class="col-md-6">
                                 <div class="form-group">
                            <label>Quotation</label>
                            <model-select :options="quotations"
                                        v-model="quotation_id"                              
                                        @input="getSupplier()"  
                                        >
                               </model-select>
                        </div>
                            </div>
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Supplier</label>
                                      <input type="text" class="form-control" v-model="supplier">
                                  </div>
                              </div>
                        </div>

                        <div class="row">
                           <div class="col-md-12"v-if="filtered_stocks.length">
                            <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Stock Items</label></legend>
                               <table class="table table-striped">
                                   <tr>
                                       <th>Item</th>
                                       <th>Qty</th>
                                       <th>Uom</th>
                                       <th>Scheduled Date</th>
                                       <th>Quote Rate</th>
                                       <th>Delivery Date</th>
                                       <th>Maximum Delivery Qty</th>
                                   </tr>
                                   <tr v-for="stk in filtered_stocks" :key="stk.item_id">
                                       <td>{{stk.item}}</td>
                                       <td>{{stk.qty}}</td>
                                       <td>{{stk.uom}}</td>
                                       <td>{{stk.scheduled_date}}</td>
                                       <td>{{stk.rate}}</td>
                                       <td>{{stk.delivery_date}}</td>
                                       <td>{{stk.max_qty}}</td>
                                   </tr>
                                 

                               </table>
                           </fieldset>
                           </div>
                        </div>
                        <br><br>
                         <div class="row">
                           <div class="col-md-12"v-if="filtered_assets.length">
                            <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Assets</label></legend>
                               <table class="table table-striped">
                                   <tr>
                                       <th>Item</th>
                                       <th>Qty</th>
                                       <th>Uom</th>
                                       <th>Scheduled Date</th>
                                       <th>Quote Rate</th>
                                       <th>Delivery Date</th>
                                       <th>Maximum Delivery Qty</th>
                                   </tr>
                                   <tr v-for="asset in filtered_assets" :key="asset.item_id">
                                       <td>{{asset.item}}</td>
                                       <td>{{asset.qty}}</td>
                                       <td>{{asset.uom}}</td>
                                       <td>{{asset.scheduled_date}}</td>
                                       <td>{{asset.rate}}</td>
                                       <td>{{asset.delivery_date}}</td>
                                       <td>{{asset.max_qty}}</td>
                                   </tr>
                                 

                               </table>
                           </fieldset>
                           </div>
                        </div>  

                           <br><br>
                         <div class="row">
                           <div class="col-md-12"v-if="filtered_services.length">
                            <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Services</label></legend>
                               <table class="table table-striped">
                                   <tr>
                                       <th>Name</th>
                                       <th>Description</th>
                                       <th>Amount</th>
                                       <th>Scheduled Date</th>
                                       <th>Quote Rate</th>
                                       <th>Delivery Date</th>                                   
                                   </tr>
                                   <tr v-for="service in filtered_services" :key="service.item_id">
                                       <td>{{service.item}}</td>
                                       <td>{{service.description}}</td>
                                       <td>{{service.amount}}</td>
                                       <td>{{service.scheduled_date}}</td>
                                       <td>{{service.rate}}</td>
                                       <td>{{service.delivery_date}}</td>                               
                                   </tr>
                                 

                               </table>
                           </fieldset>
                           </div>
                        </div>                  
                 
                </div>
                
            </div>       


            </div>
            
        </section>

    </div>
</template>

<script>
 import { ModelSelect } from 'vue-search-select';
    export default {
        data(){
            return {
              quotations:[],
              quotation_id:'',
              supplier:'' ,
              suppliers:{},
              all_quotes:{},
              stocks:{},
              assets:{},
              services:{},
              filtered_stocks:[],
              filtered_assets:[],
              filtered_services:[]         
            
            }
        },
        created(){
        this.getQuotations();
        },
        methods:{
         getQuotations(){
            axios.get('quotations')
            .then(res => { 
            this.suppliers = res.data.suppliers; 
            this.all_quotes = res.data.filtered_quotations; 
            this.assets = res.data.assets;
            this.stocks = res.data.stock_items;
            this.services = res.data.services;

             res.data.filtered_quotations.forEach(s => {
                this.quotations.push({
                    'value': s.id,
                    'text': s.quote_no
                })
             })
         
            })
         },
         getSupplier(){
            let quote = this.all_quotes.find(q => q.id == this.quotation_id);
            this.supplier = this.suppliers.find(s => s.id == quote.supplier_id).name;
            this.filtered_stocks = [];
            this.filtered_assets = [];
            this.filtered_services = [];

          if (quote.item_stock[0]['item_id']) {
            for(let i=0;i<quote.item_stock.length;i++){
                this.filtered_stocks.push({
                    'item': this.stocks.find(a => a.id == quote.item_stock[i]['item_id']).code +'-'+ this.stocks.find(a => a.id == quote.item_stock[i]['item_id']).description,
                    'uom': quote.item_stock[i]['uom'],
                    'qty': quote.item_stock[i]['qty'],
                    'scheduled_date': quote.item_stock[i]['scheduled_date'],
                    'rate': quote.item_stock[i]['rate'],
                    'delivery_date': moment(quote.item_stock[i]['delivery_date']).format('DD-MM-YYYY'),
                    'max_qty': quote.item_stock[i]['max_qty'], 
                })
            }
          }
          if (quote.item_asset[0]['item_id']) {           
            for(let i=0;i<quote.item_asset.length;i++){
                this.filtered_assets.push({
                    'item': this.assets.find(a => a.id == quote.item_asset[i]['item_id']).code +'-'+ this.assets.find(a => a.id == quote.item_asset[i]['item_id']).description,
                    'uom': quote.item_asset[i]['uom'],
                    'qty': quote.item_asset[i]['qty'],
                    'scheduled_date': quote.item_asset[i]['scheduled_date'],
                    'rate': quote.item_asset[i]['rate'],
                    'delivery_date': moment(quote.item_asset[i]['delivery_date']).format('DD-MM-YYYY'),
                    'max_qty': quote.item_asset[i]['max_qty'], 
                })
            }
          }
           if (quote.item_service[0]['item_id']) {
            for(let i=0;i<quote.item_service.length;i++){
                this.filtered_services.push({
                    'item': this.services.find(s => s.id == quote.item_service[i]['item_id']).name,            
                    'description': quote.item_service[i]['description'],
                    'amount': quote.item_service[i]['amount'],
                    'scheduled_date': moment(quote.item_service[i]['scheduled_date']).format('DD-MM-YYYY'),
                    'rate': quote.item_service[i]['rate'],
                    'delivery_date': moment(quote.item_service[i]['delivery_date']).format('DD-MM-YYYY')            
                })
            }
          }

          }
        },
        components:{
            ModelSelect
        }
    }
</script>

<style scoped>

</style>
