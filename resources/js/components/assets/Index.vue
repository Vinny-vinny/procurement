<template>
    <div>
        <all-asset v-if="add_machine" :edit="editing"></all-asset>
        <!-- Main content -->
        <section class="content" v-if="!add_machine">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Assets
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_machine=true">Add Asset
                                </v-btn>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="importMachines()">{{importing ? 'Importing...' : 'Import from Sage'}}
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
                                    <v-icon class="outlined" @click="editMachine(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteMachine(item.id)">mdi-delete</v-icon>

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
    import Machines from "./Assets";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_machine: false,
                editing: false,
                importing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.getMachines();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_machines'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getMachines(){
                this.$store.dispatch('my_machines');
            },
            editMachine(machine){
                this.$store.dispatch('updateMachine',machine)
                    .then(() =>{
                        this.editing=true;
                        this.add_machine=true;
                    })
            },
            deleteMachine(id){
                axios.delete(`machines/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            importMachines(){
                this.importing = true;
            axios.get('import-machines')
                .then(machines => {
                    if (machines.data.length){
                        this.$toastr.s('Machines imported successfully.');

                    }
                    this.importing = false;
                    this.$router.go();

                })
            },
            listen(){
                eventBus.$on('listMachines',(machine) =>{
                    this.getItems();
                    this.add_machine =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_machine = false;
                    this.editing = false;
                });
                eventBus.$on('updateMachine',(machine)=>{
                    this.add_machine = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == machine.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(machine);
                });
            },

        },
        components:{
          'all-asset':Machines
        }
    }
</script>

<style>
.mr{
    margin-right: 10px;
}
</style>
