const state = () => ({
    all_my_priorities:{},
    priority:{}
});

const getters = {
    all_priorities(state){
        return state.all_my_priorities;
    },
};
const actions = {
    my_priorities({commit}){
        axios.get('priorities')
            .then(res => {
                commit('my_priorities',res.data);
            })
    },
    updatePriority({commit},user){
        commit('updatePriority',user);
    },
};
const mutations = {
    my_priorities(state,data){
        state.all_my_priorities = data;
    },
    updatePriority(state,priority){
        state.priority = priority;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
