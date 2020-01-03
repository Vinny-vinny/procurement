<template>
    <div>
        <quotation v-if="add_quote" :edit="editing"></quotation>
        <!-- Main content -->
        <section class="content" v-if="!add_quote">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Quotations</h3>
                    <button class="btn btn-primary pull-right" @click="add_quote=true">Add Quotaion</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Quotation Date</th>
                            <th>Description</th>
                            <th>Supplier</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="quote in tableData">
                            <td>{{quote.quote_no}}</td>
                            <td>{{quote.quotation_date}}</td>
                            <td>{{quote.description}}</td>
                             <td>{{quote.supplier}}</td
                            <td>
                                <button class="btn btn-success btn-sm" @click="editQuotation(quote)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteQuotation(quote.id)"><i class="fa fa-trash"></i></button>
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

    import Quotation from "./Quotation";
    export default {
        data(){
            return {
                tableData: [],
                add_quote: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getQuotations();
        },       
        methods:{         
            getQuotations(){
                axios.get('quotations')
                    .then(res => {
                        this.tableData = res.data.quotations
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editQuotation(supplier){
                this.$store.dispatch('updateQuotation',supplier)
                    .then(() =>{
                        this.editing=true;
                        this.add_quote=true;
                    })
            },
            deleteQuotation(id){
                axios.delete(`quotations/${id}`)
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
                eventBus.$on('listQuotations',(quotation) =>{
                    this.tableData.unshift(quotation);
                    this.add_quote =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_quote = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateQuotation',(quotation)=>{
                    this.add_quote = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == quotation.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(quotation);
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
            Quotation
        }
    }
</script>

<style scoped>

</style>
