<template>
    <div>
        <disposal v-if="add_disposal" :edit="editing"></disposal>
        <disposal-memo v-if="show_memo"></disposal-memo>
        <!-- Main content -->
        <section class="content" v-if="!add_disposal && !show_memo">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Asset Disposal</h3>
                    <button class="btn btn-primary pull-right" @click="add_disposal=true">Add New</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Ref#</th>
                            <th>Bid Opening Date</th>
                            <th>Bid Deadline Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="asset in tableData">
                            <td>{{asset.disposal_no}}</td>
                            <td>{{asset.date_opened}}</td>
                            <td>{{asset.date_closed}}</td>
                            <td>
                              <router-link :to="'/bid-award/'+asset.id" class="btn btn-success btn-sm" v-if="checkBid(asset.id)"><i class="fa fa-trophy"></i></router-link>                         
                                <button class="btn btn-info btn-sm" @click="showMemo(asset)"><i class="fa fa-eye"></i>Memo</button>
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

    import Disposal from "./AssetDisposal";
    import DisposalMemo from "./disposalmemo/DisposalMemo";
    export default {
        data(){
            return {
                tableData: [],
                add_disposal: false,
                editing: false,
                bids:{},
                show_memo:false
            }
        },
        created(){
            this.listen();
            this.getDisposals();
        },
        computed:{
        
        },    
        methods:{
            checkBid(asset_id){
           let item = this.tableData.find(asset => asset.id == asset_id);  
           let bids = this.bids.filter(bid => bid.disposal_id == asset_id);          
           return item.status == 1 && bids.length;
        },
            getDisposals(){
                axios.get('asset-disposal')
                    .then(res => {
                        this.tableData = res.data.disposals
                        this.bids = res.data.bids
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editDisposal(disposal){
                this.$store.dispatch('updateDisposal',disposal)
                    .then(() =>{
                        this.editing=true;
                        this.add_disposal=true;
                    })
            },
             showMemo(disposal){
                this.$store.dispatch('updateDisposal',disposal)
                    .then(() =>{
                     this.show_memo=true;
                    })
            },
            deleteDisposal(id){
                axios.delete(`asset-disposal/${id}`)
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
                eventBus.$on('listDisposal',(disposal) =>{
                    this.tableData.unshift(disposal);
                    this.add_disposal =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_disposal = false;
                    this.editing = false;
                    this.show_memo = false;
                    this.initDatable();
                });
                eventBus.$on('updateDisposal',(disposal)=>{
                    this.add_disposal = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == disposal.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(disposal);
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
            Disposal,
            DisposalMemo
        }
    }
</script>

<style scoped>

</style>
