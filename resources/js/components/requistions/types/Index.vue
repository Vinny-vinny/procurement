<template>
    <div>
        <req-type v-if="add_req_type" :edit="editing"></req-type>
        <!-- Main content -->
        <section class="content" v-if="!add_req_type">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Requisition Types</h3>
                    <button class="btn btn-primary pull-right" @click="add_req_type=true">Add Requisition Type</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="display: none">Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="rq in tableData">
                            <td>{{rq.id}}</td>
                            <td>{{rq.name}}</td>
                            <td style="display: none">{{rq.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editReqType(rq)"><i class="fa fa-edit"></i></button>
                                 <button class="btn btn-danger btn-sm" @click="deleteReqType(rq.id)"><i class="fa fa-trash"></i></button>
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
    import ReqType from "./ReqType";
    export default {
        data(){
            return {
                tableData: [],
                add_req_type: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getReqTypes();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getReqTypes(){
                axios.get('requisition-types')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editReqType(req){
                this.$store.dispatch('updateReqType',req)
                    .then(() =>{
                        this.editing=true;
                        this.add_req_type=true;
                    })
            },
            deleteReqType(id){
                axios.delete(`requisition-types/${id}`)
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
                eventBus.$on('listReqTypes',(rq) =>{
                    this.tableData.unshift(rq);
                    this.add_req_type =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_req_type = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateReqType',(rq)=>{
                    this.add_req_type = false;
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
            ReqType
        }
    }
</script>

<style scoped>

</style>
