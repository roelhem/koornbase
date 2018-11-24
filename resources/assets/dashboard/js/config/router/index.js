import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

export const routes = [

    {
        path:'/home',
        alias:'/',
        name:'home',
        component: require('../../views/home/index'),
    },


    {
        path:'/me',
        name:'me.overview',
        component: require('../../views/me/overview'),
        children: [
            {
                path:'user',
                name:'me.overview.user',
                component: require('../../views/me/overview.user'),
            },
            {
                path:'personal',
                name:'me.overview.personal',
                component: require('../../views/me/overview.personal'),
            },
            {
                path:'koornbeurs',
                name:'me.overview.koornbeurs',
                component: require('../../views/me/overview.koornbeurs'),
            },
        ],
    },


    {
        path:'/oauth/apps',
        name:'oauth.apps.index',
        component: require('../../views/oauth/apps/index'),
    },
    {
        path:'/oauth/clients',
        name:'oauth.clients.index',
        component: require('../../views/oauth/clients/index'),
    },
    {
        path:'/oauth/clients/:id',
        name:'oauth.clients.view',
        component: require('../../views/oauth/clients/view'),
        props:true,
    },


    {
        path:'/db/persons',
        name:'db.persons.index',
        component: require('../../views/persons/index'),
    },
    {
        path:'/db/persons/:id',
        name:'db.persons.view',
        component: require('../../views/persons/view'),
        props:true,
        children: [
            {
                path:'contact',
                name:'db.persons.view.contact',
                component: require('../../views/persons/view.contact'),
            },
            {
                path:'debug',
                name:'db.persons.view.debug',
                component: require('../../views/persons/view.debug'),
            },
            {
                path:'membership',
                name:'db.persons.view.membership',
                component: require('../../views/persons/view.membership'),
            },
            {
                path:'overview',
                name:'db.persons.view.overview',
                component: require('../../views/persons/view.overview'),
            },
        ]
    },

    {
        path:'/db/groups',
        name:'db.groups.index',
        component: require('../../views/groups/index'),
    },
    {
        path:'/db/groups/create',
        name:'db.groups.create',
        component: require('../../views/groups/create'),
    },
    {
        path:'/db/groups/:id',
        name:'db.groups.view',
        component: require('../../views/groups/view'),
        props:true,
    },


    {
        path:'/users',
        name:'users.index',
        component: require('../../views/users/index'),
    },
    {
        path:'/users/create',
        name:'users.create',
        component: require('../../views/users/create'),
    },
    {
        path:'/users/:id',
        name:'users.view',
        component: require('../../views/users/view'),
        props:true,
    },
];

export const router = new VueRouter({
    routes
});

export default router;