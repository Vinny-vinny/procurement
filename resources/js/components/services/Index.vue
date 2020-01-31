<template>
    <div>
        <service v-if="add_service" :edit="editing"></service>
        <!-- Main content -->
        <section class="content" v-if="!add_service">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Services</h3>
                    <button class="btn btn-primary pull-right" @click="add_service=true">Add Service</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="service in tableData">
                            <td>{{service.id}}</td>
                            <td>{{service.name}}</td>
                            <td>{{service.description}}</td>
                            <td>{{service.amount | number}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editService(service)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteService(service.id)"><i class="fa fa-trash"></i></button>
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

    import Service from "./Service";
    export default {
        data(){
            return {
                tableData: [],
                add_service: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getServices();
        },       
        methods:{
            getServices(){
                axios.get('services')
                    .then(res => {
                        this.tableData = res.data
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editService(service){
                this.$store.dispatch('updateService',service)
                    .then(() =>{
                        this.editing=true;
                        this.add_service=true;
                    })
            },
            deleteService(id){
                axios.delete(`services/${id}`)
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
                eventBus.$on('listServices',(service) =>{
                    this.tableData.unshift(service);
                    this.add_service =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_service = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateService',(service)=>{
                    this.add_service = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == service.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(service);
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
            Service
        }
    }
</script>

<style scoped>

</style>
