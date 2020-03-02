<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bid# {{form.bid_no}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveAward()">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>Disposal No</label>
                            <input type="text" class="form-control" v-model="form.disposal_no" disabled>
                        </div>
                            </div>
                             <div class="col-md-6">
                            <div class="form-group">
                            <label>Bid Date</label>
                            <input type="text" class="form-control" v-model="form.bid_date" disabled>
                        </div>
                         </div>
                        </div>
                           <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>Payment Mode</label>
                            <input type="text" class="form-control" v-model="form.payment_mode" disabled>
                        </div>
                            </div>
                             <div class="col-md-6">
                            <div class="form-group">
                            <label>Staff</label>
                            <input type="text" class="form-control" v-model="form.user" disabled>
                        </div>
                         </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">                            

                                <div class="form-group">
                               <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Assets Details</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>                                         
                                            <th>Amount</th>
                                            <th>Picture</th>                                                                       
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in asset_details">     
                                         <td>                                             
                                        <input type="text" class="form-control ite_m" v-model="m.asset_name">                                         
                                          </td>                              
                                         <td><input type="number" step="0.001" class="form-control ite_m" v-model="m.amount"
                                            placeholder="Amount">
                                            </td>  
                                         <td>
                                            <a :href="m.picture" target="_blank" class="btn btn-default ite_m"><i class="fa fa-eye"></i>View Image</a>      
                                                                                   </td>                                                                         
                                            <td>                                                    
                                            </td>
                                        </tr>
                                    </table>
                                  </fieldset>
                                       
                                </div>
                            </div>
                        </div>                       
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
 import { ModelSelect } from 'vue-search-select';
    export default {
        props:['edit'],
        data(){
            return {
                form:{}  ,
                bids:{},
                date_closed:'',
                awards:{},
                disposals:{},
                all_assets:[],
                assets:{},
                users:{},
                bid_items:[],              
                disposal_no:'',
                asset_id:'',
                items:{},  
                asset_details:[]              

            }
        },
        created(){
           this.getDetails();       
           this.listen();
             },
         methods:{
         getDetails(){
         axios.get('bid-award')
         .then(res => {         
          this.bids = res.data.bids;
          this.disposals = res.data.disposals;
          this.awards = res.data.awards;
          this.assets = res.data.assets;
          this.users = res.data.users;
         })
         },      
          
            cancel(){
             eventBus.$emit('cancel')
            },
            listen(){
            this.form = this.$store.state.bids; 
              
            setTimeout(()=>{
             this.bidDetails();
            },2000)
            },
            bidDetails(){
            // console.log(this.asset_details)
            for(let i=0;i<this.form.asset_details.length;i++){
             this.asset_details.push({
                'asset_name':this.assets.find(asset => asset.id == this.form.asset_details[i]['asset_id']).description,
                'amount': this.form.asset_details[i]['amount'],
                'picture': this.form.asset_details[i]['picture']
             })            
            }
            // console.log(this.asset_details)
            }
           
        },
        components:{
            ModelSelect
        }
    }
</script>

<style scoped>
.i_p_3{
    width:180px !important;
    margin-bottom: 5px;
}
</style>
