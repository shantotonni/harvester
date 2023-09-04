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
import ServiceCenters from '../views/service_centers/List'
import Profile from '../views/profile/Profile'
import AdminDashboard from '../views/admin_dashboard/Index'
import HarvesterService from '../views/harvester_service/List'
import ServiceTypes from '../views/service_types/List'
import ServicingTypes from '../views/servicing_types/List'
import HarvesterInfo from '../views/harvester_info/List'
import SeasonalCrops from '../views/seasonal_crops/List'
import HarvesterParts from '../views/harvester_parts/List'
import ShowroomList from '../views/showroom/List'
import ServiceTips from '../views/service_tips/List'
import ServiceEngineer from '../views/service_engineer/List'
import CompleteService from '../views/service_request/CompletedList.vue'
import PendingService from '../views/service_request/PendingList'
import ServiceRequestDetails from '../views/service_request/Details.vue'
import SectionList from '../views/section/List'
import CustomerInfo from '../views/customer_info/List'
import ProductModel from '../views/product_model/List'
import HarvestingCostList from '../views/harvesting_cost/List'

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
            //Service centers
            {
                path: base_url + '/service-centers', name: 'ServiceCenters', component: ServiceCenters
            },
          //harvester service
            {
                path: base_url + '/harvester-service', name: 'HarvesterService', component: HarvesterService
            },
            //harvester info
            {
                path: base_url + '/harvester-info', name: 'HarvesterInfo', component: HarvesterInfo
            },
            //Harvester Service type
            {
                path: base_url + '/service-types', name: 'ServiceTypes', component: ServiceTypes
            },
            //Harvester Servicing types
            {
                path: base_url + '/servicing-types', name: 'ServicingTypes', component: ServicingTypes
            },
            //District wise seasonal crops
            {
                path: base_url + '/district-wise-seasonal-crops', name: 'SeasonalCrops', component: SeasonalCrops
            },
            //Harvester Parts
            {
                path: base_url + '/harvester-parts', name: 'HarvesterParts', component: HarvesterParts
            },
            //Showroom
            {
                path: base_url + '/showroom-list', name: 'ShowroomList', component: ShowroomList
            },
            //Service tips
            {
                path: base_url + '/service-tips', name: 'ServiceTips', component: ServiceTips
            },

            //Service Engineer
            {
                path: base_url + '/service-engineer', name: 'ServiceEngineer', component: ServiceEngineer
            },
            //Pending Service Request
            {
                path: base_url + '/pending-service-request-list', name: 'PendingService', component: PendingService
            },
            //Complete Service Request
            {
                    path: base_url + '/completed-service-request-list', name: 'CompleteService', component: CompleteService
            },
            //Details Service Request
            {
                    path: base_url + '/service-request-details/:id', name: 'ServiceRequestDetails', component: ServiceRequestDetails
            },
            //Section Request
            {
                    path: base_url + '/section-list', name: 'SectionList', component: SectionList
            },

            //Customer list
            {
                    path: base_url + '/customer-info', name: 'CustomerInfo', component: CustomerInfo
            },
            //Product model list
            {
                    path: base_url + '/product-model', name: 'ProductModel', component: ProductModel
            },
            //harvesting cost list
            {
                    path: base_url + '/harvesting-cost-list', name: 'HarvestingCostList', component: HarvestingCostList
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
