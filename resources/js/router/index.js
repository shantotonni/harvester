import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);
const base_url = '/harvester';

const checkToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === undefined || token === null || token === '') {
        next(base_url + '/login');
    } else {
        next();
    }
};

const activeToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === undefined || token === null || token === '') {
        next();
    } else {
        next(base_url + '/');
    }
};

import Login from "../views/auth/Login";
//main route
import Main from "../components/layouts/Main";

import Dasboard from '../views/dashboard/Index'
import UserList from '../views/user/List'
import MenuPermission from '../views/user/MenuPermission'
import MenuList from '../views/menu/List'
import MenuCreate from '../views/menu/Create'
import MenuEdit from '../views/menu/Edit'
import ChangePassword from '../views/settings/ChangePassword'
import CustomerList from '../views/customer/List'
import RoleList from '../views/role/List'
import BrandList from '../views/brand/List'
import Profile from '../views/profile/Profile'
import AdminDashboard from '../views/admin_dashboard/Index'
import Portfolio from '../views/portfolio/List.vue'
import Category from '../views/category/List.vue'
import Banner from '../views/banner/List.vue'
import Product from '../views/product/List.vue'
import Doctor from '../views/doctor/List.vue'
import MOInfo from '../views/moinfo/List.vue'
import Shop from '../views/shop/List.vue'
import District from '../views/district/List.vue'
import Upazila from '../views/upazila/List.vue'
const routes = [
    {
        path: base_url + '/',
        component: Main,
        redirect: {name:'Dashboard'},
        children: [
            {
                path: base_url + '/dashboard', name: 'Dashboard', component: Dasboard
            },
            //user vue route
            {
                path: base_url + '/user-list', name: 'UserList', component: UserList
            },
            {
                path: base_url + '/user-menu-permission', name: 'UserMenuPermission', component: MenuPermission
            },
            //menu vue route
            {
                path: base_url + '/menu-list', name: 'MenuList', component: MenuList
            },
            {
                path: base_url + '/menu-create', name: 'MenuCreate', component: MenuCreate
            },
            {
                path: base_url + '/menu-edit/:MenuId', name: 'MenuEdit', component: MenuEdit
            },
            {
                path: base_url + '/change-password', name: 'ChangePassword', component: ChangePassword
            },
            //Role vue route
            {
                path: base_url + '/role-list', name: 'RoleList', component: RoleList
            },
            //Brand vue route
            {
                path: base_url + '/brand-list', name: 'BrandList', component: BrandList
            },
            //user profile route
            {
                path: base_url + '/profile', name: 'Profile', component: Profile
            },
            //customer vue route
            {
                path: base_url + '/customer-list', name: 'CustomerList', component: CustomerList
            },
            //admin dashboard
            {
                path: base_url + '/admin-dashboard', name: 'AdminDashboard', component: AdminDashboard
            },
           // portfolio route
            {
                path: base_url + '/portfolio-list', name: 'Portfolio', component: Portfolio
            },
            //category route
            {
                path: base_url + '/category-list', name: 'Category', component: Category
            },
            //banner route
            {
                path: base_url + '/banner-list', name: 'Banner', component: Banner
            },
            //product route
            {
                path: base_url + '/product-list', name: 'Product', component: Product
            },
            //doctor route
            {
                path: base_url + '/doctor-list', name: 'Doctor', component: Doctor
            },
            //MOInfo route
            {
                path: base_url + '/moinfo-list', name: 'MOInfo', component: MOInfo
            },
            //MOInfo route
            {
                path: base_url + '/shop-list', name: 'Shop', component: Shop
            },
            //District route
            {
                path: base_url + '/district-list', name: 'District', component: District
            },
            //Upazila route
            {
                path: base_url + '/upazila-list', name: 'Upazila', component: Upazila
            },
        ],
        beforeEnter(to, from, next) {
            checkToken(to, from, next);
        }
    },
    {
        path: base_url + '/login',
        name: 'Login',
        component: Login,
        beforeEnter(to, from, next) {
            activeToken(to, from, next);
        }
    },
]

const router = new VueRouter({
    mode: 'history',
    routes
});

router.afterEach(() => {
    $('#preloader').hide();
});

export default router
