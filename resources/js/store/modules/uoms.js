const state = () => ({
    all_my_uoms:{}
});

const getters = {
    all_uoms(state){
        return state.all_my_uoms;
    },
};
const actions = {
    my_uoms({commit}){
        axios.get('uom')
            .then(res => {
                commit('my_uoms',res.data);
            })
    },
};
const mutations = {
    my_uoms(state,data){
        state.all_my_uoms = data;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
