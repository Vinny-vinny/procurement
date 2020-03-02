<template>
    <div>
        <payment-mode v-if="add_mode" :edit="editing"></payment-mode>
        <!-- Main content -->
        <section class="content" v-if="!add_mode">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Payment Modes</h3>
                    <button class="btn btn-primary pull-right" @click="add_mode=true">Add Payment Mode</button>
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
                        <tr v-for="mode in tableData">
                            <td>{{mode.id}}</td>
                            <td>{{mode.name}}</td>
                            <td>{{mode.description}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editMode(mode)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteMode(mode.id)"><i class="fa fa-trash"></i></button>
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

    import PaymentMode from "./PaymentMode";
    export default {
        data(){
            return {
                tableData: [],
                add_mode: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getModes();
        },       
        methods:{
            getModes(){
                axios.get('payment-modes')
                    .then(res => {
                        this.tableData = res.data
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editMode(mode){
                this.$store.dispatch('updateMode',mode)
                    .then(() =>{
                        this.editing=true;
                        this.add_mode=true;
                    })
            },
            deleteMode(id){
                axios.delete(`payment-modes/${id}`)
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
                eventBus.$on('listMode',(mode) =>{
                    this.tableData.unshift(mode);
                    this.add_mode =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_mode = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateMode',(mode)=>{
                    this.add_mode = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == mode.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(mode);
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
            PaymentMode
        }
    }
</script>

<style scoped>

</style>
