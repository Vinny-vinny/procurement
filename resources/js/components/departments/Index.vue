<template>
    <div>
        <department v-if="add_department" :edit="editing"></department>
        <!-- Main content -->
        <section class="content" v-if="!add_department">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Departments</h3>
                    <button class="btn btn-primary pull-right" @click="add_department=true">Add Department</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="dept in tableData">
                            <td>{{dept.id}}</td>
                            <td>{{dept.name}}</td>
                            <td>{{dept.description}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editDepartment(dept)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteDepartment(dept.id)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import Department from "./Department";
    export default {
        data(){
            return {
                tableData: [],
                add_department: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getDepartments();
        },

        methods:{
            getDepartments(){
                axios.get('departments')
                    .then(res => {
                        this.tableData = res.data
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editDepartment(dept){
                this.$store.dispatch('updateDepartment',dept)
                    .then(() =>{
                        this.editing=true;
                        this.add_department=true;
                        this.initDatable();
                    })
            },
            deleteDepartment(id){
                axios.delete(`departments/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }

                    })
                    .catch(error => Exception.handle(error))
            },
            listen(){
                eventBus.$on('listDepartments',(dept) =>{
                    this.tableData.unshift(dept);
                    this.add_department =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_department = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateDepartment',(dept)=>{
                    this.add_department = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == dept.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(dept);
                    this.initDatable();
                });
            },
            initDatable(){
                setTimeout(()=>{
                    $('.dt').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        order: [[ 0, 'asc' ], [ 3, 'desc' ]],
                        responsive: true,
                        destroy: true,
                        retrieve:true,
                        autoFill: true,
                        colReorder: true,

                    });
                },1000)
            },
        },
        components:{
            Department
        }
    }
</script>

<style scoped>

</style>
