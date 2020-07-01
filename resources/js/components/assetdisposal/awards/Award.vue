<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Award# {{form.ref_no}}</h3>
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
                            <label>Created At</label>
                            <input type="text" class="form-control" v-model="form.created" disabled>
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
                                            <th>Staff</th>                                           
                                            <th></th>                                            
                                        </tr>
                                        <tr v-for="(m,i) in form.asset_details">     
                                       <td>                                             
                                        <input type="text" class="form-control ite_m" v-model="m.asset_name">                                         
                                          </td>                              
                                         <td><input type="number" step="0.001" class="form-control ite_m" v-model="m.amount"
                                            placeholder="Amount">
                                         </td>  
                                         <td>
                                          <input type="text" class="form-control ite_m" v-model="m.staff">    
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
                 }
        },
        created(){
           this.listen();
             },
         methods:{    
            cancel(){
             eventBus.$emit('cancel')
            },
            listen(){
            this.form = this.$store.state.awards; 
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
