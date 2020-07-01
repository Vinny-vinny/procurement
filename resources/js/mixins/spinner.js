import PulseLoader from 'vue-spinner/src/ScaleLoader.vue'

export default {
    data(){
      return{
          loading:true,
          color:'#3c8dbc',
          size:'100px'
      }
    },
    components:{
        PulseLoader
    }
}
