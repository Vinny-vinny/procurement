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
                        <div class="form-group">
                            <label>Department</label>
                            <select class="form-control" v-model="form.department_id" required>
                                <option :value="department.id" v-for="department in departments" :key="department.id">{{department.name}}</option>
                            </select>
                        </div>
                       <div class="form-group">
                           <label>Begins On</label>
                           <datepicker v-model="form.begins_on" required></datepicker>
                       </div>
                        <div class="form-group">
                            <label>Ends On</label>
                            <datepicker v-model="form.ends_on" required></datepicker>
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
       budgeting(){ 
       let total =0;        
         if ((Object.values(this.form.item_stock[0])[0] !== '' && Object.values(this.form.item_stock[0])[0] !== null) || (Object.values(this.form.item_stock[0])[1] !== '' && Object.values(this.form.item_stock[0])[1] !== null)) {
                    for(let i=0;i<this.form.item_stock.length;i++){         
            if (this.form.item_stock[i]['item_id'] !=='' && this.form.item_stock[i]['amount'] !=='') {
                total+=parseFloat(this.form.item_stock[i]['amount']);
            }   
         }  
         } 
          if ((Object.values(this.form.item_asset[0])[0] !== '' && Object.values(this.form.item_asset[0])[0] !== null) || (Object.values(this.form.item_asset[0])[1] !== '' && Object.values(this.form.item_asset[0])[1] !== null)) {               
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
        budgeting(){
            return [this.stock_item,this.budget_amount,this.form.item_stock,this.form.item_asset].join();
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
                this.form.begins_on = DateConverter.convertDate(this.form.begins_on);
                this.form.ends_on = DateConverter.convertDate(this.form.ends_on);
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
                   // this.form.total_amount = this.$store.state.budgets.total_amount;                
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

</style>
