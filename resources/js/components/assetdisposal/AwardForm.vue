<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bid Award Form</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveAward()">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>Disposal No</label>
                            <input type="text" class="form-control" v-model="disposal_no" disabled>
                        </div>
                            </div>
                             <div class="col-md-6">
                            <div class="form-group">
                            <label>Bid Expiry Date</label>
                            <input type="text" class="form-control" v-model="date_closed" disabled>
                        </div>
                         </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Disposal Item</label>
                                   <model-select :options="all_assets"
                                        v-model="asset_id"  
                                        @input="getBids()"                                   
                                        >
                                        </model-select>
                                </div>

                                <div class="form-group">
                               <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Assets Details</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>                                         
                                            <th>Bid#</th>
                                            <th>Bid Date</th>
                                            <th>Amount</th>
                                            <th>Staff</th>                                                                       
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.asset_details">     
                                         <td>                                             
                                        <input type="text" class="form-control ite_m" v-model="m.asset_name">
                                          <input type="text" class="form-control ite_m" v-model="m.asset_id" style="display:none">
                                          </td>                                          
                                          <td><input type="text" class="form-control ite_m" v-model="m.bid_no"
                                            placeholder="Reserved Price">
                                          <input type="text" class="form-control ite_m" v-model="m.bid_id"
                                            style="display:none">
                                        </td>
                                        <td>
                                          <input type="text" class="form-control ite_m" v-model="m.bid_date"
                                           >
                                        </td>
                                            <td><input type="number" step="0.001" class="form-control ite_m" v-model="m.amount"
                                            placeholder="Amount"></td>
                                            <td><input type="text" class="form-control ite_m" v-model="m.staff"
                                            placeholder="Staff">
                                            <input type="text" class="form-control ite_m" v-model="m.staff_id" style="display:none">
                                        </td>                                                                            
                                            <td>                                                                            
                                             <button class="btn btn-primary btn-sm" v-if="form.asset_details.length && asset_id && m.staff_id" @click="award(i)"> <i class="fa fa-trophy"></i>Award</button>
                                            </td>
                                        </tr>
                                    </table>
                                  </fieldset>
                                       
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Process</button>
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
                form:{                   
                    disposal_id:'',                  
                    asset_details:[{asset_id:'',asset_name:'',staff:'',staff_id:'',bid_no:'',bid_id:'',amount:'',bid_date:''}],
                    awarded:[],                    
                },
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
                items:{}

            }
        },
        created(){
           this.getDetails();
           this.fetchDetails();
           console.log(this.$route.params['id'])
              },
       methods:{
         award(i){
         let item = this.form.asset_details.find(asset => asset.asset_id == this.form.asset_details[i]['asset_id']).awarded=1; 
         this.form.awarded.push(this.form.asset_details[i]);     
         this.form.asset_details = this.form.asset_details.filter(asset => asset.asset_id !==this.asset_id);         
         },
       getBids(){
        let awarded_ids =[];
        if (this.form.awarded.length) {      
          awarded_ids = this.form.awarded.map(p =>{
          return p.asset_id;
          })
        }  
       
       let bids = this.bids.filter(b => b.disposal_id == this.$route.params['id']);
       console.log(bids)
       this.form.asset_details=[];  
        for(let k=0;k<bids.length;k++){
         let asset_details = JSON.parse(bids[k]['asset_details']);
         for(let j=0;j<asset_details.length;j++){
           if (asset_details[j]['asset_id'] ==this.asset_id) {             
             this.form.asset_details.push({
                'asset_id': asset_details[j]['asset_id'], 
                'asset_name': this.assets.find(asset => asset.id == asset_details[j]['asset_id']).code+'-'+this.assets.find(asset => asset.id == asset_details[j]['asset_id']).description,               
                'staff': this.users.find(d => d.id == bids[k]['user_id']).name,
                'staff_id': bids[k]['user_id'],
                'bid_no': bids[k]['bid_no'],
                'bid_id': bids[k]['id'],
                'amount': asset_details[j]['amount'],
                'bid_date':moment(bids[k]['bid_date']).format('DD-MM-YYYY')
             });            
            }
         }
        }
       let walla = [];
        if (this.form.asset_details.length > 0 && this.form.awarded.length > 0) {
            for(let a=0;a<this.form.asset_details.length;a++){
                if (!awarded_ids.includes(this.form.asset_details[a]['asset_id'])) {
                    walla.push(this.form.asset_details[a]);         
                }                          
            }
          this.form.asset_details = walla;          
        }  
         
        if(this.form.asset_details.length==0) {
         this.form.asset_details = [{asset_id:'',asset_name:'',staff:'',staff_id:'',reserved_amount:'',amount:'',awarded:''}];   
        }
     
        },
       fetchDetails(){
       setTimeout(()=>{
       this.form.disposal_id = this.$route.params['id']
       let disposal = this.disposals.find(d => d.id == this.form.disposal_id);
       this.disposal_no = disposal.disposal_no;
       this.date_closed = disposal.date_closed;
        for(let i=0;i<disposal.asset_details.length;i++){
        this.assets.forEach(asset => {
            if(asset.id == disposal.asset_details[i]['asset_id']){
                this.all_assets.push({
                    'value':asset.id,
                    'text':`${asset.code}-${asset.description}`
                })
            }
        })
       }
       },1000)
        },
         getDetails(){
         axios.get('bid-award')
         .then(res => {         
          this.bids = res.data.all_bids;
          this.disposals = res.data.disposals;
          this.awards = res.data.awards;
          this.assets = res.data.assets;
          this.users = res.data.users;
         })
         },         
            saveAward(){ 
            if (this.asset_id =='') {
             return this.$toastr.e('Disposal item field is required.');
            } 
            if(this.form.awarded.length ==0){
                return this.$toastr.e('Sorry,Please you must award the assets before processing.');
            } 
                   axios.post('bid-award',this.form)
                    .then(res => {
                     console.log(res.data);
                      //eventBus.$emit('listCategory',res.data)
                    })
                    .catch(error => error.response)
            },
          
            cancel(){
                eventBus.$emit('cancel')
            },
           
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
