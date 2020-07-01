const state = () => ({
    all_my_enquiries:{},
    enquiry:{}
});

const getters = {
    all_enquiries(state){
        return state.all_my_enquiries;
    },
};
const actions = {
    my_enquiries({commit}){
        axios.get('enquiry')
            .then(res => {
           commit('my_enquiries',res.data);
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
