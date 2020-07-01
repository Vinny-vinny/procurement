<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_department ? 'Update Department' : 'New Department'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveDepartment()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_department ? 'Update' : 'Save'}}</button>
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
                    name:'',
                    description:'',
                    id:''
                },
                edit_department: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveDepartment(){
                this.edit_department ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('departments',this.form)
                    .then(res => {
                        this.$store.state.departments.all_my_departments.unshift(res.data);
                        eventBus.$emit('listDepartments',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`departments/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_department = false;
                        eventBus.$emit('updateDepartment',res.data);
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
                    this.form = this.$store.state.departments.department
                }
            },
        },
        components:{
            datepicker
        }
    }
</script>

<style>
    .vdp-datepicker input {
        border-radius: 0;
        box-shadow: none;
        border-color: #d2d6de;
        display: block;
        width: 100%;
        height: calc(2.25rem + 2px);
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }
</style>
