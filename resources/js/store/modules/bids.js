const state = () => ({
    all_my_bids:{},
    bid:{}
});

const getters = {
    all_bids(state){
        return state.all_my_bids;
    },
};
const actions = {
    my_bids({commit}){
        axios.get('bidding')
            .then(res => {
                console.log('-------------')
                console.log(res.data)
                console.log('=============')
                commit('my_bids',res.data);
            })
    },
    updateBid({commit},user){
        commit('updateBid',user);
    },
};
const mutations = {
    my_bids(state,data){
        state.all_my_bids = data;
    },
    updateBid(state,bid){
        state.bid = bid;
    },
};

export default {
    namespace:true,
    state,
    getters,
    actions,
    mutations
}
