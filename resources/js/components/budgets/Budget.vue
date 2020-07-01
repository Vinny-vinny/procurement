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
                        <div class="row">
                            <div class="col-md-12">
                            <div class="form-group">
                            <label>Department</label>
                            <select class="form-control" v-model="form.department_id" required @change="departmentDetails()" :disabled="edit">
                                <option :value="department.id" v-for="department in unbudgeted_departments" :key="department.id">{{department.name}}</option>
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
                                                 <option value="service">Service</option>
                                            </select>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" v-if="show_stock">
                                   <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Stock Items</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Amount</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">
                                        <td>
                                         <model-select :options="stocks"
                                        v-model="m.item_id"
                                        @input="stock_item = m.item_id"
                                        class="item"
                                        >
                                        </model-select>
                                        </td>

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
                                    </fieldset>
                                </div>
                                <div class="form-group" v-if="show_asset">
                                    <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Assets</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Amount</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.item_asset">
                                            <td>
                                        <model-select :options="assetss"
                                        v-model="m.item_id"
                                        @input="stock_item = m.item_id"
                                        class="item"
                                        >
                                        </model-select>
                                        </td>
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
                                </fieldset>
                                </div>

                                 <div class="form-group" v-if="show_service">
                                    <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Services</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.item_service">
                                        <td>
                                        <model-select :options="servicess"
                                        v-model="m.item_id"
                                        @input="service_item = m.item_id"
                                        class="item"
                                        >
                                        </model-select>
                                        </td>
                                            <td>
                                                <input type="text" class="form-control cost" v-model="m.description"
                                                       placeholder="Description" disabled></td>

                                                        <td><input type="number" class="form-control cost" v-model="m.amount"
                                                       placeholder="Amount" disabled></td>
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeService(i)"
                                                   v-show="i || (!i && form.item_service.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addService(i)"
                                                   v-show="i == form.item_service.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
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
    import { ModelSelect } from 'vue-search-select';
    import {mapGetters} from 'vuex';
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
                    ending_date:'',
                    item_stock: [{item_id: '',amount: ''}],
                    item_asset: [{item_id: '',amount: ''}],
                    item_service: [{item_id: '',name:'',description:'',amount: ''}],
                    id:''
                },
                edit_budget: this.edit,
                show_stock:false,
                show_asset:false,
                show_service:false,
                budget_amount:'',
                stock_item:'',
                service_item:'',
                stocks:[],
                assetss:[],
                servicess:[],
                unbudgeted_departments:[],
                b_a:0

            }
        },
        created(){
            this.getDetails();
            this.listen();
        },
        watch:{
            departments(){
              return this.departments;
            },
            assets(){
                this.assets.forEach(a => {
                    this.assetss.push({
                        'value': a.id,
                        'text': a.code +'-'+a.description
                    })
                })
              return this.assets;
            },
            item_types(){
                return this.item_types;
            },
            stock_items(){
                this.stock_items.forEach(s => {
                    this.stocks.push({
                        'value': s.id,
                        'text': s.code +'-'+s.description
                    })
                })
                return this.stock_items;
            },
            services(){
                this.services.forEach(s => {
                    this.servicess.push({
                        'value': s.id,
                        'text': s.name
                    })
                })
                return this.services;
            },
            budgets(){
                return this.budgets;
            },
            filtered_departments(){
                return this.filtered_departments;
            },
            service_items(){
             for(let i=0;i<this.services.length;i++){
               if (Object.values(this.form.item_service[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_service.length; j++) {
                        if (this.form.item_service[j]['item_id'] == this.services[i]['id']) {
                            this.form.item_service[j]['name'] = this.services[i]['name']
                            this.form.item_service[j]['description'] = this.services[i]['description']
                            this.form.item_service[j]['amount'] = this.services[i]['amount']
                        }
                    }
                }
            }

            },

            start_end(){
             var aYearFromNow = new Date(this.form.begins_on);
             aYearFromNow.setFullYear(aYearFromNow.getFullYear() + 1);
             this.form.ends_on = aYearFromNow;
              var dateFrom = moment(this.form.begins_on).format('DD/MM/YYYY');
              var dateTo = moment(this.form.ends_on).format('DD/MM/YYYY');;
              let departments = [];
              let departments_obj = {};
              let dpts;

             if (this.filtered_departments.length) {
              this.filtered_departments.forEach(b => {
               this.departments.forEach(d => {
              if (d.id == b.department_id) {
              var dateCheck = moment(d.begins_on).format('DD/MM/YYYY');
              var d1 = dateFrom.split("/");
              var d2 = dateTo.split("/");
              var c = dateCheck.split("/");

              var from = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
              var to   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
              var check = new Date(c[2], parseInt(c[1])-1, c[0]);
                    if(check > from && check < to){
                        if (!departments_obj[d.id]) {
                            departments_obj[d.id] = d;
                        }
                    }

                }
               })
             })

            this.unbudgeted_departments = [];
            dpts = Object.values(departments_obj);

               let my_departments = dpts.map(obj =>{
                      return obj.id;
                    })

            for(let n=0;n<this.departments.length;n++){
                if (!my_departments.includes(this.departments[n]['id'])) {
                    this.unbudgeted_departments.push(this.departments[n]);
                }
            }
             }

             else {
                  this.unbudgeted_departments =  this.departments;
                  }

           },
       budgeting(){
       let total =0;
       if (Object.values(this.form.item_stock[0])[1] !== '' && Object.values(this.form.item_stock[0])[1] !== null && Object.values(this.form.item_stock[0])[1] !== null) {
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
          if (Object.values(this.form.item_service[0])[1] !== '' && Object.values(this.form.item_service[0])[1] !== null) {
            for(let k=0;k<this.form.item_service.length;k++){
            if (this.form.item_service[k]['item_id'] !=='' && this.form.item_service[k]['amount'] !=='') {
                total+=parseFloat(this.form.item_service[k]['amount']);
            }
         }
         }

        this.form.total_amount = total;
        }
    },
        computed:{
         ...mapGetters({
             departments:'all_departments',
             assets:'all_machines',
             item_types:'all_item_types',
             stock_items:'all_parts',
             services:'all_services',
             budgets:'all_budgets',
             filtered_departments:'all_filtered_budgets'

         }) ,
        budgeting(){
            return [this.stock_item,this.budget_amount,this.form.item_stock,this.form.item_asset,this.form.item_service,this.service_item].join();
        },
        service_items(){
            return [this.service_item,this.form.item_service].join();
        },
        start_end(){
            return [this.form.begins_on].join();
        },

        },
        methods:{
             departmentDetails(){
             if (this.form.begins_on =='') {
                this.form.department_id = '';
                return this.$toastr.e('Please Enter Financial Year first.');
             }
             },
            itemType(){
            if (this.form.item_type =='asset') {
                this.show_asset=true;
                this.show_stock=false;
                this.show_service = false;
            }  else if(this.form.item_type =='stock') {
               this.show_asset=false;
                this.show_stock=true;
                 this.show_service = false;
            }
             else if(this.form.item_type =='service') {
               this.show_asset=false;
               this.show_stock=false;
               this.show_service = true;
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
             addService(i){
            this.form.item_service.push({item_id: '',name:'',description:'',amount: ''});

            },
            removeService(i){
            this.form.item_service.splice(i,1);
            },
            getDetails(){
              this.$store.dispatch('my_departments');
              this.$store.dispatch('my_machines');
              this.$store.dispatch('my_parts');
              this.$store.dispatch('my_budgets');
              this.$store.dispatch('my_item_types');
              this.$store.dispatch('my_services');
            },
              saveBudget(){
                let service_obj = {};
                let stk_obj = {};
                let asset_obj = {};
                  if (Object.values(this.form.item_stock[0])[0] !== '' || Object.values(this.form.item_stock[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_stock.length; i++) {
                        if (this.form.item_stock[i]['item_id'] === '' || this.form.item_stock[i]['amount'] === '' ) {
                            return this.$toastr.e('Please all Stock Items fields are required.');
                        }
                    }

                 for(let i=0;i<this.form.item_stock.length;i++){
                if(!stk_obj[this.form.item_stock[i]['item_id']]){
                    stk_obj[this.form.item_stock[i]['item_id']] = this.form.item_stock[i];
                }
                else if(stk_obj[this.form.item_stock[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${this.stock_items.find(s => s.id ==stk_obj[this.form.item_stock[i]['item_id']]['item_id']).code} twice,Please check before proceeding.`);
                }
            }
                }
                if (Object.values(this.form.item_asset[0])[0] !== '' || Object.values(this.form.item_asset[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_asset.length; i++) {
                        if (this.form.item_asset[i]['item_id'] === '' || this.form.item_asset[i]['amount'] === '' ) {
                            return this.$toastr.e('Please all Assets fields are required.');
                        }
                    }

                 for(let i=0;i<this.form.item_asset.length;i++){
                if(!asset_obj[this.form.item_asset[i]['item_id']]){
                    asset_obj[this.form.item_asset[i]['item_id']] = this.form.item_asset[i];
                }
                else if(asset_obj[this.form.item_asset[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${this.assets.find(a => a.id ==asset_obj[this.form.item_asset[i]['item_id']]['item_id']).code} twice,Please check before proceeding.`);
                }
            }

           }

            if (Object.values(this.form.item_service[0])[0] !== '' || Object.values(this.form.item_service[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_service.length; i++) {
                        if (this.form.item_service[i]['item_id'] === '' || this.form.item_service[i]['amount'] === '' ) {
                            return this.$toastr.e('Please all Services fields are required.');
                        }
                    }

                 for(let i=0;i<this.form.item_service.length;i++){
                if(!service_obj[this.form.item_service[i]['item_id']]){
                    service_obj[this.form.item_service[i]['item_id']] = this.form.item_service[i];
                }
                else if(service_obj[this.form.item_service[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${service_obj[this.form.item_service[i]['item_id']]['name']} twice,Please check before proceeding.`);
                }

            }

           }

               this.form.begins_on = DateConverter.convertDate(this.form.begins_on);
               this.form.ending_date = DateConverter.convertDate(this.form.ends_on);

                this.edit_budget ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('department-budget',this.form)
                    .then(res => {
                        this.$store.state.budgets.all_my_budgets.unshift(res.data);
                        eventBus.$emit('listBudgets',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`department-budget/${this.form.id}`,this.form)
                    .then(res => {
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
                    this.form = this.$store.state.budgets.budget;
                     //this.unbudgeted_departments = this.departments;
                     this.itemType();


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
