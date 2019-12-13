<template>
    <div>
        <item-type v-if="add_item_type" :edit="editing"></item-type>
        <!-- Main content -->
        <section class="content" v-if="!add_item_type">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Item Types</h3>
                    <button class="btn btn-primary pull-right" @click="add_item_type=true">Add Item Type</button>
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
                        <tr v-for="item in tableData">
                            <td>{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td style="display: none">{{item.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editItemType(item)"><i class="fa fa-edit"></i></button>
                                 <button class="btn btn-danger btn-sm" @click="deleteItemType(item.id)"><i class="fa fa-trash"></i></button>
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
    import ItemType from "./ItemType";
    export default {
        data(){
            return {
                tableData: [],
                add_item_type: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getItemTypes();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getItemTypes(){
                axios.get('item-types')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editItemType(item){
                this.$store.dispatch('updateItemType',item)
                    .then(() =>{
                        this.editing=true;
                        this.add_item_type=true;
                    })
            },
            deleteItemType(id){
                axios.delete(`item-types/${id}`)
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
                eventBus.$on('listItemTypes',(item) =>{
                    this.tableData.unshift(item);
                    this.add_item_type =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_item_type = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateItemType',(item)=>{
                    this.add_item_type = false;
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
           ItemType
        }
    }
</script>

<style scoped>

</style>
