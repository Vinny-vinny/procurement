const state = () => ({
    all_my_quotations:{},
    all_my_filtered_quotations:{},
    quotation:{}
});

const getters = {
    all_quotations(state){
        return state.all_my_quotations;
    },
    all_filtered_quotations(state){
     return state.all_my_filtered_quotations;
    },
};
const actions = {
    my_quotations({commit}){
        axios.get('quotations')
            .then(res => {
                console.log(res)
                commit('my_quotations',res.data.quotations);
                commit('my_filtered_quotations',res.data.filtered_quotations);
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
    my_filtered_quotations(state,data){
     state.all_my_filtered_quotations = data;
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
