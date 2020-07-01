const state = () => ({
    all_my_machines:{},
    machine:{}
});

const getters = {
    all_machines(state){
        return state.all_my_machines;
    },
};
const actions = {
    my_machines({commit}){
        axios.get('machines')
            .then(res => {
                commit('my_machines',res.data);
            })
    },
    updateMachine({commit},user){
        commit('updateMachine',user);
    },
};
const mutations = {
    my_machines(state,data){
        state.all_my_machines = data;
    },
    updateMachine(state,machine){
        state.machine = machine;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
