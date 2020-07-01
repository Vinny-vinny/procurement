const state = () => ({
    all_my_services:{},
    service:{}
});

const getters = {
    all_services(state){
        return state.all_my_services;
    },
};
const actions = {
    my_services({commit}){
        axios.get('services')
            .then(res => {
                commit('my_services',res.data);
            })
    },
    updateService({commit},service){
        commit('updateService',service);
    },
};
const mutations = {
    my_services(state,data){
        state.all_my_services = data;
    },
    updateService(state,service){
        state.service = service;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
