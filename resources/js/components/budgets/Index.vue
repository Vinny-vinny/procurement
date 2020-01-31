<template>
    <div>
        <budget v-if="add_budget" :edit="editing"></budget>
        <!-- Main content -->
        <section class="content" v-if="!add_budget">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Department Budgets</h3>
                    <button class="btn btn-primary pull-right" @click="add_budget=true">Add Department Budget</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>                          
                            <th>Department</th>
                            <th>Begins On</th>
                            <th>Ends On</th>
                            <th>Budget Amount</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="budget in tableData">
                            <td>{{budget.id}}</td>
                            <td>{{budget.department}}</td>
                            <td>{{budget.start_date_fr}}</td>
                            <td>{{budget.end_date}}</td>
                            <td>{{budget.total_amount | number}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editBudget(budget)"><i class="fa fa-edit"></i></button>
                                <!--<button class="btn btn-danger btn-sm" @click="deleteCategory(category.id)"><i class="fa fa-trash"></i></button>-->
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
    import Budget from "./Budget";
    export default {
        data(){
            return {
                tableData: [],
                add_budget: false,
                editing: false

            }
        },
        created(){
            this.listen();
            this.getBudgets();
        },
        methods:{
            getBudgets(){
                axios.get('department-budget')
                    .then(res =>{
                        this.tableData = res.data.budgets
                        console.log()
                        this.initDatable();
                    })
                    .catch(error => Exception.handle(error))
            },
            editBudget(budget){
                this.$store.dispatch('updateBudget',budget)
                    .then(() =>{
                        this.editing=true;
                        this.add_budget=true;
                    })
            },
            deleteBudget(id){
                axios.delete(`department-budget/${id}`)
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
                eventBus.$on('listBudgets',(budget) =>{
                    this.tableData.unshift(budget);
                    this.add_budget =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_budget = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateBudget',(budget)=>{
                    this.add_budget = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == budget.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(budget);
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
            Budget
        }
    }
</script>

<style scoped>

</style>
