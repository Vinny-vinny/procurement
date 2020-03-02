import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        path_to:'/',
        supplier:{},
        categories:{},
        users:{},
        customers:{},
        requisitions:{},
        req_types:{},
        departments:{},
        item_types:{},
        priorities:{},
        machines:{},
        budgets:{},
        enquiries:{},
        quotations:{},
        purchase_orders:{},
        services:{},
        disposals:{},
        bids:{},
        modes:{},
        awards:{}
    },
    mutations:{
     pathTo(state, to) {
     state.path_to = to;
     },
        updateSupplier(state,supplier){
         state.supplier = supplier;
        },

        updateCategory(state,category){
         state.categories = category;
        },
        updateUser(state,user){
         state.users = user;
        },
        updateCustomer(state,customer){
         state.customers = customer;
        },
        updateRequisition(state,rq){
         state.requisitions = rq;
        },
        updateReqType(state,type){
         state.req_types = type;
        },
        updateDepartment(state,dept){
         state.departments = dept;
        },
        updateItemType(state,item){
         state.item_types = item;
        },
        updatePriority(state,prio){
         state.priorities = prio;
        },
        updateMachine(state,asset){
         state.machines = asset;
        },
        updateBudget(state,budget){
         state.budgets = budget;
        },
        updateEnquiry(state,enquiry){
        state.enquiries = enquiry;
        },
        updateQuotation(state,quotation){
        state.quotations = quotation;
        },
       updatePurchaseOrder(state,po){
       state.purchase_orders = po;
       },
       updateService(state,service){
        state.services = service;
       },
       updateDisposal(state,disposal){
       state.disposals = disposal;
       },
       updateMode(state,mode){
       state.modes = mode;
       },
       updateBid(state,bid){
        state.bids = bid;
       },
       updateAward(state,award){
        state.awards = award;
       }
    },
    actions:{
    pathTo({commit},to){
     commit('pathTo',to);
    },
        updateSupplier({commit},supplier){
        commit('updateSupplier',supplier);
        },
        updateCategory({commit},category){
        commit('updateCategory',category);
        },
        updateUser({commit},user){
        commit('updateUser',user);
        },
        updateCustomer({commit},customer){
        commit('updateCustomer',customer);
        },
        updateRequisition({commit},rq){
        commit('updateRequisition',rq);
        },
        updateReqType({commit},type){
        commit('updateReqType',type);
        },
        updateDepartment({commit},department){
        commit('updateDepartment',department);
        },
        updateItemType({commit},item){
        commit('updateItemType',item);
        },
        updatePriority({commit},prio){
        commit('updatePriority',prio);
        },
        updateMachine({commit},asset){
        commit('updateMachine',asset);
        },
       updateBudget({commit},budget){
        commit('updateBudget',budget);
       },
       updateEnquiry({commit},enquiry){
        commit('updateEnquiry',enquiry);
       },
       updateQuotation({commit},quotation){
      commit('updateQuotation',quotation);
       },
       updatePurchaseOrder({commit},po){
        commit('updatePurchaseOrder',po);
       },
       updateService({commit},service){
        commit('updateService',service);
       },
       updateDisposal({commit},disposal){
        commit('updateDisposal',disposal);
       },
       updateMode({commit},mode){
        commit('updateMode',mode);
       },
       updateBid({commit},bid){
        commit('updateBid',bid);
       },
       updateAward({commit},award){
        commit('updateAward',award);
       }
    }
})
