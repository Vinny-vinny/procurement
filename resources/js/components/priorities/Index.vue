<template>
    <div>
        <priority v-if="add_priority" :edit="editing"></priority>
        <!-- Main content -->
        <section class="content" v-if="!add_priority">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Priorities
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_priority=true">Add Priority
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
                                    <v-icon class="outlined" @click="editPriority(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deletePriority(item.id)">mdi-delete</v-icon>

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
    import Priority from "./Priority";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_priority: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getPriorities();
        },
        computed:{
            ...mapGetters({
                tableData:'all_priorities'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getPriorities(){
                this.$store.dispatch('my_priorities');
            },
            editPriority(item){
                this.$store.dispatch('updatePriority',item)
                    .then(() =>{
                        this.editing=true;
                        this.add_priority=true;
                    })
            },
            deletePriority(id){
                axios.delete(`priorities/${id}`)
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
                eventBus.$on('listPriorities',(item) =>{
                    this.getItems();
                    this.add_priority =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_priority = false;
                    this.editing = false;
                });
                eventBus.$on('updatePriority',(item)=>{
                    this.add_priority = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == item.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(item);
                });
            },

        },
        components:{
            Priority
        }
    }
</script>

<style scoped>

</style>
