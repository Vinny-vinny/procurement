<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_item_type ? 'Update Item Type' : 'New Item Type'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveItemType()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_item_type ? 'Update' : 'Save'}}</button>
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
                edit_item_type: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveItemType(){
                this.edit_item_type ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('item-types',this.form)
                    .then(res => {
                        eventBus.$emit('listItemTypes',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`item-types/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_item_type = false;
                        eventBus.$emit('updateItemType',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.item_types
                }
            },
        }
    }
</script>

<style scoped>

</style>
