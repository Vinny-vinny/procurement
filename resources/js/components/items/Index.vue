<template>
    <div>
        <items v-if="add_part" :edit="editing"></items>
        <!-- Main content -->
        <section class="content" v-if="!add_part">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Stock Items
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_part=true">Add Stock Item
                                </v-btn>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark  @click="importParts()">{{importing ? 'Importing...':'Import from Sage'}}
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
    import Items from "./Items";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_part: false,
                editing: false,
                importing:false,
                headers: FieldDefs
            }
        },
        created(){
            this.getParts();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_parts'
            })
        },
        watch:{
            tableData(){
            this.getItems();
            }
        },
        methods:{
            importParts(){
                this.importing = true;
                axios.get(`import-parts`)
                    .then(res =>{
                       this.importing = false;
                       this.$toastr.s('Items successfully imported.');
                        this.$router.go();
                    })
            },
            getParts(){
                this.$store.dispatch('my_parts');
            },
            editPart(part){
                this.$store.dispatch('updatePart',part)
                    .then(() =>{
                        this.editing=true;
                        this.add_part=true;
                    })
            },
            deletePart(id){
                axios.delete(`parts/${id}`)
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
                eventBus.$on('listParts',(part) =>{
                    this.getItems();
                    this.add_part =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_part = false;
                    this.editing = false;
                });
                eventBus.$on('updateParts',(part)=>{
                    this.add_part = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == part.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(part);
                });
            },
        },
        components:{
            Items
        }
    }
</script>

<style>
.mr{
    margin-right: 20px;
}
</style>
