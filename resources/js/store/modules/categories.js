const state = () => ({
    all_my_categories:{},
    category:{}
});

const getters = {
    all_categories(state){
        return state.all_my_categories;
    },
};
const actions = {
    my_categories({commit}){
        axios.get('categories')
            .then(res => {
                commit('my_categories',res.data);
            })
    },
    updateCategory({commit},user){
        commit('updateCategory',user);
    },
};
const mutations = {
    my_categories(state,data){
        state.all_my_categories = data;
    },
    updateCategory(state,category){
        state.category = category;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
