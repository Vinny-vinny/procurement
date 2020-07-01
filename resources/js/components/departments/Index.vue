<template>
    <div>
        <department v-if="add_department" :edit="editing"></department>
        <!-- Main content -->
        <section class="content" v-if="!add_department">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Departments
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_department=true">Add Department
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
                                    <v-icon class="outlined" @click="editDepartment(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteDepartment(item.id)">mdi-delete</v-icon>

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
    import Department from "./Department";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_department: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getDepartments();
        },
        computed:{
            ...mapGetters({
                tableData:'all_departments'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getDepartments(){
                this.$store.dispatch('my_departments');
            },
            editDepartment(dept){
                this.$store.dispatch('updateDepartment',dept)
                    .then(() =>{
                        this.editing=true;
                        this.add_department=true;
                    })
            },
            deleteDepartment(id){
                axios.delete(`departments/${id}`)
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
                eventBus.$on('listDepartments',(dept) =>{
                    this.getItems();
                    this.add_department =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_department = false;
                    this.editing = false;
                });
                eventBus.$on('updateDepartment',(dept)=>{
                    this.add_department = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == dept.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(dept);
                });
            },

        },
        components:{
            Department
        }
    }
</script>

<style scoped>

</style>
