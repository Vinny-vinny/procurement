const state = () => ({
    all_my_budgets:{},
    all_my_filtered_budgets:{},
    budget:{}
});

const getters = {
    all_budgets(state){
        return state.all_my_budgets;
    },
    all_filtered_budgets(state){
     return state.all_my_filtered_budgets;
    },
};
const actions = {
    my_budgets({commit}){
        axios.get('department-budget')
            .then(res => {
                commit('my_budgets',res.data.all);
                commit('my_filtered_budgets',res.data.filtered);
            })
    },
    updateBudget({commit},budget){
        commit('updateBudget',budget);
    },
};
const mutations = {
    my_budgets(state,data){
        state.all_my_budgets = data;
    },
    my_filtered_budgets(state,data){
        state.all_my_filtered_budgets = data;
    },
    updateBudget(state,budget){
        state.budget = budget;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
