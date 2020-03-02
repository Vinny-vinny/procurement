<template>
    <div>
        <award v-if="show_award"></award>
        <!-- Main content -->
        <section class="content" v-if="!show_award">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bid Awards</h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Ref#</th>
                            <th>Created At</th>
                            <th>Disposal#</th>                           
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="award in tableData">
                            <td>{{award.ref_no}}</td>
                            <td>{{award.created}}</td>
                            <td>{{award.disposal_no}}</td>                      
                            <td>
                             <button class="btn btn-info btn-sm" @click="editDisposal(award)"><i class="fa fa-eye"></i></button>
                                
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>

    import Award from "./Award";
    export default {
        data(){
            return {
                tableData: [],
                show_award: false        
            }
        },
        created(){
            this.listen();
            this.getAwards();
        },       
        methods:{
            getAwards(){
                axios.get('bid-award')
                    .then(res => {
                        this.tableData = res.data.all_awards
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
            },
            editDisposal(award){
                this.$store.dispatch('updateAward',award)
                    .then(() =>{                       
                        this.show_award=true;
                    })
            },          
            listen(){
                eventBus.$on('listAward',(award) =>{
                    this.tableData.unshift(award);
                    this.show_award =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.show_award = false;
                    this.initDatable();
                });
               },
            initDatable(){
                setTimeout(()=>{
                    $('.dt').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        order: [[ 0, 'asc' ], [ 3, 'desc' ]],
                        responsive: true,
                        destroy: true,
                        retrieve:true,
                        autoFill: true,
                        colReorder: true,

                    });
                },1000)
            },
        },
        components:{
            Award
        }
    }
</script>

<style scoped>

</style>
