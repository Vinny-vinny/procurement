const state = () => ({
    all_my_projects:{}
});

const getters = {
    all_projects(state){
        return state.all_my_projects;
    },
};
const actions = {
    my_projects({commit}){
        axios.get('projects')
            .then(res => {
                commit('my_projects',res.data);
            })
    }
};
const mutations = {
    my_projects(state,data){
        state.all_my_projects = data;
    },

};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
