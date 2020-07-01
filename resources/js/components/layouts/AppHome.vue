<template>

    <div :class="{wrapper:nextPath,wrappercustom:!nextPath}">
    <app-top-nav v-if="nextPath"></app-top-nav>
    <app-sidebar v-if="nextPath"></app-sidebar>
    <div :class="{'content-wrapper':nextPath,wrapperlogin:!nextPath}">
    <router-view></router-view>
    </div>
<app-footer v-if="nextPath"></app-footer>

</div>
</template>
<script>
    import AppFooter from "./AppFooter";
    import AppSidebar from "./AppSidebar";
    import AppTopNav from "./AppTopNav";
    export default {
        computed:{
            nextPath(){
              return !(this.$store.state.path_to =='/login' || this.$store.state.path_to=='/signup');
            }
        },
        created(){

            if (!User.loggedIn()){
                if ($cookies.isKey('auth_email')){
                    axios.post('/auth/signin',{email:$cookies.get('auth_email')})
                        .then(res => {
                            if (res.data==='nouser'){
                                this.$toastr.e(`Sorry, Email ${$cookies.get('auth_email')} does not exits.`)
                                setTimeout(()=>{
                                    window.location.href = dashboad_url;
                                },1500)
                            }else{
                                return User.responseAfterLogin(res)
                            }
                        })
                }else{
                    window.location.href = dashboad_url;
                }
            }
        },
      components:{
          AppFooter,
          AppSidebar,
          AppTopNav
      }
    }
</script>

<style>
    .wrapperlogin{
        /*margin-right: 10%;*/
        /*margin-left: 10%;*/
        /*height: 100vh;*/
        /*background: darkgray;*/
        /*margin-bottom: 20%;*/

    }
    .wrappercustom{

    }
</style>
