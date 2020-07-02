const state = () => ({
    all_my_awards:{},
    award:{}
});

const getters = {
    all_awards(state){
        return state.all_my_awards;
    },
};
const actions = {
    my_awards({commit}){
        axios.get('bid-award')
            .then(res => {
                commit('my_awards',res.data);
            })
    },
    updateAward({commit},user){
        commit('updateAward',user);
    },
};
const mutations = {
    my_awards(state,data){
        state.all_my_awards = data;
    },
    updateAward(state,award){
        state.award = award;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
