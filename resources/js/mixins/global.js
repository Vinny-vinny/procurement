export default {
    created(){
        this.listen();
    },
    methods:{
        cancel(){
            eventBus.$emit('cancel')
        },
    }
}
