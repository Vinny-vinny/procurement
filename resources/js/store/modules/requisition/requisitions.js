const state = () => ({
    all_my_requisitions:{},
    all_my_filtered_requisitions:{},
    requisition:{}
});

const getters = {
    all_requisitions(state){
        return state.all_my_requisitions;
    },
    all_filtered_requisitions(state){
        return state.all_my_filtered_requisitions;
    },
};
const actions = {
    my_requisitions({commit}){
        axios.get('requisitions')
            .then(res => {
                commit('my_requisitions',res.data.requisitions);
                commit('my_filtered_requisitions',res.data.filtered_requisitions);
            })
    },
    updateRequisition({commit},req){
        commit('updateRequisition',req);
    },
};
const mutations = {
    my_requisitions(state,data){
        state.all_my_requisitions = data;
    },
    my_filtered_requisitions(state,data){
        state.all_my_filtered_requisitions = data;
    },
    updateRequisition(state,req){
        state.requisition = req;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
