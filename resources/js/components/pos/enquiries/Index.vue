<template>
    <div>
        <enquiry v-if="add_enquiry" :edit="editing"></enquiry>
        <!-- Main content -->
        <section class="content" v-if="!add_enquiry">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Enquiries</h3>
                    <button class="btn btn-primary pull-right" @click="add_enquiry=true">Add Enquiry</button>
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
                        <tr v-for="inquiry in tableData">
                            <td>{{inquiry.id}}</td>
                            <td>{{inquiry.name}}</td>
                            <td style="display: none">{{inquiry.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editEnquiry(enquiry)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteEnquiry(enquiry.id)"><i class="fa fa-trash"></i></button>
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

    import Enquiry from "./Enquiry";
    export default {
        data(){
            return {
                tableData: [],
                add_enquiry: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getEnquiries();
        },       
        methods:{
            getEnquiries(){
                axios.get('enquiry')
                    .then(res => {
                        this.tableData = res.data
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editEnquiry(enquiry){
                this.$store.dispatch('updateEnquiry',enquiry)
                    .then(() =>{
                        this.editing=true;
                        this.add_enquiry=true;
                    })
            },
            deleteEnquiry(id){
                axios.delete(`enquiry/${id}`)
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
                eventBus.$on('listEnquiry',(enquiry) =>{
                    this.tableData.unshift(enquiry);
                    this.add_enquiry =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_enquiry = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateEnquiry',(enquiry)=>{
                    this.add_enquiry = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == enquiry.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(enquiry);
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
            Enquiry
        }
    }
</script>

<style scoped>

</style>
