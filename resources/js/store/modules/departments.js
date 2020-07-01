const state = () => ({
    all_my_departments:{},
    department:{}
});

const getters = {
    all_departments(state){
     return state.all_my_departments;
    },
};
const actions = {
    my_departments({commit}){
        axios.get('departments')
            .then(res => {
                commit('my_departments',res.data);
            })
    },
    updateDepartment({commit},user){
        commit('updateDepartment',user);
    },
};
const mutations = {
    my_departments(state,data){
        state.all_my_departments = data;
    },
    updateDepartment(state,department){
        state.department = department;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
