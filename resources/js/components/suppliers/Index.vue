<template>
    <div>
        <suppliers v-if="add_supplier" :edit="editing"></suppliers>
        <!-- Main content -->
        <section class="content" v-if="!add_supplier">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                    <v-card>
                        <v-card-title>
                            Suppliers
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
                            <v-spacer></v-spacer>
                            <v-btn small color="indigo" dark @click="add_supplier=true">Add Supplier
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn small color="indigo" dark @click="importSuppliers()">{{importing ? 'Importing...' : 'Import from Sage'}}
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
                                <v-icon class="outlined" @click="editSupplier(item)">mdi-pencil</v-icon>
                                <v-icon   class="outlined-trash"  @click="deleteSupplier(item.id)">mdi-delete</v-icon>

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
    import Suppliers from "./Supplier";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_supplier: false,
                editing: false,
                importing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getSuppliers();
        },
        computed:{
            ...mapGetters({
                tableData:'all_suppliers'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getSuppliers(){
                this.$store.dispatch('my_suppliers');
            },
            editSupplier(supplier){
                this.$store.dispatch('updateSupplier',supplier)
                    .then(() =>{
                        this.editing=true;
                        this.add_supplier=true;
                    })
            },
            deleteSupplier(id){
                axios.delete(`suppliers/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            importSuppliers(){
                 this.importing = true;
                   axios.get('import-suppliers')
                  .then(suppliers => {
                    this.getItems();
                    this.$toastr.s('Suppliers successfully imported.')
                    this.importing=false;
                    this.$router.go();
                  })
            },
            listen(){
                eventBus.$on('listSuppliers',(supplier) =>{
                    this.getItems();
                    this.add_supplier =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_supplier = false;
                    this.editing = false;
                });
                eventBus.$on('updateSupplier',(supplier)=>{
                    this.add_supplier = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == supplier.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(supplier);
                });
            },

        },
        components:{
            Suppliers
        }
    }
</script>

<style>

</style>
