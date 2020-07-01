const state = () => ({
    all_my_quotations:{},
    quotation:{}
});

const getters = {
    all_quotations(state){
        return state.all_my_quotations;
    },
};
const actions = {
    my_quotations({commit}){
        axios.get('quotation')
            .then(res => {
                commit('my_quotations',res.data);
            })
    },
    updateQuotation({commit},user){
        commit('updateQuotation',user);
    },
};
const mutations = {
    my_quotations(state,data){
        state.all_my_quotations = data;
    },
    updateQuotation(state,quotation){
        state.quotation = quotation;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
