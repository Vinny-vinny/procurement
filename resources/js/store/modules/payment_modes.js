const state = () => ({
    all_my_modes:{},
    mode:{}
});

const getters = {
    all_modes(state){
        return state.all_my_modes;
    },
};
const actions = {
    my_modes({commit}){
        axios.get('payment-modes/')
            .then(res => {
                commit('my_modes',res.data);
            })
    },
    updateMode({commit},user){
        commit('updateMode',user);
    },
};
const mutations = {
    my_modes(state,data){
        state.all_my_modes = data;
    },
    updateMode(state,mode){
        state.mode = mode;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
