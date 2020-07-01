const state = () => ({
    all_my_pos:{},
    po:{}
});

const getters = {
    all_pos(state){
        return state.all_my_pos;
    },
};
const actions = {
    my_pos({commit}){
        axios.get('purchase-order')
            .then(res => {
             commit('my_pos',res.data);
            })
    },
    updatePo({commit},user){
        commit('updatePo',user);
    },
};
const mutations = {
    my_pos(state,data){
        state.all_my_pos = data;
    },
    updatePo(state,po){
        state.po = po;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
