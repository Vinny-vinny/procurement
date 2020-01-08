import Vue from 'vue';
import VueRouter from "vue-router";
import Login from "../components/auth/Login";
import Register from "../components/auth/Register";
import Category from "../components/categories/Index";
import Users from "../components/users/Index";
import ReqType from "../components/requistions/types/Index";
import Requisition from "../components/requistions/Index";
import Department from "../components/departments/Index";
import ItemType from "../components/items/types/Index";
import Priority from "../components/priorities/Index";
import Project from "../components/projects/Index";
import Items from "../components/items/Index";
import Assets from "../components/assets/Index";
import ServiceProviders from "../components/suppliers/Index";
import Budget from "../components/budgets/Index";
import Enquiry from "../components/pos/enquiries/Index";
import Quotation from "../components/quotations/Index";
import PurchaseOrder from "../components/pos/po/Index";

Vue.use(VueRouter);
function guard(to, from, next) {
    if (User.loggedIn()) {
        next();
    } else {
        next('/login');
    }
}
const routes = [
    {path:'/login',component: Login},
    {path:'/signup',component: Register},
    {path:'/users',component: Users, beforeEnter: guard},
    {path:'/categories',component:Category, beforeEnter: guard},
    {path:'/requisition-type',component:ReqType, beforeEnter: guard},
    {path:'/requisitions',component:Requisition, beforeEnter: guard},
    {path:'/departments',component:Department, beforeEnter: guard},
    {path:'/item-types',component:ItemType, beforeEnter: guard},
    {path:'/priorities',component:Priority, beforeEnter: guard},
    {path:'/projects',component:Project, beforeEnter: guard},
    {path:'/items',component:Items, beforeEnter: guard},
    {path:'/all-assets',component:Assets, beforeEnter: guard},
    {path:'/suppliers',component:ServiceProviders, beforeEnter: guard},
    {path:'/budget',component:Budget, beforeEnter: guard},
    {path:'/enquiry',component:Enquiry, beforeEnter: guard},
    {path:'/quotations',component:Quotation, beforeEnter: guard},
    {path:'/purchase-order',component:PurchaseOrder, beforeEnter: guard},

];

export default new VueRouter({
    routes,
    mode: 'history'
});
