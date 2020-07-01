<template>
    <div>
        <enquiry v-if="add_enquiry" :edit="editing"></enquiry>
        <!-- Main content -->
        <section class="content" v-if="!add_enquiry">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Enquiries
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_enquiry=true">Add Enquiry
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
                                    <v-icon class="outlined" @click="editEnquiry(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteEnquiry(item.id)">mdi-delete</v-icon>

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

    import Enquiry from "./Enquiry";
    import datatable from "../../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_enquiry: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.getEnquiries();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_enquiries'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getEnquiries(){
                this.$store.dispatch('my_enquiries');
            },
            editEnquiry(enquiry){
                this.$store.dispatch('updateEnquiry',enquiry)
                    .then(() =>{
                        this.editing=true;
                        this.add_enquiry=true;
                    })
            },
            deleteEnquiry(id){
                axios.delete(`enquiry/${id}`)
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
                eventBus.$on('listEnquiry',(enquiry) =>{
                    this.getItems();
                    this.add_enquiry =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_enquiry = false;
                    this.editing = false;
                });
                eventBus.$on('updateEnquiry',(enquiry)=>{
                    this.add_enquiry = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == enquiry.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(enquiry);
                });
            },

        },
        components:{
            Enquiry
        }
    }
</script>

<style scoped>

</style>
