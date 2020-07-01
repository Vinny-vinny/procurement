<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_machine ? 'Update Asset' : 'New Asset'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveMachine()" id="asset">

                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" class="form-control" v-model="form.code" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" v-model="form.description" required>
                                </div>

                        <button type="submit" class="btn btn-primary">{{edit_machine ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        props: ['edit'],
        data() {
            return {
                form: {
                    code: '',
                    description: '',
                    id: ''
                },
                edit_machine:this.edit
            }
        },

            created() {
            this.listen();
        },
        methods: {
            saveMachine() {
                this.edit_machine ? this.update() : this.save();
            },
            save() {
                delete this.form.id;
                axios.post('machines', this.form)
                    .then(res => {
                        this.$store.state.assets.all_my_machines.unshift(res.data);
                        this.$toastr.s('Asset created Successfully.');
                        eventBus.$emit('listMachines', res.data)
                    })
                    .catch(error => error.response)
            },
            update() {
                axios.patch(`machines/${this.form.id}`, this.form)
                    .then(res => {
                        this.edit_machine = false;
                        this.$toastr.s('Asset updated Successfully.');
                        eventBus.$emit('updateMachine', res.data);
                    })
                    .catch(error => error.response)
            },
            cancel() {
                eventBus.$emit('cancel')
            },
            listen() {
                if (this.edit) {
                    this.form = this.$store.state.assets.machine

                }
            },
        }
    }
</script>

<style scoped>

</style>
