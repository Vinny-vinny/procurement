<template>
    <div>
        <users v-if="add_user" :edit="editing"></users>
        <!-- Main content -->
        <section class="content" v-if="!add_user">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Users
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_user=true">Add User
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
                                    <v-icon class="outlined" @click="editUser(item)">mdi-pencil</v-icon>
                                    <v-icon   class="outlined-trash"  @click="deleteUser(item.id)">mdi-delete</v-icon>

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
    import Users from "./Users";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import spinner from "../../mixins/spinner";
    export default {
        mixins:[datatable,spinner],
        data(){
            return {
                add_user: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getUsers();
        },
        computed:{
            ...mapGetters({
                tableData:'all_users'
            })
        },
        watch:{
            tableData(){
                this.getItems();
            }
        },
        methods:{
            getUsers(){
                this.$store.dispatch('my_users');
            },
            editUser(user){
                this.$store.dispatch('updateUser',user)
                    .then(() =>{
                        this.editing=true;
                        this.add_user=true;
                    })
            },
            deleteUser(id){
                axios.delete(`users/${id}`)
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
                eventBus.$on('listUsers',(user) =>{
                    this.getItems();
                    this.add_user =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_user = false;
                    this.editing = false;
                });
                eventBus.$on('updateUser',(user)=>{
                    this.add_user = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == user.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(user);
                });
            },

        },
        components:{
            Users
        }
    }
</script>

<style scoped>

</style>
