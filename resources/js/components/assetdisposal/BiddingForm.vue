<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bidding Form</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveBid()">
                        <div class="row">
                            <div class="col-md-4">
                            <div class="form-group">
                            <label>Disposal No</label>
                            <input type="text" class="form-control" v-model="disposal_no" required disabled>
                        </div>
                            </div>
                             <div class="col-md-4">
                             <div class="form-group">
                            <label>Bid Date</label>
                            <datepicker v-model="form.bid_date" required></datepicker>
                            </div> 
                            </div>
                             <div class="col-md-4">
                             <div class="form-group">
                            <label>Staff Name</label>
                            <input type="text" class="form-control" v-model="username" required disabled>
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
                                            <th>Reserved Price(NBV)</th>
                                            <th>Amount</th>
                                            <th>Picture</th>  
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.asset_details">              

                                            <td>                                             
                                        <model-select :options="all_assets"
                                        v-model="m.asset_id"
                                        class="i_p_3 qq"
                                        >
                                        </model-select>
                                          </td>                                          
                                          <td><input type="number" step="0.001" class="form-control ite_m" v-model="m.reserved_amount"
                                                       placeholder="Reserved Price" disabled></td>
                                          <td><input type="number" step="0.001" class="form-control ite_m" v-model="m.amount"
                                                       placeholder="Amount"></td>
                                            <td>                                            
                                              <a :href="m.picture" target="_blank" class="btn btn-default ite_m"><i class="fa fa-eye"></i>View Image</a>
                                              </td>                                  
                                                   
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeItem(i)"
                                                   v-show="i || (!i && form.asset_details.length > 1)"></i>
                                                <!-- <i style="display:none" class="fa fa-plus-circle add" @click="addItem(i)"
                                                   v-show="i == form.asset_details.length -1"></i> -->
                                            </td>
                                        </tr>
                                    </table>
                                  </fieldset>
                                       
                                </div>
                                <div class="form-group">
                                    <label>Mode Of Payment</label>
                                    <select class="form-control" v-model="form.payment_mode_id" required>
                                        <option :value="mode.id" v-for="mode in payment_modes" :ke="mode.id">{{mode.name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                                              
                        <button type="submit" class="btn btn-primary">Save</button>
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
                    bid_date:'',
                    user_id:User.id(),
                    disposal_id:'',
                    asset_details:[{asset_id:'',reserved_amount:'',amount:'',picture:''}],
                    payment_mode_id:''
                },
                username:User.name(),
                edit_category: this.edit,
                assets:{},
                all_assets:[],
                disposals:{},
                disposal_no:'',
                payment_modes:{},
                bids:{}

            }
        },
        created(){          
            this.getAssetsDetails();
            this.fetchDetails(); 

        },
        methods:{
            fetchDetails(){
             setTimeout(()=>{
             let disposal = this.disposals.find(d => d.id == this.$route.params['id']);
             this.disposal_no = disposal.disposal_no;
             this.form.disposal_id = disposal.id;            
             for(let i=0;i<disposal.asset_details.length;i++){
                this.form.asset_details.push({
                    'asset_id':disposal.asset_details[i]['asset_id'],
                    'reserved_amount': disposal.asset_details[i]['amount'],
                    'amount': '',
                    'picture':site_url+'uploads/'+disposal.asset_details[i]['picture']
                })
             }
            this.form.asset_details.splice(this.form.asset_details[0],1);
             this.form.asset_details.forEach(asset =>{
                this.assets.forEach(asst => {
                    if (asset.asset_id == asst.id) {
                        this.all_assets.push({
                            'value':asst.id,
                            'text':`${asst.code}-${asst.description}`
                        })
                    }
                })
             })            
             },1000)
            },
            getAssetsDetails(){
            axios.get('bidding')
            .then(res => {               
             this.assets = res.data.assets;
             this.disposals = res.data.disposals;
             this.payment_modes = res.data.payment_modes;
             this.bids = res.data.biddings;

         if (this.bids.find(bid => bid.disposal_id ==this.$route.params['id'] && User.id() == bid.user_id)) {
            this.$toastr.e('Sorry,You cannot bid more than once.');
            setTimeout(()=>{
            this.$router.push('/bidding');
            },3000)
           } 
             })
            },
            removeItem(i){
             this.form.asset_details.splice(i,1);
            },
            addItem(){
            this.form.asset_details.push({asset_id:'',reserved_amount:'',amount:'',picture:''});
            },
                saveBid(){
                if (this.form.bid_date=='') {
                    return this.$toastr.e('Sorry,Bid Date is required.');
                }
                for(let i=0;i<this.form.asset_details.length;i++){
                    if (this.form.asset_details[i]['asset_id'] =='' || this.form.asset_details[i]['amount'] ==''){
                        return this.$toastr.e('Sorry,all asset details fields are required.');
                    }
                }
                let bids = this.bids.filter(b => b.disposal_id == this.form.disposal_id);
                      if (bids.length > 0) {
                      if (bids.find(b => b.user_id == this.form.user_id)) {
                        return this.$toastr.e('Sorry,you cannot bid more than once.');
                    }
                }
               // return console.log(this.form);               
                this.form.bid_date = moment(this.form.bid_date).format('YYYY-MM-DD HH:MM:ss');
                axios.post('bidding',this.form)
                    .then(res => {
                        this.$router.push(`/bidding`);
                        //eventBus.$emit('listCategory',res.data)
                    })
                    .catch(error => error.response)
            },           
            cancel(){
                this.$router.push('/bidding');
                //eventBus.$emit('cancel')
            },
           },
        components:{
        datepicker,
        ModelSelect
        },
    }
</script>

<style scoped>
.ite_m{
    margin-bottom:5px;
}

</style>
