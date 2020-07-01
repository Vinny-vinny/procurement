<template>
    <div>
        <requisition v-if="add_req" :edit="editing"></requisition>
        <!-- Main content -->
        <section class="content" v-if="!add_req">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Requisitions
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_req=true">Add Requisition
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
                                    <v-icon class="outlined" @click="editReq(item)">mdi-pencil</v-icon>


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
    import Requisition from "./Requisition";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";

    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_req: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.getRqs();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_requisitions'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getRqs(){
                this.$store.dispatch('my_requisitions');
            },
            editReq(rq){
                this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        this.editing=true;
                        this.add_req=true;
                    })
            },
            deleteReq(id){
                axios.delete(`requisitions/${id}`)
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
                eventBus.$on('listRequisitions',(rq) =>{
                    this.getItems();
                    this.add_req =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_req = false;
                    this.editing = false;
                });
                eventBus.$on('updateRequisition',(rq)=>{
                    this.add_req = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == rq.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(rq);
                });
            },

        },
        components:{
            Requisition
        }
    }
</script>

<style scoped>

</style>
