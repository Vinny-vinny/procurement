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
                                    <select v-model="form.department_id" class="form-control" required @change="checkItems()" :disabled="edit">
                                         <option selected disabled>Select Department</option>
                                        <option :value="department.id" v-for="department in unbudgeted_departments" :key="department.id">{{department.name}}</option>
                                    </select>
                                </div>
                            <div class="form-group">
                                    <label>Item Type</label>
                                    <select v-model="form.item_type" required class="form-control">
                                       <option value="stock">Stock Item</option>
                                       <option value="asset">Asset</option>
                                        <option value="service">Service</option>
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
                                      <model-select :options="projects"
                                        v-model="form.project_id"
                                        >
                                        </model-select>
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
                                    <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Stock Items</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Uom</th>
                                            <th>Scheduled Date</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.item_stock">
                                         <td>
                                         <model-select :options="filtered_stock_items"
                                        v-model="m.item_id"
                                        class="i_p"
                                        >
                                        </model-select>
                                        </td>

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
                                       </fieldset>
                                </div>
                                <div class="form-group" v-if="show_asset">
                                    <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Assets</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Uom</th>
                                            <th>Scheduled Date</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.item_asset">
                                            <td>
                                         <model-select :options="filtered_assets"
                                        v-model="m.item_id"
                                        class="i_p">
                                        </model-select>
                                        </td>
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
                                            <th>Scheduled Date</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.item_service">
                                            <td>
                                         <model-select :options="filtered_services"
                                        v-model="m.item_id"
                                        @input="service_item = m.item_id"
                                        class="i_p"
                                        >
                                        </model-select>
                                        </td>
                                        <td>
                                         <input type="text" class="form-control cost" v-model="m.description"
                                                       placeholder="Description" disabled></td>

                                         <td><input type="number" class="form-control cost" v-model="m.amount"
                                                       placeholder="Amount" disabled></td>
                                         <td><datepicker v-model="m.scheduled_date" placeholder="Scheduled Date"></datepicker></td>
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
    import { ModelSelect } from 'vue-search-select';
    import {mapGetters} from 'vuex';
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
                    item_service: [{item_id: '',description: '',amount:'',scheduled_date:''}],
                    id:''
                },
                edit_req: this.edit,
                projects:[],
                show_stock:false,
                show_asset:false,
                show_service:false,
                filtered_stock_items:[],
                filtered_assets:[],
                filtered_services:[],
                service_item:'',
                unbudgeted_departments:[]
            }
        },
        created(){
            this.getDetails();
            this.listen();
        },
        watch:{
            req_types(){
             return this.req_types;
            },
            departments(){
             return this.departments;
            },
            priorities(){
                return this.priorities;
            },
            stock_items(){
             return this.stock_items;
            },
            assets(){
                return this.assets;
            },
            budgets(){
                return this.budgets;
            },
            uoms(){
                return this.uoms;
            },
            services(){
                return this.services;
            },
            filtered_departments(){

                let departments = [];
                let departments_obj = {};

                if (this.filtered_departments.length) {
                    this.filtered_departments.forEach(b => {
                        this.departments.forEach(d => {
                            if (d.id == b.department_id) {

                                var dateFrom = '01/01/'+moment(Date.now()).format('Y')+'/01/01';
                                var dateTo = '01/01/'+(parseInt(moment(Date.now()).format('Y'))+1);
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

                    this.unbudgeted_departments = Object.values(departments_obj);

                    if(this.filtered_requistions.length){
                        let my_depts = this.filtered_requistions.map(d => {
                            return d.department_id;
                        })
                        const all_dpts = Object.values(departments_obj);
                        let wanted = [];

                        for(let p=0;p<all_dpts.length;p++){
                            if (!my_depts.includes(all_dpts[p]['id'])) {
                                wanted.push(all_dpts[p]);
                            }
                        }
                        this.unbudgeted_departments = wanted;
                    }

                }

                else {
                    this.unbudgeted_departments =  this.departments;
                }
                return this.filtered_departments;
            },
            filtered_requistions(){
                return this.filtered_requistions;
            },
            all_projects(){
                this.all_projects.forEach(p => {
                    this.projects.push({
                        'value': p.id,
                        'text': `${p.code}-${p.description}`
                    })
                })

            },
             service_items(){
              for(let i=0;i<this.services.length;i++){
               if (Object.values(this.form.item_service[0])[0] !== '') {
                    for (let j = 0; j < this.form.item_service.length; j++) {
                        if (this.form.item_service[j]['item_id'] == this.services[i]['id']) {
                            this.form.item_service[j]['description'] = this.services[i]['description']
                            this.form.item_service[j]['amount'] = this.services[i]['amount']
                        }
                    }
                }
            }

            },
        fetchItems(){
                let budget_item ={} ;
                let stk_items = {};
                let asset_item = {};
                let service_item = {};
                if (this.form.department_id =='') {
                    this.form.item_type ='';
                    return this.$toastr.e('Please select department first')
                }
          if (this.form.department_id !=='' && this.form.item_type !=='') {
          // setTimeout(()=>{
           if(this.edit) {
            this.unbudgeted_departments = this.departments;
           }
          let budgets = this.budgets.find(b => b.department_id == this.form.department_id);
         if (budgets ==undefined) {
           this.show_asset = false;
           this.show_stock = false;
           this.show_service = false;
          return this.$toastr.e(`The selected department does not have budgeted ${this.form.item_type}.`);
         }
             if (this.form.item_type=='asset') {

                    if(budgets['item_asset'][0]['item_id'] != null){
                        asset_item = budgets['item_asset'];
                    }
                     if (Custom.isEmpty(asset_item)) {
                        this.show_asset = false;
                        this.show_stock = false;
                        this.show_service = false;
                      return this.$toastr.e(`The selected department does not have budgeted Assets.`);
                     }
                this.show_stock = false;
                this.show_asset = true;
                this.show_service = false;
                this.filtered_assets = [];
               for(let i=0;i<asset_item.length;i++){
                for(let j=0;j<this.assets.length;j++){
                    if (asset_item[i]['item_id'] == this.assets[j]['id']) {
                        this.filtered_assets.push({
                            'value': this.assets[j]['id'],
                            'text': this.assets[j]['code'] +'-'+this.assets[j]['description']
                        });
                    }
                }
               }

                  if (this.filtered_assets.length) {
                    if (this.form.item_asset[0]['item_id'] =="") {
                     this.form.item_asset.splice(this.form.item_asset[0],1);
                    }
                    if (this.form.item_asset.length ==0) {

                    this.filtered_assets.forEach((service) =>{
                           this.form.item_asset.push({
                            'item_id': service['value'],
                            'qty': '',
                            'uom': '',
                            'scheduled_date': '',
                            'rate': '',
                            'delivery_date': '',
                            'max_qty': ''

                    })
                })
                }

                }
            }
                 if (this.form.item_type=='stock') {
                 //console.log('stock')
                    if(budgets['item_stock'][0]['item_id'] != null){
                        stk_items = budgets['item_stock'];
                    }
                     if (Custom.isEmpty(stk_items)) {
                        this.show_asset = false;
                        this.show_stock = false;
                        this.show_service = false;
                      return this.$toastr.e(`The selected department does not have budgeted Stock Items.`);
                     }
                this.show_stock = true;
                this.show_asset = false;
                this.show_service = false;
                this.filtered_stock_items = [];
                for(let i=0;i<stk_items.length;i++){
                    for(let j=0;j<this.stock_items.length;j++){
                        if (stk_items[i]['item_id'] == this.stock_items[j]['id']) {
                            this.filtered_stock_items.push({
                                'value': this.stock_items[j]['id'],
                                'text': this.stock_items[j]['code'] +'-'+this.stock_items[j]['description']
                            });
                        }
                    }
                }

                   if (this.filtered_stock_items.length) {
                    if (this.form.item_stock[0]['item_id'] =="") {
                     this.form.item_stock.splice(this.form.item_stock[0],1);
                    }
                    if (this.form.item_stock.length ==0) {

                    this.filtered_stock_items.forEach((stk) =>{
                           this.form.item_stock.push({
                            'item_id': stk['value'],
                            'qty': '',
                            'uom': '',
                            'scheduled_date': '',
                            'rate': '',
                            'delivery_date': '',
                            'max_qty': ''

                    })
                })
                }
                }
                }

                  if (this.form.item_type=='service') {
                    if(budgets['item_service'][0]['item_id'] != null){
                        service_item = budgets['item_service'];
                    }
                     if (Custom.isEmpty(service_item)) {
                        this.show_asset = false;
                        this.show_stock = false;
                        this.show_service = false;
                      return this.$toastr.e(`The selected department does not have budgeted Services.`);
                     }
                this.show_stock = false;
                this.show_asset = false;
                this.show_service = true;
                this.filtered_services = [];
                for(let i=0;i<service_item.length;i++){
                    for(let j=0;j<this.services.length;j++){
                        if (service_item[i]['item_id'] == this.services[j]['id']) {
                            this.filtered_services.push({
                                'value': this.services[j]['id'],
                                'text': this.services[j]['name']
                            });
                        }
                    }
                }

                        if (this.filtered_services.length) {
                        if (this.form.item_service[0]['item_id'] =="") {
                         this.form.item_service.splice(this.form.item_service[0],1);
                        }
                        if (this.form.item_service.length ==0) {

                        this.filtered_services.forEach((service) =>{
                        for(let k=0;k<this.services.length;k++){
                            if (service.value == this.services[k]['id']) {
                                this.form.item_service.push({
                                'item_id': service['value'],
                                'description': service['description'],
                                'amount': service['amount'],
                                'scheduled_date': ''

                        })
                        }
                        }
                    })
                    }
                    }

                }
       //  },1000)

            }
        }
        },
        computed:{
        ...mapGetters({
            req_types:'all_req_types',
            departments:'all_departments',
            priorities:'all_priorities',
            stock_items:'all_parts',
            budgets:'all_budgets',
            uoms:'all_uoms',
            services:'all_services',
            filtered_departments:'all_filtered_budgets',
            filtered_requistions:'all_filtered_requisitions',
            all_projects:'all_projects',
            assets:'all_machines',
        }),
        service_items(){
        return [this.service_item,this.form.item_service].join();
        },
       fetchItems(){
        return [this.form.item_type,this.form.department_id].join();
       }
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
             addService(i){
            this.form.item_service.push({item_id: '',description: '',amount:'',scheduled_date:''});
            },
            removeService(i){
            this.form.item_service.splice(i,1);
            },

            checkItems(){

            },
            getDetails(){
              this.$store.dispatch('my_departments');
              this.$store.dispatch('my_req_types');
              this.$store.dispatch('my_priorities');
              this.$store.dispatch('my_parts');
              this.$store.dispatch('my_machines');
              this.$store.dispatch('my_budgets');
              this.$store.dispatch('my_services');
              this.$store.dispatch('my_requisitions');
              this.$store.dispatch('my_uoms');
              this.$store.dispatch('my_projects');
            },

            saveRequisition(){
                let service_obj = {};
                let stk_obj = {};
                let asset_obj = {};
                if (Object.values(this.form.item_stock[0])[0] !== '' || Object.values(this.form.item_stock[0])[1] !== '' || Object.values(this.form.item_stock[0])[2] !== '' || Object.values(this.form.item_stock[0])[3] !== '') {
                    for (let i = 0; i < this.form.item_stock.length; i++) {
                        if (this.form.item_stock[i]['item_id'] === '' || this.form.item_stock[i]['qty'] === '' || this.form.item_stock[i]['uom'] === '' || this.form.item_stock[i]['scheduled_date'] === '') {
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
                   if (Object.values(this.form.item_asset[0])[0] !== '' || Object.values(this.form.item_asset[0])[1] !== '' || Object.values(this.form.item_asset[0])[2] !== '' || Object.values(this.form.item_asset[0])[3] !== '') {
                    for (let i = 0; i < this.form.item_asset.length; i++) {
                        if (this.form.item_asset[i]['item_id'] === '' || this.form.item_asset[i]['qty'] === '' || this.form.item_asset[i]['uom'] === '' || this.form.item_asset[i]['scheduled_date'] === '') {
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
                  if (Object.values(this.form.item_service[0])[0] !== '' || Object.values(this.form.item_service[0])[1] !== '' || Object.values(this.form.item_service[0])[2] !== '' || Object.values(this.form.item_service[0])[3] !== '') {
                    for (let i = 0; i < this.form.item_service.length; i++) {
                        if (this.form.item_service[i]['item_id'] === '' || this.form.item_service[i]['scheduled_date'] === '') {
                            return this.$toastr.e('Please all Service fields are required.');
                        }
                    }

                for(let i=0;i<this.form.item_service.length;i++){
                if(!service_obj[this.form.item_service[i]['item_id']]){
                    service_obj[this.form.item_service[i]['item_id']] = this.form.item_service[i];
                }
                else if(service_obj[this.form.item_service[i]['item_id']]){
                  return this.$toastr.e(`Sorry, You have entered an item ${service_obj[this.form.item_service[i]['item_id']]['description']} twice,Please check before proceeding.`);
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
                    .then(res => {
                        this.$store.state.requisitions.all_my_requisitions.unshift(res.data);
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
                    this.form = this.$store.state.requisitions.requisition;
               //  setTimeout(()=>{
                   this.unbudgeted_departments = this.departments.find(d => d.id == this.form.department_id);
              //   },1000)
                    if (this.form.item_type == 'stock') {
                        this.show_stock = true;
                        this.show_asset = false;
                        this.show_service = false;
                    }
                    if (this.form.item_type == 'asset') {
                        this.show_stock = false;
                        this.show_asset = true;
                         this.show_service = false;
                    }
                     if (this.form.item_type == 'service') {
                        this.show_stock = false;
                        this.show_asset = false;
                         this.show_service = true;
                    }

                }
            },
        },
        components:{
            datepicker,
            ModelSelect
        }
    }
</script>

<style>
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
.m_b{
    margin-bottom:5px;
}
.i_p{
    width: 250px !important;
    margin-right: -40px !important;
    margin-bottom: 5px;

   }
</style>
