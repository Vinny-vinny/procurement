<template>
    <div>
        <service v-if="add_service" :edit="editing"></service>
        <!-- Main content -->
        <section class="content" v-if="!add_service">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Services
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_service=true">Add Service
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
                                    <v-icon class="outlined" @click="editService(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteService(item.id)">mdi-delete</v-icon>

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

    import Service from "./Service";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_service: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getServices();
        },
        computed:{
            ...mapGetters({
                tableData:'all_services'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getServices(){
                this.$store.dispatch('my_services');
            },
            editService(service){
                this.$store.dispatch('updateService',service)
                    .then(() =>{
                        this.editing=true;
                        this.add_service=true;
                    })
            },
            deleteService(id){
                axios.delete(`services/${id}`)
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
                eventBus.$on('listServices',(service) =>{
                    this.getItems();
                    this.add_service =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_service = false;
                    this.editing = false;
                });
                eventBus.$on('updateService',(service)=>{
                    this.add_service = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == service.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(service);

                });
            },
        },
        components:{
            Service
        }
    }
</script>

<style scoped>

</style>
