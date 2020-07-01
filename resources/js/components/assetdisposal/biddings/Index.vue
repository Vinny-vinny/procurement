<template>
    <div>
        <bidding v-if="show_bid"></bidding>
        <!-- Main content -->
        <section class="content" v-if="!show_bid">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Biddings</h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Bid#</th>
                            <th>Bid Date</th>
                            <th>Disposal#</th>
                            <th>Staff</th>
                            <th>Payment Mode</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="bid in tableData">
                            <td>{{bid.bid_no}}</td>
                            <td>{{bid.bid_date}}</td>
                            <td>{{bid.disposal_no}}</td>
                            <td>{{bid.user}}</td>
                            <td>{{bid.payment_mode}}</td>
                            <td>
                             <button class="btn btn-info btn-sm" @click="editDisposal(bid)"><i class="fa fa-eye"></i></button>
                             <button class="btn btn-success btn-sm" @click="disposalForm(bid)"><i class="fa fa-eye"> Disposal Form</i></button>
                                
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

    import Bidding from "./Bidding";
    export default {
        data(){
            return {
                tableData: [],
                show_bid: false,
                disposal_form:false        
            }
        },
        created(){
            this.listen();
            this.getBiddings();
        },       
        methods:{
            getBiddings(){
                axios.get('bidding')
                    .then(res => {
                        this.tableData = res.data.biddings
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editDisposal(bid){
                this.$store.dispatch('updateBid',bid)
                    .then(() =>{                       
                        this.show_bid=true;
                    })
            },    
            disposalForm(bid){
             this.$store.dispatch('updateBid',bid)
                    .then(() =>{                       
                        this.disposal_form=true;
                    })
            },      
            listen(){
                eventBus.$on('listBid',(bid) =>{
                    this.tableData.unshift(bid);
                    this.show_bid =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.show_bid = false;
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
            Bidding
        }
    }
</script>

<style scoped>

</style>
