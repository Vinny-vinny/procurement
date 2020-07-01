<template>
    <div>
        <purchase-order v-if="add_po" :edit="editing"></purchase-order>
        <!-- Main content -->
        <section class="content" v-if="!add_po">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Purchase Orders
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_po=true">Add Purchase Order
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
                                    <v-icon class="outlined" @click="editPo(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deletePo(item.id)">mdi-delete</v-icon>

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

    import PurchaseOrder from "./PurchaseOrder";
    import datatable from "../../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_po: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.getPos();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_pos'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getPos(){
                this.$store.dispatch('my_pos');
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
                    this.getItems();
                    this.add_po =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_po = false;
                    this.editing = false;
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
                });
            },

        },
        components:{
            PurchaseOrder
        }
    }
</script>

<style scoped>

</style>
