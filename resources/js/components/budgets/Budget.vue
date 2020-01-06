<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_budget ? 'Update Department Budget' : 'New Department Budget'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveBudget()">
                        
                           <fieldset class="the-fieldset">
                            <legend class="the-legend"><label class="fyr">FINANCIAL YEAR</label></legend>
                             <div class="row">
                             <div class="col-md-6">
                             <div class="form-group">
                           <label>Begins On</label>
                           <datepicker v-model="form.begins_on" required></datepicker>
                                                   
                            </div> 
                                </div>
                                 <div class="col-md-6">
                                   <div class="form-group">
                            <label>Ends On</label>
                            <datepicker v-model="form.ends_on" required disabled></datepicker>
                        </div>  
                                 </div>
                            </div>                        
                           
                         </fieldset>    
                       <br>
                         <fieldset class="the-fieldset">
                            <legend class="the-legend"><label class="fyr">BUDGET FREQUENCY</label></legend>
                           <div class="fy">
                            <div class="row bf">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control" v-model="form.frequency_type">
                                        <option value="weeks">Weeks</option>
                                        <option value="months">Months</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Period</label>
                                    <input type="number" v-model="form.period" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <datepicker v-model="form.start_date" ref="start_date"></datepicker>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <datepicker v-model="form.end_date" ref="end_date"></datepicker>
                                </div>
                            </div>
                        </div> 
                           </div>                           
                         </fieldset>                           
                        <br>
                        <div class="row">
                            <div class="col-md-6">                                        
                            <div class="form-group">
                            <label>Department</label>
                            <select class="form-control" v-model="form.department_id" required>
                                <option :value="department.id" v-for="department in departments" :key="department.id">{{department.name}}</option>
                            </select>
                        </div>
                        </div>
                             <div class="col-md-6">                       
                            <div class="form-group">
                            <label>Budget Renewal Type</label>
                            <select v-model="form.renewal_type" class="form-control" required>
                                <option value="carried_forward">Budget Carried Forward</option>
                                <option value="recurring_budget">Recurring Budget</option>
                                <option value="relapses">Budget Replapses</option>
                            </select>
                        </div> 
                            </div>
                        </div>  
                                     
                           <div class="form-group">
                            <label>Item Type</label>
                            <select class="form-control" v-model="form.item_type"
                                                        placeholder="Item Type" @change="itemType()">
                                                <option selected disabled>Select Item Type</option>
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
                                            <th>Amount</th>
                                            <th></th>                                          
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">                 

                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Stock Item" @change="stock_item = m.item_id">
                                                <option selected disabled>Select Stock Item</option>
                                                <option :value="stock.id" v-for="stock in stock_items" :key="stock.id">{{stock.code}}-{{stock.description}}</option>
                                            </select></td>                                          

                                            <td><input type="number" class="form-control cost" v-model="m.amount"
                                                       placeholder="Amount" @keyup="budget_amount = m.amount"></td>
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
                                            <th>Amount</th>
                                            <th></th>                            
                                        </tr>
                                        <tr v-for="(m,i) in form.item_asset">                           
                                            <td><select class="form-control item" v-model="m.item_id"
                                                        placeholder="Asset" @change="stock_item = m.item_id">
                                                <option selected disabled>Select Asset</option>
                                                <option :value="asset.id" v-for="asset in assets" :key="asset.id">{{asset.code}}-{{asset.description}}</option>
                                            </select></td>
                                            <td><input type="number" class="form-control cost" v-model="m.amount"
                                                       placeholder="Amount" @keyup="budget_amount = m.amount"></td>
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
                        <div class="row">
                            <div class="col-md-6"></div>                          
                              <div class="col-md-6 pull-right budget">
                                   <div class="form-group">
                            <label>Budget Amount</label>
                            <input type="number" class="form-control" v-model="form.total_amount" id="b_budget" disabled>
                        </div>
                              </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_budget ? 'Update' : 'Save'}}</button>
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
                    department_id:'',
                    total_amount:0,
                    begins_on:'',
                    ends_on:'',
                    item_type:'',
                    renewal_type:'',
                    frequency_type:'',
                    period:'',
                    start_date:'',
                    end_date:'',
                    fr_end_date:'',
                    ending_date:'',
                    item_stock: [{item_id: '',amount: ''}],
                    item_asset: [{item_id: '',amount: ''}],
                    id:''
                },                
                edit_budget: this.edit,
                departments:{},
                assets:{},
                item_types:{},
                stock_items:{},
                show_stock:false,
                show_asset:false,
                budget_amount:'',
                stock_item:''
               
            }
        },
        created(){
            this.listen();
            this.getBudgetData();            
           
        },
        watch:{
            budget_frq(){
                if (this.form.period !=='' && this.form.frequency_type !=='' && this.form.begins_on !=='' && this.form.start_date !=='') {                                          
                       if ((DateConverter.convertDate(this.form.start_date) > DateConverter.convertDate(this.form.ends_on)) || (DateConverter.convertDate(this.form.start_date) < DateConverter.convertDate(this.form.begins_on))) {
                        this.$refs.start_date.clearDate();
                        this.form.start_date = '';
                        return this.$toastr.e('Sorry,Start date should be within the financial year.')
                       }
                       if (this.form.frequency_type =='weeks') {                       
                       let now = new Date(this.form.begins_on);
                       now.setDate(now.getDate() + this.form.period * 7);
                       this.form.end_date = now;                                        
                    }
             else if (this.form.frequency_type=='months') {   
          var CurrentDate = new Date();
         CurrentDate.setMonth(CurrentDate.getMonth() + this.form.period);
         this.form.end_date = CurrentDate;
                    }
                    if (DateConverter.convertDate(this.form.end_date) > DateConverter.convertDate(this.form.ends_on)) {
                        this.$refs.start_date.clearDate();
                        this.$refs.end_date.clearDate();
                        this.form.start_date = '';
                        this.form.end_date = '';
                        return this.$toastr.e('Sorry,End date should be within the financial year.')
                       } 

                }        
              
            },
            start_end(){
            if(this.form.begins_on !==''){          
             var aYearFromNow = new Date(this.form.begins_on);
             aYearFromNow.setFullYear(aYearFromNow.getFullYear() + 1);
             this.form.ends_on = aYearFromNow                    
            }

            },
       budgeting(){ 
       let total =0;   
       if (Object.values(this.form.item_stock[0])[1] !== '' && Object.values(this.form.item_stock[0])[1] !== null) {
               for(let i=0;i<this.form.item_stock.length;i++){         
            if (this.form.item_stock[i]['item_id'] !=='' && this.form.item_stock[i]['amount'] !=='') {
                total+=parseFloat(this.form.item_stock[i]['amount']);
            }   
         }  
         }      
            if (Object.values(this.form.item_asset[0])[1] !== '' && Object.values(this.form.item_asset[0])[1] !== null) {    
            for(let k=0;k<this.form.item_asset.length;k++){  
            if (this.form.item_asset[k]['item_id'] !=='' && this.form.item_asset[k]['amount'] !=='') {
                total+=parseFloat(this.form.item_asset[k]['amount']);
            }        
         } 
         }                    
          
        this.form.total_amount = total; 
        }
    },
        computed:{
            budget_frq(){
            return [this.form.period,this.form.frequency_type,this.form.begins_on,this.form.start_date,this.form.ends_on].join();
            },
        budgeting(){
            return [this.stock_item,this.budget_amount,this.form.item_stock,this.form.item_asset].join();
        },
        start_end(){
            return [this.form.begins_on,this.form.ends_on].join();
        }
        },
        methods:{         
            itemType(){
            if (this.form.item_type =='asset') {
                this.show_asset=true;
                this.show_stock=false;
            }  else if(this.form.item_type =='stock') {
               this.show_asset=false;
                this.show_stock=true; 
            }             
         
            },
            addItem(i) {
                this.form.item_stock.push({item_id: '', amount: ''});
            },
            removeItem(i) {
                this.form.item_stock.splice(i, 1);
            },
             addAsset(i){
            this.form.item_asset.push({item_id:'',amount:''});            
            },
            removeAsset(i){
            this.form.item_asset.splice(i,1);
            },           
            getBudgetData(){
                axios.get('department-budget')
                .then(res => {                 
                    this.departments = res.data.departments;
                    this.assets = res.data.assets;
                    this.item_types = res.data.item_types;
                    this.stock_items = res.data.stock_items;
                })
            },
            saveBudget(){
                  if (Object.values(this.form.item_stock[0])[0] !== '' || Object.values(this.form.item_stock[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_stock.length; i++) {
                        if (this.form.item_stock[i]['item_id'] === '' || this.form.item_stock[i]['amount'] === '' ) {
                            return this.$toastr.e('Please all Stock Items fields are required.');
                        }
                    }
                }
                if (Object.values(this.form.item_asset[0])[0] !== '' || Object.values(this.form.item_asset[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_asset.length; i++) {
                        if (this.form.item_asset[i]['item_id'] === '' || this.form.item_asset[i]['amount'] === '' ) {
                            return this.$toastr.e('Please all Assets fields are required.');
                        }
                    }
                }
                if (this.form.frequency_type !=='') {
                    if (this.form.period =='' || this.form.start_date =='') {
                        return this.$toastr.e('Sorry,All budget frequency fields are required.');
                    }
                this.form.start_date = DateConverter.convertDate(this.form.start_date);
                this.form.fr_end_date = DateConverter.convertDate(this.form.end_date);
                 }
                             
               this.form.begins_on = DateConverter.convertDate(this.form.begins_on);
               this.form.ending_date = DateConverter.convertDate(this.form.ends_on);   
                
                this.edit_budget ? this.update() : this.save();
            },
            save(){
                delete this.form.id;                       
                axios.post('department-budget',this.form)
                    .then(res => {                        
                        eventBus.$emit('listBudgets',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){              
                axios.patch(`department-budget/${this.form.id}`,this.form)
                    .then(res => {
                      //  console.log(res.data)
                        this.edit_budget = false;
                        eventBus.$emit('updateBudget',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){                
                    this.form = this.$store.state.budgets;                         
                     this.itemType();
                   

                }
            },

        },
        components:{
            datepicker
        }
    }
</script>

<style scoped>
.cost{
    margin-left:10px;
    margin-bottom:10px;
}
.add,.remove{
    margin-left:15px;
}
.item{
    margin-bottom:10px;
}
.budget{
    left:30%;
}
#b_budget{
    width:30%;
}
.the-legend {
    border-style: none;
    border-width: 0;
    font-size: 14px;
    line-height: 20px;
    margin-bottom: 0;
    width: auto;
    padding: 0 10px;
    border: 1px solid #e0e0e0;
}
.the-fieldset {
    border: 1px solid #e0e0e0;
    padding: 10px;
}
.fyr{
    font-weight:800
}
.fy{
    display:flex;
}
.bf{
    width:100%
}
</style>
