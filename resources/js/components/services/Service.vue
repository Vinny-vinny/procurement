<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_service ? 'Update Service' : 'New Service'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveService()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                          <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" step="0.001" class="form-control" v-model="form.amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_service ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',
                    description:'',
                    amount:'',
                    id:''
                },
                edit_service: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveService(){
                this.edit_service ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('services',this.form)
                    .then(res => {
                        eventBus.$emit('listServices',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`services/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_service = false;
                        eventBus.$emit('updateService',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.services
                }
            },
        }
    }
</script>

<style scoped>

</style>
