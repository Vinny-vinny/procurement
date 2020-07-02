<template>
    <div>
        <disposal v-if="add_disposal" :edit="editing"></disposal>
        <disposal-memo v-if="show_memo"></disposal-memo>
        <!-- Main content -->
        <section class="content" v-if="!add_disposal && !show_memo">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Asset Disposal
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_disposal=true">Add New
                                </v-btn>
                            </v-card-title>
                            <v-data-table
                                v-model="selected"
                                :headers="headers"
                                :items="items"
                                :single-select="singleSelect"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"
                                :search="search"
                                item-key="name"
                                class="elevation-1"
                                :footer-props="{
                              showFirstLastPage: true,
                              firstIcon: 'mdi-arrow-collapse-left',
                              lastIcon: 'mdi-arrow-collapse-right',
                              prevIcon: 'mdi-minus',
                              nextIcon: 'mdi-plus'
                              }"
                            >
                                <template v-slot:item.actions="{ item }">
                                    <router-link :to="'/bid-award/'+item.id" class="btn btn-success btn-sm" v-if="checkBid(item.id)"><i class="fa fa-trophy"></i></router-link>
                                    <v-icon class="outlined"  @click="showMemo(item)"><i class="fa fa-eye"></i>Memo</v-icon>
                                </template>
                            </v-data-table>
                            <center>
                                <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
                            </center>
                        </v-card>
                    </v-app>
                </div>
            </div>
        </section>
    </div>
</template>
<script>

    import Disposal from "./AssetDisposal";
    import DisposalMemo from "./disposalmemo/DisposalMemo";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_disposal: false,
                editing: false,
                show_memo:false,
                headers: FieldDefs
            }
        },
        created(){
            this.getDisposals();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_disposals',
                bids:'all_bids'
            })
        },
        watch:{
            tableData(){
             this.getItems();
            },
            bids(){
            return this.bids;
            }
        },
        methods:{
           checkBid(asset_id){
               console.log(`lll -> ${this.bids.length}`)
           let item = this.tableData.find(asset => asset.id == asset_id);
           if (this.bids.length !==undefined && this.bids.length > 0){
               alert('ooo')
               let bids = this.bids.filter(bid => bid.disposal_id == asset_id);
               return item.status == 1;
           }
           return  false;
        },
            getDisposals(){
              this.$store.dispatch('my_disposals');
              this.$store.dispatch('my_bids');
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
                    console.log('walla')
                    this.getItems();
                    this.add_disposal =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_disposal = false;
                    this.editing = false;
                    this.show_memo = false;
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
                });
            }
        },
        components:{
            Disposal,
            DisposalMemo
        }
    }
</script>

<style scoped>

</style>
