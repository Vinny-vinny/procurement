const state = () => ({
    all_my_enquiries:{},
    all_my_filtered_enquiries:{},
    enquiry:{}
});

const getters = {
    all_enquiries(state){
        return state.all_my_enquiries;
    },
    all_filtered_enquiries(state){
    return state.all_my_filtered_enquiries;
    },
};
const actions = {
    my_enquiries({commit}){
        axios.get('enquiry')
            .then(res => {
           commit('my_enquiries',res.data.enquiries);
           commit('my_filtered_enquiries',res.data.filtered_enquiries);
            })
    },
    updateEnquiry({commit},enq){
        commit('updateEnquiry',enq);
    },
};
const mutations = {
    my_enquiries(state,data){
        state.all_my_enquiries = data;
    },
    my_filtered_enquiries(state,data){
        state.all_my_filtered_enquiries = data;
    },
    updateEnquiry(state,enq){
        state.enquiry = enq;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
