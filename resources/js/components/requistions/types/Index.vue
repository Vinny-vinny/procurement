<template>
    <div>
        <req-type v-if="add_req_type" :edit="editing"></req-type>
        <!-- Main content -->
        <section class="content" v-if="!add_req_type">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Requisition Types
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_req_type=true">Add Requisition Type
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
                                    <v-icon class="outlined" @click="editReqType(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteReqType(item.id)">mdi-delete</v-icon>

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
    import ReqType from "./ReqType";
    import datatable from "../../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_req_type: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getReqTypes();
        },
        computed:{
            ...mapGetters({
                tableData:'all_req_types'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getReqTypes(){
                this.$store.dispatch('my_req_types');
            },
            editReqType(req){
                this.$store.dispatch('updateReqType',req)
                    .then(() =>{
                        this.editing=true;
                        this.add_req_type=true;
                    })
            },
            deleteReqType(id){
                axios.delete(`requisition-types/${id}`)
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
                eventBus.$on('listReqTypes',(rq) =>{
                    this.getItems();
                    this.add_req_type =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_req_type = false;
                    this.editing = false;
                });
                eventBus.$on('updateReqType',(rq)=>{
                    this.add_req_type = false;
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
            ReqType
        }
    }
</script>

<style scoped>

</style>
