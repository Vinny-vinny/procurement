<template>
    <div>
        <award v-if="show_award"></award>
        <!-- Main content -->
        <section class="content" v-if="!show_award">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Bid Awards
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_disposal=true">Add New
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
                                    <v-icon class="outlined"  @click="editDisposal(award)"><i class="fa fa-eye"></i>Memo</v-icon>
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

    import Award from "./Award";
    import {mapGetters} from "vuex";
    import datatable from "../../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../../mixins/spinner";

    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                show_award: false,
                headers: FieldDefs
            }
        },
        created(){
            this.getAwards();
            this.listen();
        },
        computed:{
            ...mapGetters({
                tableData:'all_awards'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getAwards(){
              this.$store.dispatch('my_awards');
            },
            editDisposal(award){
                this.$store.dispatch('updateAward',award)
                    .then(() =>{
                        this.show_award=true;
                    })
            },
            listen(){
                eventBus.$on('listAward',(award) =>{
                   this.getItems();
                    this.show_award =false;
                });
                eventBus.$on('cancel',()=>{
                    this.show_award = false;
                });
               }
        },
        components:{
            Award
        }
    }
</script>

<style scoped>

</style>
