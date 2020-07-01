<template>
    <div>
        <quotation v-if="add_quote" :edit="editing"></quotation>
        <!-- Main content -->
        <section class="content" v-if="!add_quote">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Quotations
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_quote=true">Add Quotation
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
                                    <v-icon class="outlined" @click="editQuotation(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteQuotation(item.id)">mdi-delete</v-icon>

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

    import Quotation from "./Quotation";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_quote: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.getQuotations();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_quotations'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getQuotations(){
             this.$store.dispatch('my_quotations');
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
                    this.getItems();
                    this.add_quote =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_quote = false;
                    this.editing = false;
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
                });
            },

        },
        components:{
            Quotation
        }
    }
</script>

<style scoped>

</style>
