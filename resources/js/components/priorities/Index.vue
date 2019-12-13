<template>
    <div>
        <priority v-if="add_priority" :edit="editing"></priority>
        <!-- Main content -->
        <section class="content" v-if="!add_priority">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Priorities</h3>
                    <button class="btn btn-primary pull-right" @click="add_priority=true">Add Priority</button>
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
                        <tr v-for="prio in tableData">
                            <td>{{prio.id}}</td>
                            <td>{{prio.name}}</td>
                            <td style="display: none">{{prio.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editPriority(prio)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deletePriority(prio.id)"><i class="fa fa-trash"></i></button>
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
    import Priority from "./Priority";
    export default {
        data(){
            return {
                tableData: [],
                add_priority: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getPriorities();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getPriorities(){
                axios.get('priorities')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editPriority(item){
                this.$store.dispatch('updatePriority',item)
                    .then(() =>{
                        this.editing=true;
                        this.add_priority=true;
                    })
            },
            deletePriority(id){
                axios.delete(`priorities/${id}`)
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
                eventBus.$on('listPriorities',(item) =>{
                    this.tableData.unshift(item);
                    this.add_priority =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_priority = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updatePriority',(item)=>{
                    this.add_priority = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == item.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(item);
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
            Priority
        }
    }
</script>

<style scoped>

</style>
