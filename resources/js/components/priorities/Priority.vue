<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_priority ? 'Update Priority' : 'New Priority'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="savePriority()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_priority ? 'Update' : 'Save'}}</button>
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
                    id:''
                },
                edit_priority: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            savePriority(){
                this.edit_priority ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('priorities',this.form)
                    .then(res => {
                        eventBus.$emit('listPriorities',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`priorities/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_priority = false;
                        eventBus.$emit('updatePriority',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.priorities
                }
            },
        }
    }
</script>

<style scoped>

</style>
