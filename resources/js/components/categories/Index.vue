<template>
    <div>
        <category v-if="add_category" :edit="editing"></category>
        <!-- Main content -->
        <section class="content" v-if="!add_category">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Categories
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_category=true">Add Category
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
                                    <v-icon class="outlined" @click="editCategory(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteCategory(item.id)">mdi-delete</v-icon>

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

    import Category from "./Category";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_category: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getCategories();
        },
        computed:{
            ...mapGetters({
                tableData:'all_categories'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getCategories(){
             this.$store.dispatch('my_categories');
            },
            editCategory(category){
                this.$store.dispatch('updateCategory',category)
                    .then(() =>{
                        this.editing=true;
                        this.add_category=true;
                    })
            },
            deleteCategory(id){
                axios.delete(`categories/${id}`)
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
                eventBus.$on('listCategory',(category) =>{
                    this.getItems();
                    this.add_category =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_category = false;
                    this.editing = false;
                });
                eventBus.$on('updateCategory',(category)=>{
                    this.add_category = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == category.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(category);
                });
            },

        },
        components:{
            Category
        }
    }
</script>

<style scoped>

</style>
