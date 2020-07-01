<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Projects
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="importProjects()">{{importing ? 'Importing...' : 'Import from Sage'}}
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
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner"
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
               importing:false,
               headers: FieldDefs
            }
        },
        created(){
            this.getProjects();
        },
        computed:{
            ...mapGetters({
                tableData:'all_projects'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getProjects(){
                this.$store.dispatch('my_projects');
            },
            importProjects(){
                this.importing = true;
                    axios.get(`import-projects`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Projects successfully imported.');
                        this.$router.go();
                    })
            },
        }

    }
</script>

<style scoped>

</style>
