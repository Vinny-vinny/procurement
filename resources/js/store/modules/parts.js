const state = () => ({
    all_my_parts:{},
    part:{}
});

const getters = {
    all_parts(state){
        return state.all_my_parts;
    },
};
const actions = {
    my_parts({commit}){
        axios.get('parts')
            .then(res => {
                commit('my_parts',res.data);
            })
    },
    updatePart({commit},part){
        commit('updatePart',part);
    },
};
const mutations = {
    my_parts(state,data){
        state.all_my_parts = data;
    },
    updatePart(state,part){
        state.part = part;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
