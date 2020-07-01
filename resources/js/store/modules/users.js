const state = () => ({
    all_my_users:{},
    user:{}
});

const getters = {
    all_users(state){
        return state.all_my_users;
    },
};
const actions = {
    my_users({commit}){
        axios.get('users')
            .then(res => {
                commit('my_users',res.data);
        })
    },
    updateUser({commit},user){
        commit('updateUser',user);
    },
};
const mutations = {
    my_users(state,data){
        state.all_my_users = data;
    },
    updateUser(state,user){
        state.user = user;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
