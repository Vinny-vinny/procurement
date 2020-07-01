<template>
    <div>
        <budget v-if="add_budget" :edit="editing"></budget>
        <!-- Main content -->
        <section class="content" v-if="!add_budget">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Department Budgets
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_budget=true">Add Department Budget
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
                                    <v-icon class="outlined" @click="editBudget(item)">mdi-pencil</v-icon>

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
    import Budget from "./Budget";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_budget: false,
                editing: false,
                headers: FieldDefs

            }
        },
        created(){
            this.getBudgets();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_budgets'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getBudgets(){
                this.$store.dispatch('my_budgets');
            },
            editBudget(budget){
                this.$store.dispatch('updateBudget',budget)
                    .then(() =>{
                        this.editing=true;
                        this.add_budget=true;
                    })
            },
            deleteBudget(id){
                axios.delete(`department-budget/${id}`)
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
                eventBus.$on('listBudgets',(budget) =>{
                    this.getItems();
                    this.add_budget =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_budget = false;
                    this.editing = false;
                });
                eventBus.$on('updateBudget',(budget)=>{
                    this.add_budget = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == budget.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(budget);
                });
            },

        },
        components:{
            Budget
        }
    }
</script>

<style scoped>

</style>
