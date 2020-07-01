const state = () => ({
    all_my_suppliers:{},
    supplier:{},
});

const getters = {
    all_suppliers(state){
        return state.all_my_suppliers;
    },
};
const actions = {
    my_suppliers({commit}){
        axios.get('suppliers')
            .then(res => {
                commit('my_suppliers',res.data);
            })
    },
    updateSupplier({commit},user){
        commit('updateSupplier',user);
    },
};
const mutations = {
    my_suppliers(state,data){
        state.all_my_suppliers = data;
    },
    updateSupplier(state,supplier){
        state.supplier = supplier;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
