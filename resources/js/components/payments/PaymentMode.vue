<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_mode ? 'Update Payment Mode' : 'New Payment Mode'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveMode()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_mode ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import payment_modes from "../../store/modules/payment_modes";

    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',
                    description:'',
                    id:''
                },
                edit_mode: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveMode(){
                this.edit_mode ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('payment-modes',this.form)
                    .then(res => {
                        this.$store.state.payment_modes.all_my_modes.unshift(res.data);
                        eventBus.$emit('listMode',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`payment-modes/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_mode = false;
                        eventBus.$emit('updateMode',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.payment_modes.mode
                }
            },
        }
    }
</script>

<style scoped>

</style>
