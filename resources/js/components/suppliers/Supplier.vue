<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_supplier ? 'Update Supplier' : 'New Supplier'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveSupplier()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" v-model="form.email" required>
                        </div>
                        <div class="form-group">
                            <label>Account</label>
                            <input type="text" class="form-control" v-model="form.account" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" v-model="form.phone" required>
                        </div>
                          <div class="form-group">
                            <label>Physical Address</label>
                            <input type="text" class="form-control" v-model="form.physical_address" required>
                        </div>
                        <div class="form-group">
                            <label>Tax No</label>
                            <input type="text" class="form-control" v-model="form.tax_no" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_supplier ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import suppliers from "../../store/modules/suppliers";

    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',
                    email:'',
                    phone:'',
                    physical_address:'',
                    account:'',
                    tax_no:'',
                    id:''
                    },
                edit_supplier: this.edit
                }
        },
        created(){
            this.listen();
        },
        methods:{
            saveSupplier(){
                this.edit_supplier ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                    axios.post('suppliers',this.form)
                    .then(res => {
                        this.$store.state.suppliers.all_my_suppliers.unshift(res.data);
                        eventBus.$emit('listSuppliers',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                    axios.patch(`suppliers/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_supplier = false;
                        eventBus.$emit('updateSupplier',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.suppliers.supplier;
                    }
            },
        }
    }
</script>

<style scoped>

</style>
