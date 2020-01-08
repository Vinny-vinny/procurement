<template>
    <div>
        <purchase-order v-if="add_po" :edit="editing"></purchase-order>
        <!-- Main content -->
        <section class="content" v-if="!add_po">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Purchase Orders</h3>
                    <button class="btn btn-primary pull-right" @click="add_po=true">Add Purchase Order</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>PO Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="po in tableData">
                            <td>{{po.po_no}}</td>
                            <td>{{po.po_date}}</td>
                            <td>{{po.po_description}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editPo(po)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deletePo(po.id)"><i class="fa fa-trash"></i></button>
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

    import PurchaseOrder from "./PurchaseOrder";
    export default {
        data(){
            return {
                tableData: [],
                add_po: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getPos();
        },       
        methods:{
            getPos(){
                axios.get('purchase-order')
                    .then(res => {
                        this.tableData = res.data.pos
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editPo(po){
                this.$store.dispatch('updatePurchaseOrder',po)
                    .then(() =>{
                        this.editing=true;
                        this.add_po=true;
                    })
            },
            deletePo(id){
                axios.delete(`purchase-order/${id}`)
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
                eventBus.$on('listPurchaseOrders',(po) =>{
                    this.tableData.unshift(po);
                    this.add_po =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_po = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updatePurchaseOrder',(po)=>{
                    this.add_po = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == po.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(po);
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
            PurchaseOrder
        }
    }
</script>

<style scoped>

</style>
