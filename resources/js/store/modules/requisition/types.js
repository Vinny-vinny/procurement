const state = () => ({
    all_my_req_types:{},
    req_type:{}
});

const getters = {
    all_req_types(state){
        return state.all_my_req_types;
    },
};
const actions = {
    my_req_types({commit}){
        axios.get('requisition-types')
            .then(res => {
                commit('my_req_types',res.data);
            })
    },
    updateReqType({commit},user){
        commit('updateReqType',user);
    },
};
const mutations = {
    my_req_types(state,data){
        state.all_my_req_types = data;
    },
    updateReqType(state,type){
        state.req_type = type;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
