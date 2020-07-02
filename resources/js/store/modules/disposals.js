const state = () => ({
    all_my_disposals:{},
    disposal:{}
});

const getters = {
    all_disposals(state){
        return state.all_my_disposals;
    },
};
const actions = {
    my_disposals({commit}){
        axios.get('asset-disposal')
            .then(res => {
                commit('my_disposals',res.data);
            })
    },
    updateDisposal({commit},user){
        commit('updateDisposal',user);
    },
};
const mutations = {
    my_disposals(state,data){
        state.all_my_disposals = data;
    },
    updateDisposal(state,disposal){
        state.disposal = disposal;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
