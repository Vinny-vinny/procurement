<template>
    <div>
        <bidding v-if="show_bid"></bidding>
        <!-- Main content -->
        <section class="content" v-if="!show_bid">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Biddings
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
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
                                    <v-icon class="outlined" @click="editDisposal(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="disposalForm(item)">mdi-delete</v-icon>

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

    import Bidding from "./Bidding";
    import datatable from "../../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                show_bid: false,
                disposal_form:false,
                headers: FieldDefs
            }
        },
        created(){
            this.getBiddings();
            this.listen();
        },
        computed:{
            ...mapGetters({
             tableData:'all_bids'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getBiddings(){
                this.$store.dispatch('my_bids');
            },
            editDisposal(bid){
                this.$store.dispatch('updateBid',bid)
                    .then(() =>{
                        this.show_bid=true;
                    })
            },
            disposalForm(bid){
             this.$store.dispatch('updateBid',bid)
                    .then(() =>{
                        this.disposal_form=true;
                    })
            },
            listen(){
                eventBus.$on('listBid',(bid) =>{
                   this.getItems();
                    this.show_bid =false;
                });
                eventBus.$on('cancel',()=>{
                    this.show_bid = false;
                });
               }
        },
        components:{
            Bidding
        }
    }
</script>

<style scoped>

</style>
