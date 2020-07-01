const state = () => ({
    all_item_types:{}
});

const getters = {
    all_item_types(state){
        return state.all_item_types;
    },
};
const actions = {
    my_item_types({commit}){
        axios.get('item-types')
            .then(res => {
                commit('my_item_types',res.data);
            })
    },
};
const mutations = {
    my_item_types(state,data){
        state.all_item_types = data;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
