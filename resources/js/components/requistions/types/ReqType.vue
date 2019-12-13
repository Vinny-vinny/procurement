<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_req_type ? 'Update Requisition Type' : 'New Requisition Type'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveReqType()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_req_type ? 'Update' : 'Save'}}</button>
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
                edit_req_type: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveReqType(){
                this.edit_req_type ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('requisition-types',this.form)
                    .then(res => {
                        eventBus.$emit('listReqTypes',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`requisition-types/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_req_type = false;
                        eventBus.$emit('updateReqType',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.req_types
                }
            },
        }
    }
</script>

<style scoped>

</style>
