<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_disposal ? 'Update Disposal' : 'New Asset Disposal'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveDisposal()">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>Bid Opening Date</label>
                            <datepicker v-model="form.opening_date" required></datepicker>
                        </div>
                            </div>
                            <div class="col-md-6">
                               <div class="form-group">
                            <label>Bid Deadline Date of Submission</label>
                            <datepicker v-model="form.deadline_date" required></datepicker>
                        </div> 
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                             <div class="form-group">
                               <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Assets Details</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Reserved Price(NBV)</th>
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
                                          <td><input type="number" step="0.001" class="form-control ite_m" v-model="m.amount"
                                                       placeholder="Reserved Price"></td>
                                            <td>                                            
                                               <input type="file" v-on:change="onImageChange($event,m.asset_id)" class="form-control ite_m">
                                              </td>                                  
                                                   
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeItem(i)"
                                                   v-show="i || (!i && form.asset_details.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addItem(i)"
                                                   v-show="i == form.asset_details.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                  </fieldset>
                                       
                                </div> 
                            </div>
                        </div>
                    
                        <button type="submit" class="btn btn-primary">{{edit_disposal ? 'Update' : 'Save'}}</button>
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
                    asset_details:[{asset_id:'',amount:'',picture:''}],
                    opening_date:'',
                    deadline_date:''                   
                },
                edit_disposal: this.edit,
                all_assets:[],
                assets:{}
            }
        },
        created(){
            this.listen();
            this.getAssets();
        },
        methods:{
            onImageChange(e,item_id) {  
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0],item_id);
            },
            createImage(file,item_id) {
                console.log(item_id);
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                vm.form.asset_details.find(item => item.asset_id ==item_id).picture = e.target.result;
                };
                reader.readAsDataURL(file);              
               
               //console.log(this.form.asset_details);

            },
            getAssets(){
            axios.get('asset-disposal')
            .then(res => {
                this.assets = res.data.assets;
                res.data.assets.forEach(asset => {
                    this.all_assets.push({
                        'value': asset.id,
                        'text': `${asset.code}-${asset.description}`
                    })
                })  
            })
            },
            removeItem(i){
            this.form.asset_details.splice(i,1);
            },
            addItem(){
            this.form.asset_details.push({asset_id:'',amount:'',picture:''});
            },
            saveDisposal(){  
            if (this.form.opening_date=='' || this.form.deadline_date=='') {
                return this.$toastr.e('Sorry,Bid opening and deadline dates are required');
            }  
            this.form.opening_date = moment(this.form.opening_date).format('YYYY-MM-DD HH:mm:ss')  
            this.form.deadline_date = moment(this.form.deadline_date).format('YYYY-MM-DD HH:mm:ss')
            if(this.form.opening_date > this.form.deadline_date){
                return this.$toastr.e('Sorry,Bid opening date cannot be greater than Bid deadline date.')
            }
              let asset_obj = {};
              if (Object.values(this.form.asset_details[0])[0] == '' || Object.values(this.form.asset_details[0])[1] == '') {
                return this.$toastr.e('Assets Details cannot be empty.');

              }
                    for (let i = 0; i < this.form.asset_details.length; i++) {
                        if (this.form.asset_details[i]['asset_id'] === '' || this.form.asset_details[i]['amount'] === '') {
                            return this.$toastr.e('Please all Assets fields are required.');
                        }
                    }
                 
                 for(let i=0;i<this.form.asset_details.length;i++){        
                if(!asset_obj[this.form.asset_details[i]['asset_id']]){
                    asset_obj[this.form.asset_details[i]['asset_id']] = this.form.asset_details[i];
                } 
                else if(asset_obj[this.form.asset_details[i]['asset_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${this.assets.find(a => a.id ==asset_obj[this.form.asset_details[i]['asset_id']]['asset_id']).code} twice,Please check before proceeding.`);
                } 
            } 
                      
            this.edit_disposal ? this.update() : this.save();
            },
            save(){
                   axios.post('asset-disposal',this.form)
                    .then(res => {
                     //console.log(res.data);
                      eventBus.$emit('listDisposal',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                return console.log('oooooooo')
                axios.patch(`asset-disposal/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_disposal = false;
                        eventBus.$emit('updateDisposal',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.disposals
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
.i_p_3{
 width: 350px !important;
}
.qq{
    margin-bottom: 5px;
}
.ite_m{
    margin-right: 100px;
    margin-bottom: 5px;
}
</style>
