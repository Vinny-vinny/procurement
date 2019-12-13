<template>
    <div>
        <requisition v-if="add_req" :edit="editing"></requisition>
        <!-- Main content -->
        <section class="content" v-if="!add_req">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Requisitions</h3>
                    <button class="btn btn-primary pull-right" @click="add_req=true">Add Requisition</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="req in tableData">
                            <td>{{req.req_no}}</td>
                            <td>{{req.date_requested}}</td>
                            <td>{{req.description}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editReq(req)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteReq(req.id)"><i class="fa fa-trash"></i></button>
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
    import Requisition from "./Requisition";
    export default {
        data(){
            return {
                tableData: [],
                add_req: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getRqs();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getRqs(){
                axios.get('requisitions')
                    .then(res => this.tableData = res.data.requisitions)
                    .catch(error => Exception.handle(error))
            },
            editReq(rq){
                this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        this.editing=true;
                        this.add_req=true;
                    })
            },
            deleteReq(id){
                axios.delete(`requisitions/${id}`)
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
                eventBus.$on('listRequisitions',(rq) =>{
                    this.tableData.unshift(rq);
                    this.add_req =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_req = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateRequisition',(rq)=>{
                    this.add_req = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == rq.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(rq);
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
            Requisition
        }
    }
</script>

<style scoped>

</style>
