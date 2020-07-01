<template>
    <div>
        <payment-mode v-if="add_mode" :edit="editing"></payment-mode>
        <!-- Main content -->
        <section class="content" v-if="!add_mode">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Payment Modes
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_mode=true">Add Payment Mode
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
                                    <v-icon class="outlined" @click="editMode(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteMode(item.id)">mdi-delete</v-icon>

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

    import PaymentMode from "./PaymentMode";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_mode: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getModes();
        },
        computed:{
            ...mapGetters({
                tableData:'all_modes'
            })
        },
        watch:{
            tableData(){
             this.getItems();
            }
        },
        methods:{
            getModes(){
             this.$store.dispatch('my_modes');
            },
            editMode(mode){
                this.$store.dispatch('updateMode',mode)
                    .then(() =>{
                        this.editing=true;
                        this.add_mode=true;
                    })
            },
            deleteMode(id){
                axios.delete(`payment-modes/${id}`)
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
                eventBus.$on('listMode',(mode) =>{
                    this.getItems();
                    this.add_mode =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_mode = false;
                    this.editing = false;
                });
                eventBus.$on('updateMode',(mode)=>{
                    this.add_mode = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == mode.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(mode);
                });
            },
        },
        components:{
            PaymentMode
        }
    }
</script>

<style scoped>

</style>
