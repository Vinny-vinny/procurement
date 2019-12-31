<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_req ? 'Update Requisition' : 'New Requisition'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveRequisition()">
                        <div class="row">                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Requisition Date</label>
                                    <datepicker v-model="form.req_date" required></datepicker>
                                </div>
                                <div class="form-group">
                                    <label>Requistion Type</label>
                                    <select v-model="form.requisition_type_id" required class="form-control">
                                        <option :value="type.id" v-for="type in req_types" :key="type.id">{{type.name}}</option>
                                    </select>
                                </div>
                                 <div class="form-group">
                                    <label>Department</label>
                                    <select v-model="form.department_id" class="form-control" required @change="checkItems()">
                                        <option :value="department.id" v-for="department in departments" :key="department.id">{{department.name}}</option>
                                    </select>
                                </div>
                            <div class="form-group">
                                    <label>Item Type</label>
                                    <select v-model="form.item_type" required class="form-control" @change="fetchItems()">
                                       <option value="stock">Stock Item</option>
                                       <option value="asset">Asset</option>
                                    </select>
                                </div>  
                            </div>
                                  <div class="col-md-6">                          
                                <div class="form-group">
                                    <label>Requistion By</label>
                                    <input type="text" class="form-control" :value="username()" disabled>
                                </div>                             
                                <div class="form-group">
                                    <label>Project</label>
                                    <select v-model="form.project_id" required class="form-control">
                                        <option :value="project.id" v-for="project in projects" :key="project.id">{{project.code}} - {{project.code}}</option>
                                    </select>
                                </div>                                             
                           
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" v-model="form.description" required>
                                </div>                                  
                                <div class="form-group">
                                    <label>Priority</label>
                                    <select class="form-control" v-model="form.priority_id" required>
                                        <option :value="priority.id" v-for="priority in priorities" :key="priority.id">{{priority.name}}</option>
                                    </select>
                                </div>
                            </div>
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
                        <button type="submit" class="btn btn-primary">{{edit_req ? 'Update' : 'Save'}}</button>
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
                    req_date:'',
                    requisition_type_id:'',
                    requisition_by:'',
                    item_type:'',               
                    project_id:'',
                    department_id:'',                  
                    priority_id:'',
                    description:'',
                    item_stock: [{item_id: '',qty:'',uom: '',scheduled_date:''}],
                    item_asset: [{item_id: '',qty:'',uom: '',scheduled_date:''}],
                    id:''
                },
                edit_req: this.edit,
                req_types:{},
                departments:{},
                priorities:{},
                projects:{},       
                budgets:{},
                show_stock:false,
                show_asset:false,
                stock_items:{},
                assets:{},
                filtered_stock_items:[],
                filtered_assets:[],
                uoms:{}
            }
        },
        created(){
            this.listen();
            this.getRequisitions();
        },
        methods:{
            username(){
            return User.name();
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
            checkItems(){
          if (this.form.item_type !=='') {
            this.fetchItems();
          }
            },
            fetchItems(){
                let budget_item ={} ;
                let stk_items = {};
                let asset_item = {};
             if (this.form.department_id =='') {
                this.form.item_type = '';
                return this.$toastr.e('Please select department first');
             }                 

            for(let i=0;i<this.budgets.length;i++){
                if (this.form.item_type=='asset' && this.budgets[i]['department_id'] ==this.form.department_id) {
                    if(this.budgets[i]['item_asset'][0]['item_id'] != null){
                        asset_item = this.budgets[i]['item_asset'];
                    }
                     if (Custom.isEmpty(asset_item)) {
                        this.show_asset = false;
                        this.show_stock = false;   
                        this.form.item_stock = [{item_id: '',qty:'',uom: '',scheduled_date:''}];
                        this.form.item_asset = [{item_id: '',qty:'',uom: '',scheduled_date:''}]; 
                      return this.$toastr.e(`The selected department does not have budgeted Assets.`);
                     }  
                this.show_stock = false;
                this.show_asset = true;              
                this.filtered_assets = [];           
               for(let i=0;i<asset_item.length;i++){
                for(let j=0;j<this.assets.length;j++){
                    if (asset_item[i]['item_id'] == this.assets[j]['id']) {
                        this.filtered_assets.push(this.assets[j]);
                    }
                }
               }               
                } 

                 if (this.form.item_type=='stock' && this.budgets[i]['department_id'] ==this.form.department_id) {
                    if(this.budgets[i]['item_stock'][0]['item_id'] != null){
                        stk_items = this.budgets[i]['item_stock'];
                    } 
                     if (Custom.isEmpty(stk_items)) { 
                        this.show_asset = false;
                        this.show_stock = false;   
                        this.form.item_stock = [{item_id: '',qty:'',uom: '',scheduled_date:''}];
                        this.form.item_asset = [{item_id: '',qty:'',uom: '',scheduled_date:''}];
                      return this.$toastr.e(`The selected department does not have budgeted Stock Items.`);
                     }
                this.show_stock = true;
                this.show_asset = false;                       
                this.filtered_stock_items = [];
                for(let i=0;i<stk_items.length;i++){
                    for(let j=0;j<this.stock_items.length;j++){                     
                        if (stk_items[i]['item_id'] == this.stock_items[j]['id']) {
                            this.filtered_stock_items.push(this.stock_items[j]);
                        }
                    }
                }               
                }             
            }           
            },
            getRequisitions(){
              axios.get('requisitions')
                .then(res => {
                    this.req_types = res.data.requisition_types,
                    this.departments = res.data.departments,
                    this.priorities = res.data.priorities,
                    this.projects = res.data.projects,
                    this.stock_items = res.data.stock_items,
                    this.assets = res.data.assets,
                    this.budgets = res.data.budgets,
                    this.uoms    = res.data.uoms
                })
            },

            saveRequisition(){
                if (Object.values(this.form.item_stock[0])[0] !== '' || Object.values(this.form.item_stock[0])[1] !== '' || Object.values(this.form.item_stock[0])[2] !== '' || Object.values(this.form.item_stock[0])[3] !== '') {
                    for (let i = 0; i < this.form.item_stock.length; i++) {
                        if (this.form.item_stock[i]['item_id'] === '' || this.form.item_stock[i]['qty'] === '' || this.form.item_stock[i]['uom'] === '' || this.form.item_stock[i]['scheduled_date'] === '') {
                            return this.$toastr.e('Please all Stock Items fields are required.');
                        }
                    }
                }
                   if (Object.values(this.form.item_asset[0])[0] !== '' || Object.values(this.form.item_asset[0])[1] !== '' || Object.values(this.form.item_asset[0])[2] !== '' || Object.values(this.form.item_asset[0])[3] !== '') {
                    for (let i = 0; i < this.form.item_asset.length; i++) {
                        if (this.form.item_asset[i]['item_id'] === '' || this.form.item_asset[i]['qty'] === '' || this.form.item_asset[i]['uom'] === '' || this.form.item_asset[i]['scheduled_date'] === '') {
                            return this.$toastr.e('Please all Assets fields are required.');
                        }
                    }
                }        
                this.form.req_date = DateConverter.convertDate(this.form.req_date);
                this.edit_req ? this.update() : this.save();
            },
            save(){          
                delete this.form.id;
                this.form.requisition_by = User.id();
                axios.post('requisitions',this.form)
                    .then(res => {                      ;
                        eventBus.$emit('listRequisitions',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`requisitions/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_req = false;
                       eventBus.$emit('updateRequisition',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },          
            listen(){
                if (this.edit){
                    this.form = this.$store.state.requisitions;
                    setTimeout(()=>{
                   this.fetchItems();
                    },1000);                   
                    if (this.form.item_type == 'stock') {
                        this.show_stock = true;
                        this.show_asset = false;                        
                    }
                    if (this.form.item_type == 'asset') {
                        this.show_stock = false;
                        this.show_asset = true;
                    }

                }
            },
        },
        components:{
            datepicker
        }
    }
</script>

<style>

</style>
