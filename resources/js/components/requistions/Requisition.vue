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
                                    <label>Date</label>
                                    <datepicker v-model="form.req_date" required></datepicker>
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select v-model="form.requisition_type_id" required class="form-control">
                                        <option :value="type.id" v-for="type in req_types" :key="type.id">{{type.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Item Type</label>
                                    <select v-model="form.item_type_id" required class="form-control">
                                        <option :value="item_type.id" v-for="item_type in item_types" :key="item_type.id">{{item_type.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Item</label>
                                    <select v-model="form.item_id" required class="form-control">
                                        <option :value="item.id" v-for="item in items" :key="item.id">{{item.code}} - {{item.description}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Project</label>
                                    <select v-model="form.project_id" required class="form-control">
                                        <option :value="project.id" v-for="project in projects" :key="project.id">{{project.code}} - {{project.code}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department</label>
                                    <select v-model="form.department_id" class="form-control" required>
                                        <option :value="department.id" v-for="department in departments" :key="department.id">{{department.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" v-model="form.is_active" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
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
                    item_type_id:'',
                    item_id:'',
                    project_id:'',
                    department_id:'',
                    is_active:'',
                    priority_id:'',
                    description:'',
                    id:''
                },
                edit_req: this.edit,
                req_types:{},
                departments:{},
                priorities:{},
                projects:{},
                item_types:{},
                items:{}
            }
        },
        created(){
            this.listen();
            this.getRequisitions();
        },
        methods:{
            getRequisitions(){
              axios.get('requisitions')
                .then(res => {
                    this.req_types = res.data.requisition_types,
                    this.departments = res.data.departments,
                    this.priorities = res.data.priorities,
                    this.projects = res.data.projects,
                    this.items = res.data.items,
                    this.item_types = res.data.item_types
                })
            },

            saveRequisition(){
                this.form.req_date = this.convertDate(this.form.req_date);
                this.edit_req ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                this.form.requisition_by = User.id();
                axios.post('requisitions',this.form)
                    .then(res => {
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
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.requisitions
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
