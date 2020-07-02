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
import disposals from "./modules/disposals";
import awards from "./modules/awards";
import bids from "./modules/bids";


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
    quotations,
    disposals,
    awards,
    bids
    },

    state:{
        path_to:'/',
        customers:{},
    },
    mutations:{
     pathTo(state, to) {
     state.path_to = to;
     },

        updateCustomer(state,customer){
         state.customers = customer;
        },
       updateBid(state,bid){
        state.bids = bid;
       }

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
       updateBid({commit},bid){
        commit('updateBid',bid);
       },
      }
})
