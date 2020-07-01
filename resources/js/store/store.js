import Vue from 'vue';
import Vuex from 'vuex';
import users from "./modules/users";
import suppliers from "./modules/suppliers";
import categories from "./modules/categories";
import reqTypes from "./modules/requisition/types";
import requisitions from "./modules/requisition/requisitions";
import departments from "./modules/departments";
import priorities from "./modules/priorities";
import payment_modes from "./modules/payment_modes";
import services from "./modules/services";
import projects from "./modules/projects";
import assets from "./modules/assets";
import parts from "./modules/parts";
import budgets from "./modules/budgets";
import itemtypes from "./modules/itemtypes";
import uoms from "./modules/uoms";
import purchase_enquiries from "./modules/purchase_enquiries";
import purchaseorders from "./modules/purchaseorders";
import quotations from "./modules/quotations";


Vue.use(Vuex);

export default new Vuex.Store({
    modules:{
    users,
    suppliers,
    categories,
    reqTypes,
    departments,
    priorities,
    payment_modes,
    services,
    projects,
    assets,
    parts,
    budgets,
    itemtypes,
    requisitions,
    uoms,
    purchase_enquiries,
    purchaseorders,
    quotations
    },

    state:{
        path_to:'/',
        customers:{},
        machines:{},
        disposals:{},
        bids:{},
        awards:{},
    },
    mutations:{
     pathTo(state, to) {
     state.path_to = to;
     },

        updateCustomer(state,customer){
         state.customers = customer;
        },
       updateDisposal(state,disposal){
       state.disposals = disposal;
       },

       updateBid(state,bid){
        state.bids = bid;
       },
       updateAward(state,award){
        state.awards = award;
       },

    },

    actions:{
    pathTo({commit},to){
     commit('pathTo',to);
    },

        updateCustomer({commit},customer){
        commit('updateCustomer',customer);
        },

       updatePurchaseOrder({commit},po){
        commit('updatePurchaseOrder',po);
       },
       updateDisposal({commit},disposal){
        commit('updateDisposal',disposal);
       },

       updateBid({commit},bid){
        commit('updateBid',bid);
       },
       updateAward({commit},award){
        commit('updateAward',award);
       },

    }
})
